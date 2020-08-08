 
const Router = require('express-promise-router')
const router = new Router();

router.get("/", function(req, res) {
    if (req.session.user) {
        //Redirect to my account 
        res.redirect("/");
    } else {
        res.redirect("/account/login");
    }
});
router.use("/login", require("./account/login"));
router.use("/forgot-password", require("./account/forgot-password"));
router.use("/new-password", require("./account/new-password"));
router.use("/logout", require("./account/logout"));
module.exports = router;