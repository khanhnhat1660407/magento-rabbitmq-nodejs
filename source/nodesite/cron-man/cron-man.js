
const cron              = require("node-cron");
const AccountManagement = require('../middlewares/magento-account-management')
const rsmq              = require('../background-job/redis-message-queue');
const ResetPasswordTokenModel  = require('../models/reset_password_token');


// rsmq.getResetPasswordTokenQueue.on("ready", function() {
//     cron.schedule("* * * * *", function() {
//         AccountManagement.RequestResetPasswordTokenForAllRequest();
//     })
// })

// cron.schedule("* * * * * *", function() {
//     for(let i = 0 ; i < 100; i++){
//         ResetPasswordTokenModel.create({
//             user_email: `test${i}@gmail.com`,
//             reset_password_token: null,
//             status: false
//         })
//     }
// })


module.exports = cron;
