<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  const MODULE_NAVBAR_ACCOUNT_TITLE = 'Account';
  const MODULE_NAVBAR_ACCOUNT_DESCRIPTION = 'Show Customer Account Actions in Navbar.';

  const MODULE_NAVBAR_ACCOUNT_LOGGED_OUT = <<<'LI'
  <i title="Account" class="far fa-user fa-fw fa-xl me-2"></i>Account
LI;
  
  const MODULE_NAVBAR_ACCOUNT_LOGGED_IN = <<<'LI'
  <span class="position-relative">
    <i title="Account" class="fas fa-user fa-fw fa-xl"></i>
    <span class="d-none d-sm-inline position-absolute top-0 start-100 translate-middle badge">
      <i class="fas fa-check fa-2xl text-info"></i>
    </span>
  </span>
  <span class="d-inline d-sm-none">%s, you are signed in</span>
LI;
  
  const MODULE_NAVBAR_ACCOUNT_LOGIN = 'Sign In';
  const MODULE_NAVBAR_ACCOUNT_LOGOFF = '%s, Sign Out';
  const MODULE_NAVBAR_ACCOUNT = 'Settings';
  const MODULE_NAVBAR_ACCOUNT_HISTORY = 'Orders';
  const MODULE_NAVBAR_ACCOUNT_EDIT = 'Details';
  const MODULE_NAVBAR_ACCOUNT_ADDRESS_BOOK = 'Addresses';
  const MODULE_NAVBAR_ACCOUNT_PASSWORD = 'Password';
  const MODULE_NAVBAR_ACCOUNT_REGISTER = 'Register';
