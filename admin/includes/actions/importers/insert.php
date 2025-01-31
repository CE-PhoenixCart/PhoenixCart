<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  $importers_name = Text::prepare($_POST['importers_name']);
  $importers_address = Text::prepare($_POST['importers_address']);
  $importers_email = Text::prepare($_POST['importers_email']);

  $sql_data = [
    'importers_name' => $importers_name,
    'importers_address' => $importers_address,
    'importers_email' => $importers_email,
    'date_added' => 'NOW()',
  ];

  $importers_image = new upload('importers_image');
  $importers_image->set_extensions(['png', 'gif', 'jpg', 'jpeg', 'svg', 'webp']);
  $importers_image->set_destination(DIR_FS_CATALOG . 'images/');

  if ($importers_image->parse() && $importers_image->save()) {
    $sql_data['importers_image'] = $importers_image->filename;
  }

  $db->perform('importers', $sql_data);
  $importers_id = mysqli_insert_id($db);

  foreach (array_column(language::load_all(), 'id') as $language_id) {
    $sql_data = [
      'importers_url' => Text::input($_POST['importers_url'][$language_id]),
      'importers_description' => Text::prepare($_POST['importers_description'][$language_id]),
      'importers_id' => $importers_id,
      'languages_id' => $language_id,
    ];

    $db->perform('importers_info', $sql_data);
  }

  return $link->set_parameter('iID', (int)$importers_id);
