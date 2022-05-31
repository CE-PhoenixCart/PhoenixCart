<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $pages_id = Text::input($_GET['pID']);

  $sql_data = [
    'pages_status' => (int)$_POST['page_status'],
    'sort_order' => (int)$_POST['sort_order'],
    'slug' => Text::input($_POST['slug']),
    'last_modified' => 'NOW()',
  ];

  $db->perform('pages', $sql_data, 'update', "pages_id = " . (int)$pages_id);

  foreach (array_column(language::load_all(), 'id') as $language_id) {
    $sql_data = [
      'navbar_title' => Text::input($_POST['navbar_title'][$language_id]),
      'pages_title' => Text::prepare($_POST['page_title'][$language_id]),
      'pages_text' => Text::prepare($_POST['page_text'][$language_id]),
      'pages_id' => $pages_id,
      'languages_id' => $language_id,
    ];

    $db->perform('pages_description', $sql_data, 'update', "pages_id = " . (int)$pages_id . " AND languages_id = " . (int)$language_id);
  }

  return $link->set_parameter('pID', (int)$pages_id);
