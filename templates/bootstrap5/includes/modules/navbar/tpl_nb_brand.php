<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

$display = (!Text::is_empty(MODULE_NAVBAR_BRAND_PUBLIC_TEXT)) ? MODULE_NAVBAR_BRAND_PUBLIC_TEXT : (new Image('images/' . MINI_LOGO, [], htmlspecialchars(STORE_NAME)))->set_responsive(false);

echo '<a class="navbar-brand nb-brand" href="' . $GLOBALS['Linker']->build('index.php') . '">' . $display . '</a>';
