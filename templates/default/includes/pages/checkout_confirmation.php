<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $hooks->register_pipeline('progress');

  $breadcrumb->add(NAVBAR_TITLE_1, $Linker->build('checkout_shipping.php'));
  $breadcrumb->add(NAVBAR_TITLE_2);

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4"><?= HEADING_TITLE ?></h1>

<?php
  if ($messageStack->size('checkout_confirmation') > 0) {
    echo $messageStack->output('checkout_confirmation');
  }

  $form_action_url = ${$_SESSION['payment']}->form_action_url ?? $Linker->build('checkout_process.php');

  echo new Form('checkout_confirmation', $form_action_url, 'post');
?>

  <div class="row">
    <div class="col-sm-7">
      <h5 class="mb-1"><?= LIST_PRODUCTS ?><small><a class="font-weight-lighter ml-2" href="<?= $Linker->build('shopping_cart.php') ?>"><?= TEXT_EDIT ?></a></small></h5>
      <div class="border">
        <ul class="list-group list-group-flush">
          <?php
  foreach ($order->products as $product) {
    echo '<li class="list-group-item">';
    echo '<span class="float-right">' . $currencies->display_price($product['final_price'], $product['tax'], $product['qty']) . '</span>';
    echo '<h5 class="mb-1">' . $product['name'] . '<small> x ' . $product['qty'] . '</small></h5>';

    if ( (isset($product['attributes'])) && (count($product['attributes']) > 0) ) {
      echo '<p class="w-100 mb-1">';
      foreach ($product['attributes'] as $attribute) {
        echo '- ' . $attribute['option'] . ': ' . $attribute['value'] . '<br>';
      }
      echo '</p>';
    }

    echo '</li>';
  }
?>
        </ul>
        <table class="table mb-0">
          <?php
  if (MODULE_ORDER_TOTAL_INSTALLED) {
    echo $order_total_modules->output();
  }
?>
        </table>
      </div>
    </div>
    <div class="col-sm-5">
      <h5 class="mb-1"><?= ORDER_DETAILS ?></h5>
      <div class="border">
        <ul class="list-group list-group-flush">
          <?php
  $address = $customer_data->get_module('address');
  if ($_SESSION['sendto']) {
    echo '<li class="list-group-item">';
    echo '<i class="fas fa-shipping-fast fa-fw fa-3x float-right text-black-50"></i>';
    echo '<h5 class="mb-0">' . HEADING_DELIVERY_ADDRESS . '<small><a class="font-weight-lighter ml-2" href="' . $Linker->build('checkout_shipping_address.php') . '">' . TEXT_EDIT . '</a></small></h5>';
    echo '<p class="w-100 mb-1">' . $address->format($order->delivery, 1, ' ', '<br>') . '</p>';
    echo '</li>';
  }

  echo '<li class="list-group-item">';
  echo '<i class="fas fa-file-invoice-dollar fa-fw fa-3x float-right text-black-50"></i>';
  echo '<h5 class="mb-0">' . HEADING_BILLING_ADDRESS . '<small><a class="font-weight-lighter ml-2" href="' . $Linker->build('checkout_payment_address.php') . '">' . TEXT_EDIT . '</a></small></h5>';
  echo '<p class="w-100 mb-1">' . $address->format($order->billing, 1, ' ', '<br>') . '</p>';
  echo '</li>';

  if ($order->info['shipping_method']) {
    echo '<li class="list-group-item">';
    echo '<h5 class="mb-1">' . HEADING_SHIPPING_METHOD . '<small><a class="font-weight-lighter ml-2" href="' . $Linker->build('checkout_shipping.php') . '">' . TEXT_EDIT . '</a></small></h5>';
    echo '<p class="w-100 mb-1">' . $order->info['shipping_method'] . '</p>';
    echo '</li>';
  }

  echo '<li class="list-group-item">';
  echo '<h5 class="mb-1">' . HEADING_PAYMENT_METHOD . '<small><a class="font-weight-lighter ml-2" href="' . $Linker->build('checkout_payment.php') . '">' . TEXT_EDIT . '</a></small></h5>';
  echo '<p class="w-100 mb-1">' . $order->info['payment_method'] . '</p>';
  echo '</li>';
?>
        </ul>

      </div>
    </div>
  </div>

  <?php
  if (!Text::is_empty($order->info['comments'])) {
?>
  <h5 class="mb-1"><?= HEADING_ORDER_COMMENTS . '<small><a class="font-weight-lighter ml-2" href="' . $Linker->build('checkout_payment.php') . '">' .TEXT_EDIT . '</a></small>' ?></h5>

  <div class="border mb-3">
    <ul class="list-group list-group-flush">
      <li class="list-group-item">
        <i class="fas fa-comments fa-fw fa-3x float-right text-black-50"></i>
        <?= nl2br(htmlspecialchars($order->info['comments'])) . new Input('comments', ['value' => $order->info['comments']], 'hidden') ?>
      </li>
    </ul>
  </div>

  <?php
  }

  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
?>
  <hr>

  <h5 class="mb-1"><?= HEADING_PAYMENT_INFORMATION ?></h5>

  <div class="row">
    <?php
      if (!Text::is_empty($confirmation['title'])) {
        echo '<div class="col">';
        echo '<div class="bg-light border p-3">';
        echo $confirmation['title'];
        echo '</div>';
        echo '</div>';
      }

      if (isset($confirmation['fields'])) {
        echo '<div class="col">';
        echo '<div class="alert alert-info" role="alert">';
        $fields = '';
        foreach ($confirmation['fields'] as $field) {
          $fields .= $field['title'] . ' ' . $field['field'] . '<br>';
        }

        if (strlen($fields) > strlen('<br>')) {
          echo substr($fields, 0, -strlen('<br>'));
        }
        echo '</div>';
        echo '</div>';
      }
?>
  </div>

  <div class="w-100"></div>
  <?php
    }
  }

  echo $hooks->cat('injectFormDisplay');
?>

  <div class="buttonSet mt-3">
    <div class="text-right">
      <?php
  if (is_array($payment_modules->modules)) {
    echo $payment_modules->process_button();
  }

  echo new Button(sprintf(IMAGE_BUTTON_FINALISE_ORDER, $currencies->format($order->info['total'])), 'fas fa-check-circle', 'btn-success btn-block btn-lg');
?>
    </div>
  </div>

  <div class="progressBarHook">
    <?php
  $parameters = ['style' => 'progress-bar progress-bar-striped progress-bar-animated bg-info', 'markers' => ['position' => 3, 'min' => 0, 'max' => 100, 'now' => 100]];
  echo $hooks->cat('progressBar', $parameters);
?>
  </div>

</form>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
