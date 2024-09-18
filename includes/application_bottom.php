<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

// close session (store variables)
  session_write_close();

  if ('true' === STORE_PAGE_PARSE_TIME) {
    $parse_time = number_format((microtime(true) - PAGE_PARSE_START_TIME), 3);
    error_log(date('Y-m-d H:i:s') . ' - ' . getenv('REQUEST_URI') . ' (' . $parse_time . "s)\n", 3, STORE_PAGE_PARSE_TIME_LOG);

    if ('true' === DISPLAY_PAGE_PARSE_TIME) {
      echo '<small class="font-monospace text-muted text-body-secondary">Parse Time: ' . $parse_time . 's</small>';
    }
  }
?>
