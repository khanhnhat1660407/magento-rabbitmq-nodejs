 
const Router = require('express-promise-router')
const router = new Router();
const OrderManagement = require('../middlewares/order-management');
router.get("/", function(req, res) {
    res.render("admin/admin-index", {pageTitle:"Dashboard", pageCode: 'dashboard', err: false});
});

router.get("/sales-order", function(req, res) {
    res.render("admin/sales-order/index", {pageTitle:"Sales Order", pageCode: 'sales-order'});
});

module.exports = router;