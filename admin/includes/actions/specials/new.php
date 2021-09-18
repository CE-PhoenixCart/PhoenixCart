<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $discountables = Products::list_discountable();
  if (!$discountables) {
    $messageStack->add_session(WARNING_NO_PRODUCTS, 'warning');
    Href::redirect($Admin->link('catalog.php'));
  }
