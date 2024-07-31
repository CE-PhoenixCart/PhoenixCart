<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

class hook_admin_invoice_print_name {

  public function listen_injectBodyEnd() {
    $print_title = sprintf(PRINT_TITLE, Text::input($_GET['oID']));

    $helper = <<<print
<script>var original_title = document.title; window.addEventListener("beforeprint", (event) => { document.title = '{$print_title}'; }); window.addEventListener("afterprint", (event) => { document.title = original_title; });</script>
print;

    return $helper;
  }

}
