<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Actions {

    public static function parse($action) {
      $action = basename($action);

      if ( $action && class_exists($class = "\\Phoenix\\Actions\\$action") ) {
        call_user_func([$class, 'execute']);
      }
    }

  }
