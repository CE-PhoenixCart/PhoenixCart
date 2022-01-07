<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require $Template->map('template_top.php', 'component');
?>

  <div class="row">
    <?= $Template->get_content('product_info_not_found') ?>
  </div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
