<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $advert_id = Text::input($_GET['aID']);

  $advert_error = false;

  $new_advert_group = Text::prepare($_POST['new_advert_group']);
  $advert_group = (empty($new_advert_group)) ? Text::prepare($_POST['advert_group']) : $new_advert_group;

  $advert_image_local = Text::input($_POST['advert_image_local']);
  $advert_image_target = Text::input($_POST['advert_image_target']);

  $advert_image = new upload('advert_image');
  $advert_image->set_extensions(['png', 'gif', 'jpg', 'jpeg', 'svg', 'webp']);
  $advert_image->parse();

  if (empty($advert_image->filename)) {
    if ( empty($advert_image_local) && empty($_POST['advert_html_text']) ) {
      $messageStack->add(ERROR_ADVERT_IMAGE_OR_TEXT_REQUIRED, 'error');
      $advert_error = true;
    }
  } else {
    $advert_image->set_destination(DIR_FS_CATALOG . 'images/' . $advert_image_target);
    if ( $advert_image->save() == false ) {
      $advert_error = true;
    }
  }

  if ($advert_error == false) {
    $db_image_location = (Text::is_empty($advert_image_local)) ? $advert_image_target . $advert_image->filename : $advert_image_local;
    $sql_data = [
      'advert_title'    => Text::prepare($_POST['advert_title']),
      'advert_url'      => Text::input($_POST['advert_url']),
      'advert_fragment' => Text::input($_POST['advert_fragment']),
      'advert_image'    => $db_image_location,
      'advert_group'    => $advert_group,
      'sort_order'      => Text::input($_POST['sort_order']),
    ];

    $db->perform('advert', $sql_data, 'update', "advert_id = " . (int)$advert_id);

    $insert_id = mysqli_insert_id($db);

    foreach (array_column(language::load_all(), 'id') as $language_id) {
      $sql_data = [
        'advert_html_text' => Text::prepare($_POST['advert_html_text'][$language_id]),
        'languages_id' => $language_id,
      ];

      $db->perform('advert_info', $sql_data, 'update', "advert_id = " . (int)$advert_id . " AND languages_id = " . (int)$language_id);
    }

    $messageStack->add_session(SUCCESS_IMAGE_UPDATED, 'success');

    $admin_hooks->cat('insertAction');
  }

  return $Admin->link()->retain_query_except(['action']);
