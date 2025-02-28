<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class alertBlock {

  	public function __construct() {
  	}

    public function alertBlock($alerts, $alert_output = false) {
	  $alertBox_string = '';

      foreach ($alerts as $alert) {
        $alertBox_string .= '<div';

        if (isset($alert['params']) && !Text::is_empty($alert['params'])) {
          $alertBox_string .= ' ' . $alert['params'];
        }

        $alertBox_string .= '>' . PHP_EOL;
          $alertBox_string .= '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' . PHP_EOL;
          $alertBox_string .= $alert['text'];
        $alertBox_string .= '</div>' . PHP_EOL;
      }

      if ($alert_output) {
        echo $alertBox_string;
      }

      return $alertBox_string;
    }

  }
