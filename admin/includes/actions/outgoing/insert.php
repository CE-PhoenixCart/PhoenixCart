<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $slug        = Text::input($_POST['slug']);
  $customer_id = (int)$_POST['customer_id'];
  $send_at     = Text::input($_POST['send_at']);

  include_once(DIR_FS_CATALOG . 'includes/modules/outgoing/' . $slug . '.php');
  call_user_func_array(['Outgoing_' . $slug, 'admin_add'], [$customer_id, $send_at]);
  
  return $link;
  