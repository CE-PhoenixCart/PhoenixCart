<div class="card mb-2 bm-languages">
  <div class="card-header">
    <?= MODULE_BOXES_LANGUAGES_BOX_TITLE ?>
  </div>

  <div class="list-group list-group-flush">
    <?php
    foreach ($lng->catalog_languages as $key => $value) {
      $image = Text::ltrim_once(
        language::map_to_translation("images/{$value['image']}", $value['directory']),
        DIR_FS_CATALOG);

      $active = ($lng->language['code'] == $key) ? ' active' : '';

      echo '<a class="list-group-item list-group-item-action' . $active . '" href="'
           . $link->set_parameter('language', $key)
           . '">'
           . (new Image($image, [], htmlspecialchars($value['name'])))->set_responsive(false)
           . ' ' . $value['name'] . '</a>' . PHP_EOL;
    }
    ?>
  </div>
</div>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>
