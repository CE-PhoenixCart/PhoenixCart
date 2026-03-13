<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

const NAVBAR_TITLE_1 = 'Sign In';
const NAVBAR_TITLE_2 = 'Password Forgotten';

const HEADING_TITLE = 'Forgot Password';

const TEXT_MAIN = 'Enter your email address below. We\'ll send you a link to reset your password.';

const TEXT_PASSWORD_RESET_INITIATED = 'Check your email for a password reset link. The link expires in 24 hours.';

const TEXT_NO_EMAIL_ADDRESS_FOUND = 'If this email is in our records, we\'ve sent you a reset link. Please check your inbox.';

const EMAIL_PASSWORD_RESET_SUBJECT = STORE_NAME . ' - Password Reset';
const EMAIL_PASSWORD_RESET_BODY = 'We received a request to reset your password for your account at ' . STORE_NAME . '.' . "\n\n" . 'Click the link below to choose a new password:' . "\n\n%s\n\n" . 'This link expires in 24 hours.' . "\n\n" . 'Need help? Contact us at: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n";

const ERROR_ACTION_RECORDER = 'Error: A password reset link has already been sent. Please try again in %s minutes.';

const IMAGE_BUTTON_RESET_PASSWORD = 'Reset my Password';
