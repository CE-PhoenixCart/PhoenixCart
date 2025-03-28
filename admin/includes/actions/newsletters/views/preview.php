<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $newsletter = isset($newsletter_id)
              ? $db->query("SELECT title, content, module FROM newsletters WHERE newsletters_id = " . (int)$newsletter_id)->fetch_assoc()
              : ['title' => '', 'content' => '', 'module' => ''];

  $nInfo = new objectInfo($newsletter);
?>

  <table class="table table-striped">
    <tr>
      <th class="w-25"><?= TEXT_TITLE ?></th>
      <td><?= $nInfo->title ?></td>
    </tr>
    <tr>
      <th><?= TEXT_CONTENT ?></th>
      <td><?= nl2br($nInfo->content) ?></td>
    </tr>
    <?= $admin_hooks->cat('preview') ?>
  </table>

  <div class="mt-2">
    <?= $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', $link) ?>
  </div>

