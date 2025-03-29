<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

    $newsletter = $db->query("SELECT newsletters_id, title, content, module FROM newsletters WHERE newsletters_id = " . (int)$newsletter_id)->fetch_assoc();

    $nInfo = new objectInfo($newsletter);

    $module_name = $nInfo->module;
    $module = new $module_name($nInfo->title, $nInfo->content);
?>

  <div class="alert alert-info">
    <i class="fas fa-spinner fa-5x fa-spin float-end me-4"></i>
    <?= TEXT_PLEASE_WAIT ?>
    <div class="clearfix"></div>
  </div>

<?php
  System::set_time_limit(0);
  flush();
  $module->send($nInfo->newsletters_id);
?>

  <div class="alert alert-success">
    <i class="fas fa-thumbs-up fa-5x float-end me-4"></i>
    <?= TEXT_FINISHED_SENDING_EMAILS ?>
    <div class="clearfix"></div>
  </div>

  <div class="mt-2">
    <?= $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', $GLOBALS['link']) ?>
  </div>
