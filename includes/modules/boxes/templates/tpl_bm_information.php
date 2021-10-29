<div class="card mb-2 bm-information">
  <div class="card-header"><?= MODULE_BOXES_INFORMATION_BOX_TITLE ?></div>
  <div class="list-group list-group-flush">
<?php
  foreach (MODULE_BOXES_INFORMATION_BOX_DATA as $a => $b) {
    echo '<a class="list-group-item list-group-item-action" href="' . $GLOBALS['Linker']->build($a) . '">' . $b . '</a>' . PHP_EOL;
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
