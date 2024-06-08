<div class="<?= MODULE_CONTENT_PI_MODULAR_CONTENT_WIDTH ?> cm-pi-modular">
  <div class="row">
    <?php
    foreach ($slots as $k => $v) {
      $block_name = "pi_modules_$k";
      if ($GLOBALS['Template']->has_blocks($block_name)) {
        echo '<div class="col-sm-' . $v . '">';
          echo '<div class="row">';
            echo $GLOBALS['Template']->get_blocks($block_name);
          echo '</div>';
        echo '</div>' . PHP_EOL;
      }
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
