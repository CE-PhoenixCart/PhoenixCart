<div class="card mb-2 bm-manufacturer-info">
  <div class="card-header">
    <?= MODULE_BOXES_MANUFACTURER_INFO_BOX_TITLE ?>
  </div>
<?php
  if (!Text::is_empty($_image)) {
?>
  <a href="<?= $GLOBALS['Linker']->build('index.php', ['manufacturers_id' => (int)$_id]) ?>"><?=
    new Image("images/$_image", ['class' => 'card-img-top'], htmlspecialchars($_brand), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT)
  ?></a>
<?php
  }

  $link = $GLOBALS['Linker']->build('index.php', ['manufacturers_id' => (int)$_id]);
?>
  <div class="card-body">
    <h5 class="card-title mb-0"><?= $box_title ?></h5>
  </div>
  <div class="list-group list-group-flush">
    <a class="list-group-item list-group-item-action text-muted" href="<?= $link ?>"><?= MODULE_BOXES_MANUFACTURER_INFO_BOX_OTHER_PRODUCTS ?></a>
<?php
  if (!Text::is_empty($_url)) {
    $link = $GLOBALS['Linker']->build('redirect.php', ['action' => 'manufacturer', 'manufacturers_id' => (int)$_id]);
?>

    <a class="list-group-item list-group-item-action text-muted" href="<?= $link ?>" target="_blank"><?= sprintf(MODULE_BOXES_MANUFACTURER_INFO_BOX_HOMEPAGE, $_brand) ?></a>
<?php
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
