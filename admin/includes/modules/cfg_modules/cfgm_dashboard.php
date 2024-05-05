<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cfgm_dashboard {

    const CODE = 'dashboard';
    const DIRECTORY = DIR_FS_ADMIN . 'includes/modules/dashboard/';
    const LANGUAGE_DIRECTORY = DIR_FS_ADMIN . 'includes/languages/';
    const KEY = 'MODULE_ADMIN_DASHBOARD_INSTALLED';
    const TITLE = MODULE_CFG_MODULE_DASHBOARD_TITLE;
    const TEMPLATE_INTEGRATION = false;
    
    const GET_HELP_LINK = 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Dashboard';
    const GET_ADDONS_LINKS = [ADDONS_FREE => 'https://phoenixcart.org/forum/app.php/addons/free/other-29',
                              ADDONS_COMMERCIAL => 'https://phoenixcart.org/forum/app.php/addons/commercial/other-36',
                              ADDONS_PRO => 'https://phoenixcart.org/forum/app.php/addons/supporters/other-45',];

  }
