<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cfgm_currencies {

    const CODE = 'currencies';
    const DIRECTORY = DIR_FS_ADMIN . 'includes/modules/currencies/';
    const LANGUAGE_DIRECTORY = DIR_FS_ADMIN . 'includes/languages/';
    const KEY = 'MODULE_ADMIN_CURRENCIES_INSTALLED';
    const TITLE = MODULE_CFG_MODULE_CURRENCIES_TITLE;
    const TEMPLATE_INTEGRATION = false;
    
    const GET_HELP_LINK = 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Currencies';
    const GET_ADDONS_LINKS = [ADDONS_FREE => 'https://phoenixcart.org/forum/app.php/addons/free/other-29',
                              ADDONS_COMMERCIAL => 'https://phoenixcart.org/forum/app.php/addons/commercial/other-36',
                              ADDONS_PRO => 'https://phoenixcart.org/forum/app.php/addons/supporters/other-45',];

  }
