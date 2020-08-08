const Router = require("express-promise-router");
const router = new Router();

router.get("/", function(req, res) {
    if (req.session.user) {
        req.session.destroy();
    }
    res.redirect("/");
});


module.exports = router;
    