<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  session_write_close();

  if ('true' === STORE_PAGE_PARSE_TIME) {
    $timer_total = Logger::stop_timer();
    if ('true' === DISPLAY_PAGE_PARSE_TIME) {
      echo Logger::format($timer_total);
    }
  }
?>
