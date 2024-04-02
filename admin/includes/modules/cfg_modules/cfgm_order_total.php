<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cfgm_order_total {

    const CODE = 'order_total';
    const DIRECTORY = DIR_FS_CATALOG . 'includes/modules/order_total/';
    const LANGUAGE_DIRECTORY = DIR_FS_CATALOG . 'includes/languages/';
    const KEY = 'MODULE_ORDER_TOTAL_INSTALLED';
    const TITLE = MODULE_CFG_MODULE_ORDER_TOTAL_TITLE;
    const TEMPLATE_INTEGRATION = false;
    
    const GET_HELP_LINK = 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Order_Total';
    const GET_ADDONS_LINKS = [ADDONS_FREE => 'https://phoenixcart.org/forum/app.php/addons/free/orderTotal-22',
                              ADDONS_COMMERCIAL => 'https://phoenixcart.org/forum/app.php/addons/commercial/orderTotal-34',
                              ADDONS_PRO => 'https://phoenixcart.org/forum/app.php/addons/supporters/other-45',];

  }
