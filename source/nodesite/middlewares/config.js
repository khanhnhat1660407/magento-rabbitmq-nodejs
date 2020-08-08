const config = require('../config.json');

function getServerConfig()
{
    return config.server;
}

function getRedisConfig()
{
    return config.redis;
}

function getMagentoConfig()
{
    return config.magento;
}

function getMailServiceConfig()
{
    return config.mail;
}

function getRabbitMQConfig()
{
    return config.rabbitmq;
}

function getExchangeConfig(exchange)
{
    return config.rabbitmq.exchanges[exchange];
}

function getQueueConfig(queue)
{
    return config.rabbitmq.queues[queue];
}

module.exports = {
    getServerConfig,
    getRedisConfig,
    getMagentoConfig,
    getMailServiceConfig,
    getRabbitMQConfig,
    getExchangeConfig,
    getQueueConfig
}
