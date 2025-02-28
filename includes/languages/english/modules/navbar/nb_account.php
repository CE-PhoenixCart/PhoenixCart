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
  <i title="My Profile" class="far fa-user fa-fw fa-xl"></i><span class="d-inline d-sm-none"> My Profile</span>
LI;
  
  const MODULE_NAVBAR_ACCOUNT_LOGGED_IN = <<<'LI'
  <span class="position-relative">
    <i title="My Profile" class="fas fa-user fa-fw fa-xl"></i>
    <span class="d-none d-sm-inline position-absolute top-0 start-100 translate-middle badge">
      <i class="fas fa-check fa-2xl text-info"></i>
    </span>
  </span>
  <span class="d-inline d-sm-none">%s, you are logged in</span>
LI;
  
  const MODULE_NAVBAR_ACCOUNT_LOGIN = '<i class="fas fa-sign-in-alt fa-fw fa-xl"></i> Log In';
  const MODULE_NAVBAR_ACCOUNT_LOGOFF = '<i class="fas fa-sign-out-alt fa-fw fa-xl"></i> Log Off';
  const MODULE_NAVBAR_ACCOUNT = 'My Profile';
  const MODULE_NAVBAR_ACCOUNT_HISTORY = 'My Orders';
  const MODULE_NAVBAR_ACCOUNT_EDIT = 'My Details';
  const MODULE_NAVBAR_ACCOUNT_ADDRESS_BOOK = 'My Address Book';
  const MODULE_NAVBAR_ACCOUNT_PASSWORD = 'My Password';
  const MODULE_NAVBAR_ACCOUNT_REGISTER = '<i class="fas fa-pencil-alt fa-fw fa-xl"></i> Register';
