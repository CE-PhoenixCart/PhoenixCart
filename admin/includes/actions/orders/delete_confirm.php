<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $oID = Text::input($_GET['oID']);

  order::remove($oID, $_POST['restock'] ?? false, $_POST['reactivate'] ?? false);

  return $Admin->link('orders.php')->retain_query_except(['oID', 'action']);
