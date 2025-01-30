<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($_POST['products_id'], $_POST['categories_id'])) {
    $products_id = Text::input($_POST['products_id']);
    $categories_id = Text::input($_POST['categories_id']);

    if ($_POST['copy_as'] == 'link') {
      if ($categories_id == $current_category_id) {
        $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
      } else {
        $check_query = $db->query("SELECT COUNT(*) AS total FROM products_to_categories WHERE products_id = " . (int)$products_id . " AND categories_id = " . (int)$categories_id);
        $check = $check_query->fetch_assoc();
        if ($check['total'] < 1) {
          $db->query("INSERT INTO products_to_categories (products_id, categories_id) VALUES (" . (int)$products_id . ", " . (int)$categories_id . ")");
        }
      }
    } elseif ($_POST['copy_as'] == 'duplicate') {
      $db_columns = [
        'products' => [
          'products_quantity' => null,
          'products_model' => null,
          'products_image' => null,
          'products_price' => null,
          'products_date_added' => 'NOW()',
          'products_date_available' => null,
          'products_weight' => null,
          'products_status' => 0,
          'products_tax_class_id' => null,
          'manufacturers_id' => null,
          'products_gtin' => null,
          'importers_id' => null,
        ],
        'products_description' => [
          'products_id' => null,
          'language_id' => null,
          'products_name' => null,
          'products_description' => null,
          'products_url' => null,
          'products_viewed' => 0,
          'products_seo_title' => null,
          'products_seo_description' => null,
          'products_seo_keywords' => null,
        ],
        'products_images' => [
          'products_id' => null,
          'image' => null,
          'htmlcontent' => null,
          'sort_order' => null,
        ],
      ];

      $parameters = ['db' => &$db_columns];
      $admin_hooks->call('categories', 'preDuplicateCopyToConfirmAction', $parameters);
      $products_id = $db->copy($db_columns, 'products_id', (int)$products_id);
      $db->query("INSERT INTO products_to_categories (products_id, categories_id) VALUES (" . (int)$products_id . ", " . (int)$categories_id . ")");
    }
  }

  return $Admin->link('catalog.php', ['cPath' => $categories_id, 'pID' => $products_id]);
