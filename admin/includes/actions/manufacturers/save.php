<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $manufacturers_id = Text::input($_GET['mID']);
  $manufacturers_name = Text::prepare($_POST['manufacturers_name']);
  $manufacturers_address = Text::prepare($_POST['manufacturers_address']);
  $manufacturers_email = Text::prepare($_POST['manufacturers_email']);

  $sql_data = [
    'manufacturers_name' => $manufacturers_name,
    'manufacturers_address' => $manufacturers_address,
    'manufacturers_email' => $manufacturers_email,
    'last_modified' => 'NOW()',
  ];

  $manufacturers_image = new upload('manufacturers_image');
  $manufacturers_image->set_extensions(['png', 'gif', 'jpg', 'jpeg', 'svg', 'webp']);
  $manufacturers_image->set_destination(DIR_FS_CATALOG . 'images/');

  if ($manufacturers_image->parse() && $manufacturers_image->save()) {
    $sql_data['manufacturers_image'] = $manufacturers_image->filename;
  }

  $db->perform('manufacturers', $sql_data, 'update', "manufacturers_id = " . (int)$manufacturers_id);

  foreach (array_column(language::load_all(), 'id') as $language_id) {
    $sql_data = [
      'manufacturers_url' => Text::input($_POST['manufacturers_url'][$language_id]),
      'manufacturers_description' => Text::prepare($_POST['manufacturers_description'][$language_id]),
      'manufacturers_seo_description' => Text::prepare($_POST['manufacturers_seo_description'][$language_id]),
      'manufacturers_seo_title' => Text::prepare($_POST['manufacturers_seo_title'][$language_id]),
    ];

    $db->perform('manufacturers_info', $sql_data, 'update', "manufacturers_id = " . (int)$manufacturers_id . " AND languages_id = " . (int)$language_id);
  }

  return $link->set_parameter('mID', (int)$manufacturers_id);
