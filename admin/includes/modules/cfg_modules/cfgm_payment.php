<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cfgm_payment {

    const CODE = 'payment';
    const DIRECTORY = DIR_FS_CATALOG . 'includes/modules/payment/';
    const LANGUAGE_DIRECTORY = DIR_FS_CATALOG . 'includes/languages/';
    const KEY = 'MODULE_PAYMENT_INSTALLED';
    const TITLE = MODULE_CFG_MODULE_PAYMENT_TITLE;
    const TEMPLATE_INTEGRATION = false;
    
    const GET_HELP_LINK = 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Payment';
    const GET_ADDONS_LINKS = [ADDONS_FREE => 'https://phoenixcart.org/forum/app.php/addons/free/payment-21',
                              ADDONS_COMMERCIAL => 'https://phoenixcart.org/forum/app.php/addons/commercial/payment-35',];

  }
