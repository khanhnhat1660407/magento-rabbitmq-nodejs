const Router            = require("express-promise-router");
const router            = new Router();
const AccountManagement = require('../../middlewares/magento-account-management');

router.get("/", function(req, res) {
    if (req.session.user) {
        res.redirect("/");
    } else {
        res.render("account/forgot-password", {page:"forgot-password", error: null});
    }
});

router.post("/", async function(req, res){
    const {email} = req.body;
    console.log(email);
    AccountManagement.requestResetPasswordToken(email)
    .then(result => {
        res.render('result-page', {message: `Check your inbox for the next steps. If you don't receive an email, and it's not in your spam folder this could mean you signed up with a different address.`})
    })
    .catch(err => {
        res.render('account/forgot-password', {page:"forgot-password", error: err.message});
    })

});

module.exports = router;