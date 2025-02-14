<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($_GET['pID'])) {
    $products_id = Text::input($_GET['pID']);
  }
  $products_date_available = Text::input($_POST['products_date_available']);

  $sql_data = [
    'products_quantity' => (int)Text::input($_POST['products_quantity']),
    'products_model' => Text::prepare($_POST['products_model']),
    'products_price' => Text::input($_POST['products_price']),
    'products_date_available' => (date('Y-m-d') < $products_date_available) ? $products_date_available : 'NULL',
    'products_weight' => (float)Text::input($_POST['products_weight']),
    'products_status' => Text::input($_POST['products_status']),
    'products_tax_class_id' => Text::input($_POST['products_tax_class_id']),
    'manufacturers_id' => (int)Text::input($_POST['manufacturers_id']),
    'products_last_modified' => 'NOW()',
    'products_gtin' => (Text::is_empty($_POST['products_gtin']))
                     ? 'NULL'
                     : str_pad(Text::prepare($_POST['products_gtin']), 14, '0', STR_PAD_LEFT),
    'importers_id' => (int)Text::input($_POST['importers_id']),
  ];

  $products_image = new upload('products_image');
  $products_image->set_extensions(['png', 'gif', 'jpg', 'jpeg', 'svg', 'webp']);
  $products_image->set_destination(DIR_FS_CATALOG_IMAGES);
  if ($products_image->parse() && $products_image->save()) {
    $sql_data['products_image'] = Text::prepare($products_image->filename);
  }

  $db->perform('products', $sql_data, 'update', "products_id = " . (int)$products_id);

  foreach (language::load_all() as $l) {
    $sql_data = [
      'products_name' => Text::prepare($_POST['products_name'][$l['id']]),
      'products_description' => Text::prepare($_POST['products_description'][$l['id']]),
      'products_url' => Text::prepare($_POST['products_url'][$l['id']]),
      'products_seo_description' => Text::prepare($_POST['products_seo_description'][$l['id']]),
      'products_seo_keywords' => Text::prepare($_POST['products_seo_keywords'][$l['id']]),
      'products_seo_title' => Text::prepare($_POST['products_seo_title'][$l['id']]),
    ];

    $db->perform('products_description', $sql_data, 'update', "products_id = " . (int)$products_id . " AND language_id = " . (int)$l['id']);
  }

  $pi_sort_order = 0;
  $piArray = [0];

  foreach ($_FILES as $key => $value) {
// Update existing large product images
    if (preg_match('{\Aproducts_image_large_([0-9]+)\z}', $key, $matches)) {
      $pi_sort_order++;
      $sql_data = ['htmlcontent' => Text::prepare($_POST['products_image_htmlcontent_' . $matches[1]]), 'sort_order' => $pi_sort_order];

      $t = new upload($key);
      $t->set_extensions(['png', 'gif', 'jpg', 'jpeg', 'svg', 'webp']);
      $t->set_destination(DIR_FS_CATALOG_IMAGES);
      if ($t->parse() && $t->save()) {
        $sql_data['image'] = Text::prepare($t->filename);
      }

      $db->perform('products_images', $sql_data, 'update', "products_id = " . (int)$products_id . " AND id = " . (int)$matches[1]);

      $piArray[] = (int)$matches[1];
    } elseif (preg_match('{\Aproducts_image_large_new_([0-9]+)\z}', $key, $matches)) {
// Insert new large product images
      $sql_data = ['products_id' => (int)$products_id, 'htmlcontent' => Text::prepare($_POST['products_image_htmlcontent_new_' . $matches[1]]), 'sort_order' => (int)$_POST['sort_order_new_' . $matches[1]]];

      $t = new upload($key);
      $t->set_extensions(['png', 'gif', 'jpg', 'jpeg', 'svg', 'webp']);
      $t->set_destination(DIR_FS_CATALOG_IMAGES);
      if ($t->parse() && $t->save()) {
        $pi_sort_order++;
        
        $sql_data['image'] = Text::prepare($t->filename);
        $sql_data['sort_order'] = $pi_sort_order;

        $db->perform('products_images', $sql_data);

        $piArray[] = mysqli_insert_id($db);
      }
    }
  }

  $product_images_query = $db->query("SELECT image FROM products_images WHERE products_id = " . (int)$products_id . " AND id NOT IN (" . implode(', ', $piArray) . ")");
  if (mysqli_num_rows($product_images_query)) {
    while ($product_images = $product_images_query->fetch_assoc()) {
      $duplicate_image_query = $db->query("SELECT COUNT(*) AS total FROM products_images WHERE image = '" . $db->escape($product_images['image']) . "'");
      $duplicate_image = $duplicate_image_query->fetch_assoc();

      if ($duplicate_image['total'] < 2) {
        if (file_exists(DIR_FS_CATALOG_IMAGES . $product_images['image'])) {
          @unlink(DIR_FS_CATALOG_IMAGES . $product_images['image']);
        }
      }
    }

    $db->query("DELETE FROM products_images WHERE products_id = " . (int)$products_id . " AND id NOT IN (" . implode(', ', $piArray) . ")");
  }

  return $Admin->link('catalog.php', ['cPath' => $cPath, 'pID' => $products_id]);
