<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $db->query("UPDATE products SET products_status = " . (int)$_GET['flag'] . ", products_last_modified = NOW() WHERE products_id = " . (int)$_GET['pID']);

  return $Admin->link('catalog.php', ['cPath' => $_GET['cPath'], 'pID' => (int)$_GET['pID']]);
