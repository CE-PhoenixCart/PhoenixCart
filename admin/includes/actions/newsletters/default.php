<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($_GET['nID'])) {
    $newsletter_id = Text::input($_GET['nID']);
  } elseif (in_array($action, ['delete', 'new'])) {
    return;
  }

  if (!in_array($action, ['delete', 'new', 'send', 'confirm_send'])) {
    return;
  }

  function phoenix_newsletter_is_locked() {
    return $GLOBALS['db']->query("SELECT locked FROM newsletters WHERE newsletters_id = " . (int)($GLOBALS['newsletter_id']))->fetch_assoc()['locked'] ?? false;
  }

  if (!isset($_GET['nID']) || !phoenix_newsletter_is_locked()) {
    $newsletter_errors = [
      'delete' => ERROR_REMOVE_UNLOCKED_NEWSLETTER,
      'new' => ERROR_EDIT_UNLOCKED_NEWSLETTER,
      'send' => ERROR_SEND_UNLOCKED_NEWSLETTER,
      'confirm_send' => ERROR_SEND_UNLOCKED_NEWSLETTER,
    ];

    $messageStack->add_session($newsletter_errors[$action], 'error');

    return $link->set_parameter('nID', (int)$newsletter_id);
  }
