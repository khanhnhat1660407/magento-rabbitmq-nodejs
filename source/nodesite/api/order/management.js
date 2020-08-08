const Router = require('express-promise-router');
const router = Router();
const OrderManagement = require('../../middlewares/order-management');
const RabbitMQWorker  = require('../../middlewares/rabbitmq');

router.get('/get-all-order', async function(req,res){
    var draw = req.query.draw !== 'undefined' ? req.query.draw : 1;
    var limit = req.query.length !== 'undefined' ? req.query.length : 10;
    var start = req.query.start !== 'undefined' ? req.query.start : 0;
    var totalOrder = 0;
    
    OrderManagement.getAllOrder(limit, start)
    .then(results =>{
        OrderManagement.countOrders().then(total => {
            res.status(200).send(JSON.stringify({
                'draw': draw,
                'recordsTotal': total,
                'recordsFiltered': results.count,
                'data': results.rows
            }));
        });
    })
    .catch(err => {
        res.status(400).send(JSON.stringify({
            'draw': draw,
            'recordsTotal': 0,
            'recordsFiltered': 0,
            'data': []
        }))
    });
})

router.get('/', async function(req,res){
    res.status(404).send('Page not found!');
})

module.exports = router;