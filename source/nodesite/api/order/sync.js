// const Router = require('express-promise-router');
// const router = Router();
// const OrderManagement = require('../../middlewares/order-mangement');
// const RabbitMQWorker  = require('../../middlewares/rabbitmq');

// router.post('/', async function(req,res){
//     OrderManagement.getAllOrderNotSyncYet()
//     .then(orders =>{
//         RabbitMQWorker.publishOrders(orders).then(result => {
//             res.status(200).send('Sync Order Successfuly')
//         });
//     })
//     .catch(err => {
//         res.status(500).send('Sync Order Failed')
//     });
// })

// router.get('/', async function(req,res){
//     res.status(405).send('Method Not Allowed');
// })


// module.exports = router;