<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $lID = Text::input($_GET['lID']);

  $lng = $db->query("SELECT code FROM languages WHERE languages_id = " . (int)$lID)->fetch_assoc();

  $remove_language = $lng['code'] != DEFAULT_LANGUAGE;
  if (!$remove_language) {
    $messageStack->add(ERROR_REMOVE_DEFAULT_LANGUAGE, 'error');
  }

  $admin_hooks->cat('deleteAction');
