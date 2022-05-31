<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $newsletter = $db->query("SELECT title, content, module FROM newsletters WHERE newsletters_id = " . (int)$newsletter_id)->fetch_assoc();

  $nInfo = new objectInfo($newsletter);

  $module_name = $nInfo->module;
  $module = new $module_name($nInfo->title, $nInfo->content);

  echo $module->confirm();
?>
