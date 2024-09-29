<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class Logger {

    public static function stop_timer() {
      $timer_total = number_format(microtime(true) - PAGE_PARSE_START_TIME, 3);

      static::write(getenv('REQUEST_URI'), $timer_total . 's');

      return $timer_total;
    }

    public static function format($timer_total) {
      return '<small class="font-monospace text-muted text-body-secondary">Parse Time: ' . $timer_total . 's</small>';
    }

    public static function write($uri, $message) {
      error_log(Text::input(date('Y-m-d H:i:s')) . " [$message] $uri\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

  }
