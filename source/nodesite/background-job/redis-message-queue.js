const redis             = require('../middlewares/redis');
const RedisSMQ          = require("rsmq");
const RSMQWorker        = require( "rsmq-worker" );
const management        = require('./fuctions')
const mailServer        = require('../middlewares/mail-server');

const rsmq = new RedisSMQ({
    host: redis.option.hostname,
    port: redis.option.port,
    ns: "rsmq"
});

const options = {
    rsmq       : rsmq,
    autostart  : true,
    timeout    : 5000,
    maxReceiveCount: 1000
}

const mailQueue = new RSMQWorker( "mail_queue", options);
const getResetPasswordTokenQueue = new RSMQWorker( "get_reset_password_token_queue", options);

catchLog(getResetPasswordTokenQueue);
catchLog(mailQueue);


getResetPasswordTokenQueue.on( "message", function( msg, next, id ){
    //call API get token
    //getResetPasswordToken
    var message    = JSON.parse(msg)
    var user_email = message.user_email;
    var entity_id  = message.entity_id;   
    management.getResetPasswordToken(user_email)
    .then(resetInfo => {
        //TODO: catch error send
        mailQueue.send(JSON.stringify(resetInfo))
        management.markRequestDone(entity_id, resetInfo.reset_password_token)
        getResetPasswordTokenQueue.del(id);
        next();
    })
    .catch(err => {
        console.log(`RSPTQ: err - ${user_email}`);
        // console.log(err);
        management.markRequestDone(entity_id, null)
        getResetPasswordTokenQueue.del(id);
        next();
    })
});


mailQueue.on( "message", function( msg, next, id ){
    //Send emai reset password 
    var resetInfo = JSON.parse(msg);
    mailServer.sendResetPasswordEmail(resetInfo)
    .then(() =>{
        mailQueue.del(id);
        next();
    })
    .catch(err =>{
        management.markRequestFailed(resetInfo.reset_password_token);
        mailQueue.del(id);
        next();    
    });
});

function catchLog(worker){
    worker.on('error', function( err, msg ){
        console.log( "ERROR", err, msg.id );
    });
    worker.on('exceeded', function( msg ){
        console.log( "EXCEEDED", msg.id );
    });
    worker.on('timeout', function( msg ){
        console.log( "TIMEOUT", msg.message, msg.rc );
    });
}
   
module.exports = {
    getResetPasswordTokenQueue,
    mailQueue
};