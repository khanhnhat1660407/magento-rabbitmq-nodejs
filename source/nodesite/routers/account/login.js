const Router            = require("express-promise-router");
const router            = new Router();
const AccountManagement = require('../../middlewares/magento-account-management')

router.get("/", function(req, res) {
    if (req.session.user) {
        res.redirect("/");
    } else {
        res.render("account/login", {page:"login", error: 0, msg: null});
    }
});
router.post("/", async function(req, res){
    const user = req.body;
    AccountManagement.getUserToken(req, res, user)
    .then(AccountManagement.getUserProfile)
    .then(userProfile => {
        req.session.user = userProfile;
        res.redirect("/");
    })
    .catch(err => console.error(err))
    
});

module.exports = router;
    