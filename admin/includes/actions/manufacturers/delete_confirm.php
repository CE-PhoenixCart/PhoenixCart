<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $manufacturers_id = Text::input($_GET['mID']);

  if (isset($_POST['delete_image']) && ($_POST['delete_image'] === 'on')) {
    $manufacturer = $db->query("SELECT manufacturers_image FROM manufacturers WHERE manufacturers_id = " . (int)$manufacturers_id)->fetch_assoc();

    $image_location = DIR_FS_CATALOG . 'images/' . $manufacturer['manufacturers_image'];
    if (file_exists($image_location)) {
      @unlink($image_location);
    }
  }

  $db->query("DELETE FROM manufacturers WHERE manufacturers_id = " . (int)$manufacturers_id);
  $db->query("DELETE FROM manufacturers_info WHERE manufacturers_id = " . (int)$manufacturers_id);

  if (isset($_POST['delete_products']) && ('on' === $_POST['delete_products'])) {
    $products_query = $db->query("SELECT products_id FROM products WHERE manufacturers_id = " . (int)$manufacturers_id);
    while ($products = $products_query->fetch_assoc()) {
      Products::remove($products['products_id']);
    }
  } else {
    $db->query("UPDATE products SET manufacturers_id = '' WHERE manufacturers_id = " . (int)$manufacturers_id);
  }

  return $link;
