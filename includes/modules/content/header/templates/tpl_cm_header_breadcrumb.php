<div class="<?= MODULE_CONTENT_HEADER_BREADCRUMB_CONTENT_WIDTH ?> cm-header-breadcrumb">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <?php
  foreach ($GLOBALS['breadcrumb']->trail() as $v) {
    if (isset($v['link']) && !Text::is_empty($v['link'])) {
      echo '<li class="breadcrumb-item"><a href="' . $v['link'] . '">' . $v['title'] . '</a></li>';
    } else {
      echo '<li class="breadcrumb-item">' . $v['title'] . '</li>';
    }
  }
?>

    </ol>
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
