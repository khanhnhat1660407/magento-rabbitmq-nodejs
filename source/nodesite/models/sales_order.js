const Sequelize = require("sequelize");
const Model     = Sequelize.Model;
const db        = require("./database");

class SalesOrder extends Model {} 
SalesOrder.init({
    order_id: {
        type: Sequelize.STRING,
        allowNull: false,
        unique: true
    },

    currency_id: {
        type: Sequelize.STRING,
        allowNull: false,
        unique: false
    },

    email: {
        type: Sequelize.STRING,
        allowNull: true,
        unique: false
    },

    items: {
        type: Sequelize.JSON,
        allowNull: false,
    },

    shipping_address: {
        type: Sequelize.JSON,
        allowNull: false,
    },

    order_status: {
        type: Sequelize.STRING,
        allowNull: false,
        defaultValue: 'Pending'
    },

    is_synced: {
        type: Sequelize.BOOLEAN,
        allowNull: false,
        defaultValue: false
    },

    sync_result: {
        type: Sequelize.STRING,
        allowNull: true,
    }
},{
    sequelize: db,
    modelName: 'sales_order',
    timestamps: true
});

db.sync();

module.exports = SalesOrder;