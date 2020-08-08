const express    = require('express');
const session    = require("express-session");
const app        = express();
const bodyParser = require("body-parser");
const axios      = require('axios');
const config     = require('./config.json');
const Promise    = require('promise')
const redis      = require('./middlewares/redis');
const cronMan    = require('./cron-man/cron-man');
const Constant   = require('./constant');
const port       = process.env.PORT; // || config.server.DEFAULT_PORT; 
const RabbitMQService         = require('./middlewares/rabbitmq')
const OrderManagement         = require('./middlewares/order-management');
const ResetPasswordTokenModel = require('./models/reset_password_token');

var server = require("http").Server(app);
server.listen(port, function () {
    console.log(`SERVER LISTENING ON PORT: ${port} 😍`);
});
var socketIO     = require('socket.io');
const { fsync } = require('fs');
var io           = socketIO(server);

app.use(
    session({
        secret: process.env.SESSION_SECRET || config.redis.SESSION_SECRET,
        resave: false,
        saveUninitialized: true,
        store: new redis.store({
            host: redis.option.hostname,
            ttl: 86400,
            client: redis.client
        }),
    })
);

app.set("views", "./views");
app.set("view engine", "ejs");

app.use("/css", express.static(__dirname + "/css"));
app.use("/public", express.static(__dirname + "/public"));
app.use("/js", express.static(__dirname + "/js"));
app.use(bodyParser.urlencoded({ extended: false }));

app.use("/account", require("./routers/account"));
app.use("/admin", require("./routers/admin"));
app.use("/api", require("./api/api"));



app.get('/', async function (req, res) {
    var baseURL = config.magento.api_base_url;
    const necessary_content = [
        {
            key: "header_categories",
            api_url: baseURL + '/V1/categories',
            method: 'get',
            result: null,
        },
        {
            key: "block_promotion_3",
            api_url: baseURL + '/V1/promotion/products/3',
            method: 'get',
            result: null,
        }
    ];   

    // redis.client.expire('header_categories', 1);
    // redis.client.expire('block_promotion_3', 1);
    
    Promise.all(necessary_content.map(getData))
    .then(result =>{
        var homepage = {
            categories: result[0],    
            block_promotion: result[1],
        }
        // console.log(homepage.block_promotion);
        res.render("index",{page: 'index', data: homepage, user: req.session.profile});

    })
})

function getData(content)
{
    var token = config.magento.token;

    var header = { 
        Authorization: 'Bearer ' + token 
    };
    return new Promise((resolve, reject) => {
        redis.client.get(content.key,(err, result) => {
            if(result)
            {
                console.log(content.key +': get data from Redis');
                resolve(JSON.parse(result))
            }
            else{
                console.log(content.key +': get data by call Api');
                if(content.method == 'get'){
                    axios.get(content.api_url,{headers: header} )
                    .then(response => {
                        var data = JSON.stringify(response.data);
                        redis.client.set(content.key,data,redis.print);
                        redis.client.expire(content.key, 3600);
                        resolve(response.data);
                    })
                    .catch((error) => {
                        console.log(JSON.stringify(error));
                    })
                }
                //else method post 
            }
        })
    })
}

io.on('connection', (socket) => {
    console.log('[SOCKET IO] CONNECT OK!');
    //Sync Order 
    socket.on('let-sync-order', (msg) => {
        OrderManagement.SyncOrders(socket);
    });

    socket.on('generate-ten-order', (msg) => {
        OrderManagement.generateOrders(10, socket);
    });
    //Sync Order Result
});
