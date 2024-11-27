<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $page_content = $Template->get_content('checkout_success');

  $breadcrumb->add(NAVBAR_TITLE_1);
  $breadcrumb->add(NAVBAR_TITLE_2);

  require $Template->map('template_top.php', 'component');

  echo new Form('order', $Linker->build('checkout_success.php', ['action' => 'update']), 'post');
?>

  <div class="row">
    <?= $page_content ?>
  </div>

</form>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
