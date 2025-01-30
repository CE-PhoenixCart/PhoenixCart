<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  $importers_id = Text::input($_GET['iID']);

  if (isset($_POST['delete_image']) && ($_POST['delete_image'] === 'on')) {
    $importer = $db->query("SELECT importers_image FROM importers WHERE importers_id = " . (int)$importers_id)->fetch_assoc();

    $image_location = DIR_FS_CATALOG . 'images/' . $importer['importers_image'];
    if (file_exists($image_location)) {
      @unlink($image_location);
    }
  }

  $db->query("DELETE FROM importers WHERE importers_id = " . (int)$importers_id);
  $db->query("DELETE FROM importers_info WHERE importers_id = " . (int)$importers_id);

  if (isset($_POST['delete_products']) && ('on' === $_POST['delete_products'])) {
    $products_query = $db->query("SELECT products_id FROM products WHERE importers_id = " . (int)$importers_id);
    while ($products = $products_query->fetch_assoc()) {
      Products::remove($products['products_id']);
    }
  } else {
    $db->query("UPDATE products SET importers_id = '' WHERE importers_id = " . (int)$importers_id);
  }

  return $link;
