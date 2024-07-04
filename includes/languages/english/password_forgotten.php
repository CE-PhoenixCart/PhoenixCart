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

const HEADING_TITLE = 'I\'ve Forgotten My Password!';

const TEXT_MAIN = 'If you\'ve forgotten your password, enter your e-mail address below and we\'ll send you instructions on how to securely change your password.';

const TEXT_PASSWORD_RESET_INITIATED = 'Please check your e-mail for instructions on how to change your password. The instructions contain a link that is valid only for 24 hours or until your password has been updated.';

const TEXT_NO_EMAIL_ADDRESS_FOUND = 'Error: The E-mail Address was not found in our records, please try again.';

const EMAIL_PASSWORD_RESET_SUBJECT = STORE_NAME . ' - New Password';
const EMAIL_PASSWORD_RESET_BODY = 'A new password has been requested for your profile at ' . STORE_NAME . '.' . "\n\n" . 'Please follow this personal link to securely change your password:' . "\n\n%s\n\n" . 'This link will be automatically discarded after 24 hours or after your password has been changed.' . "\n\n" . 'For help with any of our online services, please e-mail the store-owner: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n";

const ERROR_ACTION_RECORDER = 'Error: A password reset link has already been sent. Please try again in %s minutes.';

const IMAGE_BUTTON_RESET_PASSWORD = 'Reset my Password';
