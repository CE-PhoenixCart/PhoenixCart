<div class="<?= MODULE_CONTENT_FOOTER_INFORMATION_CONTENT_WIDTH ?> cm-footer-information-links">
  <p class="fs-4 fw-semibold mb-1"><?= MODULE_CONTENT_FOOTER_INFORMATION_HEADING_TITLE ?></p>
  
  <nav class="nav nav-pills flex-column">
    <?php
    foreach (MODULE_CONTENT_FOOTER_INFORMATION_DATA as $page => $text) {
      echo '<a class="nav-link ps-0 text-body-emphasis" href="' . $GLOBALS['Linker']->build($page) . '">' . $text . '</a>' . PHP_EOL;
    }
    ?>
  </nav>
</div>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>
