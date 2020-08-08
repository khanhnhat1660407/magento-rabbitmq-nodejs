const SalesOrder      = require('../models/sales_order');
const RabbitMQWorker  = require('./rabbitmq');
const Constant        = require('../constant');

/**
 * Create order
 * @param {object} orderData 
 */
function createOrder(orderData) 
{
    SalesOrder.create(orderData)
    .then(function (orderCreated) {
        console.log('[CREATE ORDER][SUCCESS]: ' + orderCreated.order_id);
    }).catch(function (err) {
        console.log('[CREATE ORDER][ERROR]: '  +  err)
    })
}

/**
 * Create MultibleOrder
 * @param {Array} orders 
 */
function createMultibleOrder(orders, socket)
{
    SalesOrder.bulkCreate(orders, {
        returning: false
    }).then(function (orders){
        orders.forEach(function(order) {
            console.log('[CREATE ORDER][SUCCESS]: '  +  order.order_id)
        });
        socket.emit('generate-ten-order-done', true)
    }).catch(function(err){
        console.log('[CREATE ORDER][ERROR]: '  +  err)
        socket.emit('generate-ten-order-done', false)
    })
}
/**
 * Generate order data
 * @param {number} noOrder 
 */
function generateOrders(noOrder = 1, socket)
{
    //0-16
    var firtNameArr = ["Chau", "Nguyen", "Tran", "Le", "Pham", "Huynh", "Hoang", "Phan", "Vu", "Vo", "Dang", "Bui", "Do", "Ho", "Ngo", "Duong"];
    //0-15
    var lastNameArr = ["Nhat", "My", "Quy", "Tu", "Khanh", "Y", "Dong", "Hai", "Truc", "Tai", "Linh", "Thu", "Dung", "Vu", "Duong", "Ly"];
    
    var orders = [];
    for ( let i = 0; i < noOrder; i++) {
        let firstName =  firtNameArr[Math.floor(Math.random() * 16)];
        let lastName =  lastNameArr[Math.floor(Math.random() * 16)];
        let order = {
            order_id    : 'NODE_' + Math.random().toString(36).substring(5).toUpperCase(),
            currency_id : 'USD',
            email       : `${firstName.toLowerCase()}_${lastName.toLowerCase()}@gmail.com`,
            shipping_address : {
                firstname  : firstName,
                lastname   : lastName,
                street     : '60A Hoang Van Thu',
                region     : 'Quan Phu Nhuan',
                city       : 'Ho Chi Minh',
                country_id : 'VN',
                postcode   : '7000',
                telephone  : '0123456789',
                fax : 123456
            },
            items: [
                {
                    product_id : parseInt(Math.floor((Math.random() * 19) + 1)),
                    qty        : 1
                }
            ],
        };
        orders.push(order);
    }
    if(orders.length){
        createMultibleOrder(orders, socket);
    }
}


/**
 * Get all order not sync yet
 */
async function getAllOrderNotSyncYet() 
{
    var columns = [
        'order_id',
        'currency_id',
        'email',
        'items',
        'shipping_address',
    ]
    return await SalesOrder.findAll({ 
        attributes : columns,
        where      : { 'is_synced' : false },
        order      : [['id', 'ASC']],
        raw        : true,
    })
}

async function getAllOrder(limit, offset) 
{
    var columns = [
        'id',
        'order_id',
        'currency_id',
        'email',
        'shipping_address',
        'order_status',
        'is_synced',
        'sync_result',
        'createdAt',
    ]
    return await SalesOrder.findAndCountAll({ 
        attributes : columns,
        order      : [['id', 'DESC']],
        limit      : limit,
        offset     : offset,
        raw        : true,
    })
}

/**
 * Get order by Id
 * @param {*} id 
 */
async function getOrderById(id) 
{
    var columns = [
        'order_id',
        'currency_id',
        'email',
        'shipping_address',
        'items'
    ]
    return await SalesOrder.findOne({ 
        attributes : columns,
        where      : {'id': id}
    })
}

function SyncOrders(socket){
    getAllOrderNotSyncYet()
    .then(orders =>{
        RabbitMQWorker.publishOrders(orders, socket)
    })
    .catch(err => {
        socket.emit(Constant.SYNC_ORDER_RESULT, 'middlewares/order-management::SyncOrders' + err);
    });
}

async function countOrders()
{
    return await SalesOrder.count();
}



module.exports = {
    createOrder,
    generateOrders,
    getAllOrderNotSyncYet,
    getAllOrder,
    countOrders,
    getOrderById,
    SyncOrders,
}

