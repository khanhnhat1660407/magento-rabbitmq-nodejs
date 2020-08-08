const amqp            = require('amqplib/callback_api');
const RABBITMQ_URL    = process.env.RABBITMQ_URL;
const config          = require('./config');
const Constant        = require('../constant');

// const OrderManagement = require('./order-management');

const SalesOrder      = require('../models/sales_order');
var isDurable         = true;
var rbmqChannel       = null;
var rbmqConnection    = null;

var exchangeCreateOrderName        = 'create.order.from.nodejs.exchange';
var exchangeResultCreateOrderName  = 'create.order.from.nodejs.exchange';
var routingCreateOrderKey          = 'create.order.rabbit.mq';
var queueResultCreateOrder         = 'result.create.order.from.magento';

amqp.connect(RABBITMQ_URL,(error0, connection) => {
    rbmqConnection = connection;
    rbmqConnection.createConfirmChannel((error1, confirmChannel) => {
        rbmqChannel = confirmChannel;
        // rbmqChannel.assertExchange(exchangeResultCreateOrderName, 'topic', {
        //     durable: isDurable
        // });
    }); 
});

/**
 * 
 * @param {Array} orders 
 * @param {*} socket 
 */
function publishOrders(orders, socket)
{
    rbmqChannel.assertExchange(exchangeCreateOrderName, 'topic', {
        durable: isDurable
    });
    var i = 0;
    if (orders.length > 0) {
        orders.forEach(orderData => {
            try {
                rbmqChannel.publish(
                    exchangeCreateOrderName, 
                    routingCreateOrderKey, 
                    Buffer.from(JSON.stringify(orderData)), 
                    { 
                        contentType     : "application/json",
                        contentEncoding : "gzip",
                        messageId       : orderData.order_id
                    },
                );
                rbmqChannel.waitForConfirms( function(err, ok) {
                    if (err) {
                        console.log(`[AMQP][ERROR] publish ${orderData.order_id}: `, err);
                    } 
                    updateOrderSynced(orderData.order_id);
                    console.log(" [x] Sent %s:'%s'", routingCreateOrderKey, orderData.order_id);
                });
                i++;
                if(i+1 > orders.length){
                    socket.emit('sync-order-done', {result: true, message: 'Success'});
                }
            } catch (e) {
                socket.emit('sync-order-done', {result: false, message: 'middlewares/rabbitmq::PushlishOrders'});
            }
        });
    } else {
        socket.emit('sync-order-done', {result: true, message: 'Success'});
    }
    listenSyncOrderResult(socket)
}

/**
 * 
 * @param {*} socket 
 */
function listenSyncOrderResult(socket)
{
    rbmqChannel.assertQueue(queueResultCreateOrder, {
        exclusive: false // Doc quyen -> set true neu chi muon site nay nhan result tu queue nay 
      }, function(error2, resultQueue) {
        if (error2) {
            console.log(error2);
        }
        console.log(" [*] Waiting for messages in %s", resultQueue.queue);
        rbmqChannel.bindQueue(resultQueue.queue, exchangeResultCreateOrderName, 'create.order.result');
  
        rbmqChannel.consume(resultQueue.queue, function(msg) {
            if (msg.content) { 
                syncResult = JSON.parse(msg.content.toString('utf-8'));
                console.log(syncResult);
                orderInfo = syncResult.message.split("|");
                if (!syncResult.error) {
                    updateSyncResult(orderInfo[0], orderInfo[1], orderInfo[2])
                } else {
                    updateSyncResult(orderInfo[0], orderInfo[1], 'Pending')
                }
                socket.emit('sync-order-result',  syncResult);
            }
        }, {
          noAck: true // receiver message confirm
        });
    });
}

async function updateSyncResult(orderId, result, status){
    return await SalesOrder.update(
        {
            order_status : status ? status : 'Pending',
            sync_result  : result
        }, 
        {
            where: { order_id: orderId }
        }
    );
}

async function updateOrderSynced(orderId){
    return await SalesOrder.update(
        {
            is_synced : true,
        }, 
        {
            where: { order_id: orderId }
        }
    );
}

process.on('exit', (code) => {
    rbmqConnection.close();
    console.log('RBMQ: Connection closed');
})

module.exports = {
    publishOrders,
    listenSyncOrderResult
}