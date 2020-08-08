const Router            = require("express-promise-router");
const router            = new Router();
const AccountManagement = require('../../middlewares/magento-account-management')

router.get("/", function(req, res) {
    if (req.session.user) {
        //redirect to change password
        res.redirect("/");
    } else {
        res.render("/account/login");
    }
});

router.get("/:userId/:resetPasswordToken", function(req, res){
    var userId = req.params.userId;
    var resetPasswordToken = req.params.resetPasswordToken;
    //Chua luu redis vi chua lam token het han 
    AccountManagement.checkResetPasswordTokenIsValid(userId, resetPasswordToken)
    .then(
        result =>{
            if(result)
            {
                res.render('account/new-password', {
                    page: "new-password", 
                    userId: userId, 
                    resetPasswordToken: resetPasswordToken, 
                    errorMessage: null
                });
            }
        },
        error =>{
            console.log(error);
            res.redirect('/');
        }
    )
});


router.post("/:userId/:resetPasswordToken", function(req, res){
    var userId = req.params.userId;
    var resetPasswordToken = req.params.resetPasswordToken;
    
    var newPassword = req.body.newPassword;
    var confirmPassword = req.body.confirmPassword;
    AccountManagement.validatePassword(newPassword, confirmPassword)
    .then(
        password =>{
            AccountManagement.setNewPassword(password, resetPasswordToken)
            .then(
                result => {
                    if(result.success){
                        AccountManagement.getUserToken(req, res, {email:result.email, password: password})
                        .then(AccountManagement.getUserProfile)
                        .then(userProfile => {
                            req.session.user = userProfile;
                            res.redirect("/");
                        })
                        .catch(err => console.error(err))
                    }
                },
                error =>{
                    //redirect something wrong
                    console.log(error)
                }
            )
        }
       
    )
    .catch( 
        error => {
            res.render('account/new-password', {
                page: "new-password", 
                userId: userId, 
                resetPasswordToken: resetPasswordToken, 
                errorMessage: error
            });
    })
});

module.exports = router;