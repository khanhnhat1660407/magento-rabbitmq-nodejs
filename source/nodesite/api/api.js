const Router = require('express-promise-router')
const router = new Router();

// router.use("/sync-order", require("./order/sync"));
router.use("/order-management", require("./order/management"));
router.get("/", function(req,res){
    res.status(404).send('Page Not found');
});

module.exports = router;