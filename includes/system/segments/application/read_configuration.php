<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

// set the application parameters
  array_walk(...[
    $db->fetch_all('SELECT configuration_key, configuration_value FROM configuration'),
    function ($v) {
      define($v['configuration_key'], $v['configuration_value']);
    }]);
