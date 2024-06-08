<div class="<?= MODULE_CONTENT_FOOTER_INFORMATION_CONTENT_WIDTH ?> cm-footer-information-links">
  <h4><?= MODULE_CONTENT_FOOTER_INFORMATION_HEADING_TITLE ?></h4>
  <nav class="nav flex-column">
    <?php
  foreach (MODULE_CONTENT_FOOTER_INFORMATION_DATA as $page => $text) {
    echo '<a class="nav-link pl-0" href="' . $GLOBALS['Linker']->build($page) . '">' . $text . '</a>' . PHP_EOL;
  }
?>
  </nav>
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
