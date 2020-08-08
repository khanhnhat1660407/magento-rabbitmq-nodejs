const config                   = require('../config.json')
const baseURL                  = config.magento.api_base_url;
const axios                    = require('axios');
const Promise                  = require('promise')
const ResetPasswordTokenModel  = require('../models/reset_password_token');
const mailServer               = require('../middlewares/mail-server');


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

function markRequestFailed(token)
{
    ResetPasswordTokenModel.update(
        {
            status: false
        },
        {
            where: { reset_password_token: token }
        }

    );
}

module.exports = {
    getResetPasswordToken,
    markRequestDone,
    markRequestFailed
}