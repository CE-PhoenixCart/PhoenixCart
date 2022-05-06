<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $newsletter_module = Text::input($_POST['module']);

  if (empty($newsletter_module)) {
    $messageStack->add(ERROR_NEWSLETTER_MODULE, 'error');
    $action = 'new';
    return;
  }

  $allowed = array_map(function($v) {return basename($v, '.php');}, glob('includes/modules/newsletters/*.php'));
  if (!in_array($newsletter_module, $allowed)) {
    $messageStack->add(ERROR_NEWSLETTER_MODULE_NOT_EXISTS, 'error');
    $action = 'new';
    return;
  }

  $title = Text::prepare($_POST['title']);

  if (empty($title)) {
    $messageStack->add(ERROR_NEWSLETTER_TITLE, 'error');
    $action = 'new';
    return;
  }

  $sql_data = [
    'title' => $title,
    'content' => Text::prepare($_POST['content']),
    'module' => $newsletter_module,
  ];

  $newsletter_id = Text::input($_POST['newsletter_id']);
  $db->perform('newsletters', $sql_data, 'update', "newsletters_id = " . (int)$newsletter_id);

  return $link->set_parameter('nID', (int)$newsletter_id);
