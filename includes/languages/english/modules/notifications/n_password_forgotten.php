<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2026 Phoenix Cart

  Released under the GNU General Public License
*/

const MODULE_NOTIFICATIONS_PASSWORD_FORGOTTEN_TITLE = 'Password Forgotten Notification';
const MODULE_NOTIFICATIONS_PASSWORD_FORGOTTEN_DESCRIPTION = 'Sends an email with the password reset link.';

const MODULE_NOTIFICATIONS_PASSWORD_FORGOTTEN_TEXT_SUBJECT = STORE_NAME . ' - Password Reset';
const MODULE_NOTIFICATIONS_PASSWORD_FORGOTTEN_TEXT_BODY = 'We received a request to reset your password for your account at ' . STORE_NAME . '.' . "\n\n" . 'Click the link below to choose a new password:' . "\n\n%s\n\n" . 'This link expires in 24 hours.' . "\n\n" . 'Need help? Contact us at: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n";
