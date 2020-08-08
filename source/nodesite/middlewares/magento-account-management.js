const config                   = require('../config.json')
const baseURL                  = config.magento.api_base_url;
const axios                    = require('axios');
const Promise                  = require('promise')
const redis                    = require('./redis');
const ResetPasswordTokenModel  = require('../models/reset_password_token');
const rsmq                     = require('../background-job/redis-message-queue');

/**
 * 
 * @param {*} req 
 * @param {*} res 
 * @param {object} user 
 * @returns  {object} Promise object(token, error)
 */
function getUserToken(req, res , user)
{
    var header = { 
        'Content-Type': 'application/json' 
    };
    var data = JSON.stringify({ 
        "username": user.email,
        "password": user.password
    })
    var URL = baseURL + '/V1/integration/customer/token';
    console.log(URL);
    return new Promise((resolve, reject) => {
        axios.post(
            URL,
            data,
            { headers: header }
        )
        .then(response => {
            userToken = response.data;
            req.session.token = userToken;
            resolve(userToken);
        })
        .catch(error => {
            console.error(error);
            res.render("account/login", {page:"login", error: 1, msg: error});
        });
    });
}


/**
 * 
 * @param {string} userToken
 * @returns  {object} Promise object(userProfile, error)
 */
function getUserProfile(userToken) {
    var header = { 
        Authorization: 'Bearer ' + userToken 
    };
    var URL = baseURL + '/V1/customers/me'
    return new Promise((resolve, reject) => {
        axios.get( URL, { headers: header})
        .then(response => {
            var userProfile = response.data;
            resolve(userProfile)
        })
        .catch(error => {
            reject(error);
        });
    });
   
}

/**
 *  
 * @param {string} email
 * @returns {object} Promise 
 */
function requestResetPasswordToken(email)
{
    //redis_key,
    redis_key = "forgot_" + email;
    return new Promise((resolve, reject) => {
        redis.client.get(redis_key,(err, result) => {
            if(result)
            {
                //Lay thoi gian hien tai tru thoi gian o db => message
                reject({code:1, message: "Please request after 60s!"})
                //Log: please send request after 60s
                //Return: Check mail 
            }
            else{
                ResetPasswordTokenModel.create({
                    user_email: email,
                    resetPasswordToken: null,
                    status:false
                }).then(
                    result =>{
                        redis.client.set(redis_key,"saved_db",redis.print);
                        redis.client.expire(redis_key, 60);
                        resolve(email);
                    }
                )
                .catch(
                    err => { 
                        console.log(err);
                        reject({code:2, message: "Opp! An error occurred! Please try again"})
                    }
                )
            }
        })

    })
}

/**
 * Get reset password token
 * @param {string} email 
 * @returns {object} Promise object(token, error)
 */
function getResetPasswordToken(email)
{
    var header = { 
        'Content-Type': 'application/json' 
    };
    var data = JSON.stringify({
        "email": email,
    })
    var URL = baseURL + '/V1/sm/customer/requestResetPassword';
    return new Promise((resolve, reject) => {
        axios.put(
            URL,
            data,
            { headers: header }
        )
        .then(function(response){
            var resetInfo = response.data;
            resolve(resetInfo)
        })
        .catch(error => {
            reject(error);
        });
    });
}


/**
 * Check if reset password token is valid.
 * @param {Int16Array} userId 
 * @param {string} resetPasswordToken 
 * @returns {object} (result, error)
 */
function checkResetPasswordTokenIsValid(userId, resetPasswordToken)
{
    var URL = baseURL + `/V1/customers/${userId}/password/resetLinkToken/${resetPasswordToken}`;
    return new Promise((resolve, reject) => {
        axios.get(URL)
        .then(function(response){
            var result = response.data;
            console.log(result);
            resolve(result);
        })
        .catch((error) => {
            //TODO: có cần báo cho user biết email sai ko ?
            reject(error);
        });
    });
}

/**
 * 
 * @param {string} password 
 * @returns {Int16Array} 0: satisfy | 3: unsatify
 */
function makeRequiredCharactersCheck(password)
{
    var counter = 0;
    const requiredNumber = 3;
    var result = 0;

    if (password.match(/[0-9]+/)) {
        counter++;
    }
    if (password.match(/[A-Z]+/)) {
        counter++;
    }
    if (password.match(/[a-z]+/)) {
        counter++;
    }
    if (password.match(/[^a-zA-Z0-9]+/)) {
        counter++;
    }
    if (counter < requiredNumber) {
        result = requiredNumber;
    }

    return result;
}

/**
 * Validate password before call API 
 * @param {string} newPassword 
 * @param {string} confirmPassword 
 * @returns {object} Promise object(newPassword, error)
 */
function validatePassword(newPassword, confirmPassword)
{ 
    return new Promise((resolve, reject) => {
        var requiredCharactersCheck = makeRequiredCharactersCheck(newPassword);
        console.log(requiredCharactersCheck);
        if(newPassword != confirmPassword)
        {
            reject('Password and confirm password does not match');
        }
        else if(newPassword.length < 8)
        {
            reject('The password needs at least 8 characters. Create a new password and try again.');
        }
        else if(newPassword.trimLeft() != newPassword || newPassword.trimRight() != newPassword)
        {
            reject('The password can\'t begin or end with a space. Verify the password and try again.');
        }
        else if(requiredCharactersCheck != 0)
        {
            reject(`Minimum of different classes of characters in password is ${requiredCharactersCheck}. Classes of characters: Lower Case, Upper Case, Digits, Special Characters.`);
        }
        else
        {
            resolve(newPassword);
        }
    });

}

/**
 * Set user's new password 
 * 
 * @param {string} newPassword 
 * @param {string} resetPasswordToken 
 * @returns {object} Promise object (result, error)
 */
function setNewPassword(newPassword, resetPasswordToken)
{
    return new Promise((resolve, reject) => {
        ResetPasswordTokenModel.findOne({
                where: { reset_password_token: resetPasswordToken },
                attributes: ['user_email']
        }).then(
            result => {
                var header = { 
                    'Content-Type': 'application/json' 
                };
                var email = result.get('user_email');
                var data = JSON.stringify({
                    "email": email,
                    "resetToken": resetPasswordToken,
                    "newPassword": newPassword
                })
                var URL = baseURL + `/V1/customers/resetPassword`;
                axios.post(
                    URL,
                    data,
                    { headers: header }
                )
                .then(response => {
                    var result = {
                        email: email,
                        success: response.data
                    };
                    resolve(result)
                })
                .catch(error => {
                    console.log(error);
                    reject(error);
                });
        });
    });
}

function markRequestDone(entityId, reset_password_token)
{
    if(reset_password_token)
    {
        ResetPasswordTokenModel.update(
            {
                reset_password_token: reset_password_token,
                status: true
            },
            {
                where: { id: entityId }
            }
    
        );
    }
    else{
        ResetPasswordTokenModel.destroy({
            where: { id: entityId }
        });
    }
  
}

function RequestResetPasswordTokenForAllRequest()
{
    ResetPasswordTokenModel.findAll({
        where: {
            status: false
        },
        order: [
            ['id', 'ASC'],
        ],
    })
    .then(result=>{
        result.forEach(request => {
            //request.user_email
            var message = JSON.stringify({
                entity_id: request.id,
                user_email: request.user_email
            });
            rsmq.getResetPasswordTokenQueue.send(message);
        });
    })
}


module.exports = {
    getUserToken,
    getUserProfile,
    requestResetPasswordToken,
    getResetPasswordToken,
    checkResetPasswordTokenIsValid,
    validatePassword,
    setNewPassword,
    RequestResetPasswordTokenForAllRequest
};