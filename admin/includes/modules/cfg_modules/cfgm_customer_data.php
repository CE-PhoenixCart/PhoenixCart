<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cfgm_customer_data {

    const CODE = 'customer_data';
    const DIRECTORY = DIR_FS_CATALOG . 'includes/modules/customer_data/';
    const LANGUAGE_DIRECTORY = DIR_FS_CATALOG . 'includes/languages/';
    const KEY = 'MODULE_CUSTOMER_DATA_INSTALLED';
    const TITLE = MODULE_CFG_MODULE_CUSTOMER_DATA_TITLE;
    const TEMPLATE_INTEGRATION = false;
    
    const GET_HELP_LINK = 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Customer_Data';
    const GET_ADDONS_LINKS = [ADDONS_FREE => 'https://phoenixcart.org/forum/app.php/addons/free/other-29',
                              ADDONS_COMMERCIAL => 'https://phoenixcart.org/forum/app.php/addons/commercial/other-36',
                              ADDONS_PRO => 'https://phoenixcart.org/forum/app.php/addons/supporters/other-45',];

  }
