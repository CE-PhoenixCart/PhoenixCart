<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $converter = pathinfo(MODULE_ADMIN_CURRENCIES_INSTALLED, PATHINFO_FILENAME);
  call_user_func([$converter, 'execute']);

  return $Admin->link();
