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

<h1 class="display-4 mb-4"><?= HEADING_TITLE ?></h1>

<?php
  if ($messageStack->size('checkout_confirmation') > 0) {
    echo $messageStack->output('checkout_confirmation');
  }

  $form_action_url = ${$_SESSION['payment']}->form_action_url ?? $Linker->build('checkout_process.php');

  echo new Form('checkout_confirmation', $form_action_url, 'post');
?>

  <div class="row">
    <div class="col-sm-7">
      <h5 class="mb-1"><?= LIST_PRODUCTS . sprintf(LINK_TEXT_EDIT, 'font-weight-lighter ml-2', $Linker->build('shopping_cart.php')) ?></h5>
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
    echo SHIPPING_FA_ICON;
    echo '<h5 class="mb-0">' . HEADING_DELIVERY_ADDRESS . sprintf(LINK_TEXT_EDIT, 'font-weight-lighter ml-2', $Linker->build('checkout_shipping_address.php')) . '</h5>';
    echo '<p class="w-100 mb-1">' . $address->format($order->delivery, 1, ' ', '<br>') . '</p>';
    echo '</li>';
  }

  echo '<li class="list-group-item">';
  echo PAYMENT_FA_ICON;
  echo '<h5 class="mb-0">' . HEADING_BILLING_ADDRESS . sprintf(LINK_TEXT_EDIT, 'font-weight-lighter ml-2', $Linker->build('checkout_payment_address.php')) . '</h5>';
  echo '<p class="w-100 mb-1">' . $address->format($order->billing, 1, ' ', '<br>') . '</p>';
  echo '</li>';

  if ($order->info['shipping_method']) {
    echo '<li class="list-group-item">';
    echo '<h5 class="mb-1">' . HEADING_SHIPPING_METHOD . sprintf(LINK_TEXT_EDIT, 'font-weight-lighter ml-2', $Linker->build('checkout_shipping.php')) . '</h5>';
    echo '<p class="w-100 mb-1">' . $order->info['shipping_method'] . '</p>';
    echo '</li>';
  }

  echo '<li class="list-group-item">';
  echo '<h5 class="mb-1">' . HEADING_PAYMENT_METHOD . sprintf(LINK_TEXT_EDIT, 'font-weight-lighter ml-2', $Linker->build('checkout_payment.php')) . '</h5>';
  echo '<p class="w-100 mb-1">' . $order->info['payment_method'] . '</p>';
  echo '</li>';
?>
        </ul>

      </div>
    </div>
  </div>
  
  <h5 class="mb-1"><?= HEADING_ORDER_COMMENTS ?></h5>
  
  <div class="form-group row">
    <label for="inputComments" class="col-form-label col-sm-4 text-left text-sm-right"><?= ENTRY_COMMENTS ?></label>
    <div class="col-sm-8">
      <?= new Textarea('comments', ['cols' => '60', 'rows' => '5', 'id' => 'inputComments', 'placeholder' => ENTRY_COMMENTS_PLACEHOLDER,]) ?>
    </div>
  </div>

  <?php
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
        echo '<div class="form-group row">';
        foreach ($confirmation['fields'] as $field) {
          echo '<div class="col-form-label col-sm-3 text-left text-sm-right">' . $field['title'] . '</div>';
          echo '<div class="col-sm-9">' . $field['field'] . '</div>';
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

  <div class="mt-3">
    <p>
      <?php
  if (is_array($payment_modules->modules)) {
    echo $payment_modules->process_button();
  }

  echo new Button(sprintf(IMAGE_BUTTON_FINALISE_ORDER, $currencies->format($order->info['total'])), 'fas fa-check-circle', 'btn-success btn-block btn-lg');
?>
    </p>
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
