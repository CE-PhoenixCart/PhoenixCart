<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $sort_order = Text::input($_POST['sort_order']);

  $sql_data = [
    'sort_order' => (int)$sort_order,
    'parent_id' => $current_category_id,
    'date_added' => 'NOW()',
  ];

  $categories_image = new upload('categories_image');
  $categories_image->set_extensions(['png', 'gif', 'jpg', 'jpeg', 'svg', 'webp']);
  $categories_image->set_destination(DIR_FS_CATALOG_IMAGES);

  if ($categories_image->parse() && $categories_image->save()) {
    $sql_data['categories_image'] = $categories_image->filename;
  }
  $admin_hooks->cat(Admin::camel_case($action) . 'Prep');

  $db->perform('categories', $sql_data);

  $categories_id = mysqli_insert_id($db);

  foreach (language::load_all() as $l) {
    $sql_data = [
      'categories_name' => Text::prepare($_POST['categories_name'][$l['id']]),
      'categories_description' => Text::prepare($_POST['categories_description'][$l['id']]),
      'categories_seo_description' => Text::prepare($_POST['categories_seo_description'][$l['id']]),
      'categories_seo_title' => Text::prepare($_POST['categories_seo_title'][$l['id']]),
      'categories_id' => $categories_id,
      'language_id' => $l['id']
    ];

    $admin_hooks->cat('insertCategoryAction');

    $db->perform('categories_description', $sql_data);
  }

  $admin_hooks->cat('insertCategoryUpdateCategoryAction');

  Href::redirect($Admin->link('catalog.php', ['cPath' => $cPath, 'cID' => $categories_id]));
