const nodemailer = require('nodemailer');
const config     = require('../config.json');

async function sendEmail(to, subject, text, html,attachments) {
    const transporter = nodemailer.createTransport({
        host: 'smtp.gmail.com',
        port: 587,
        secure: false,
        auth: {
        user: process.env.EMAIL_USER|| config.mail.user,
        pass: process.env.EMAIL_PASSWORD || config.mail.password,
        },
    });

    const info = await transporter.sendMail({
        from: `"Node Store" <${process.env.EMAIL_USER}>`, 
        to, 
        subject,
        text, 
        html,
        attachments
    });
  return info; //promise object
}

function sendResetPasswordEmail(resetPasswordInfo)
{
    var userId = resetPasswordInfo.user_id;
    var resetPasswordToken = resetPasswordInfo.reset_password_token;
    var email = resetPasswordInfo.user_email;
    var subject = 'Reset password request';
    var html = `<p>Hi <strong>${resetPasswordInfo.user_name}<strong></p>
              <p>Someone has requested a password reset for your Store account. Follow the link below to set a new password:</p>
              <a href="${config.server.host}/account/new-password/${userId}/${resetPasswordToken}">
                  Reset password
              </a>`;
    
    var info = sendEmail(email, subject, ' ', html);
    return info;
}

module.exports = {
  sendEmail,
  sendResetPasswordEmail
};