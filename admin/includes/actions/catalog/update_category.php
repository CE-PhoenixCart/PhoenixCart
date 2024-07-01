<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($_POST['categories_id'])) {
    $categories_id = Text::input($_POST['categories_id']);
  }
  $sort_order = Text::input($_POST['sort_order']);

  $sql_data = [
    'sort_order' => (int)$sort_order,
    'last_modified' => 'NOW()',
  ];

  $categories_image = new upload('categories_image');
  $categories_image->set_extensions(['png', 'gif', 'jpg', 'jpeg', 'svg', 'webp']);
  $categories_image->set_destination(DIR_FS_CATALOG_IMAGES);

  if ($categories_image->parse() && $categories_image->save()) {
    $sql_data['categories_image'] = $categories_image->filename;
  }
  $admin_hooks->cat(Admin::camel_case($action) . 'Prep');

  $db->perform('categories', $sql_data, 'update', "categories_id = " . (int)$categories_id);

  foreach (language::load_all() as $l) {
    $sql_data = [
      'categories_name' => Text::prepare($_POST['categories_name'][$l['id']]),
      'categories_description' => Text::prepare($_POST['categories_description'][$l['id']]),
      'categories_seo_description' => Text::prepare($_POST['categories_seo_description'][$l['id']]),
      'categories_seo_title' => Text::prepare($_POST['categories_seo_title'][$l['id']]),
    ];

    $admin_hooks->cat('updateCategoryAction');

    $db->perform('categories_description', $sql_data, 'update', "categories_id = " . (int)$categories_id . " AND language_id = " . (int)$l['id']);
  }

  $admin_hooks->cat('insertCategoryUpdateCategoryAction');

  Href::redirect($Admin->link('catalog.php', ['cPath' => $cPath, 'cID' => $categories_id]));
