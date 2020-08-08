const url       = require("url");
const session   = require("express-session");
const redis     = require('redis');
const REDIS_URL = process.env.REDIS_URL;
const option    = url.parse(REDIS_URL);
const client    = redis.createClient(option.port,option.hostname);
const store     = require("connect-redis")(session);

client.on('error', (err) => {
    console.log('Redis error: ', err);
});

module.exports = {
    redis,
    option,
    client,
    store
}