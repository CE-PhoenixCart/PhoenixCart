<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Orders {

    public static function message_update() {
      if ($GLOBALS['order_updated']) {
        $GLOBALS['messageStack']->add_session(SUCCESS_ORDER_UPDATED, 'success');
      } else {
        $GLOBALS['messageStack']->add_session(WARNING_ORDER_NOT_UPDATED, 'warning');
      }
    }

  }
