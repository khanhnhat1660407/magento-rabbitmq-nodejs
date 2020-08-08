const Sequelize = require("sequelize");
const Model     = Sequelize.Model;
const db        = require("./database");

class ResetPasswordTokenModel extends Model {} 
ResetPasswordTokenModel.init({
    user_email: {
        type: Sequelize.STRING,
        allowNull: false,
        unique: false
    },

    reset_password_token: {
        type: Sequelize.STRING,
        allowNull: true,
        unique: true
    },

    status: {
        type: Sequelize.BOOLEAN,
        allowNull: false,
    }

},{
    sequelize: db,
    modelName: 'reset_password_token',
    timestamps: true
});

db.sync();

module.exports = ResetPasswordTokenModel;