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


<?= new Form('cart_quantity', $Linker->build('product_info.php')->retain_query_except()->set_parameter('action', 'add_product'), 'post', ['class' => 'was-validated']) ?>

<?php
  if ($messageStack->size('product_action') > 0) {
    echo $messageStack->output('product_action');
  }
?>

  <div class="row is-product"<?= $product->get('data_attributes') ?>>
    <?= $Template->get_content('product_info') ?>
  </div>

</form>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
