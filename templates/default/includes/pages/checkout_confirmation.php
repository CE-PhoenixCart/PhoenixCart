<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

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
      <p class="fs-5 fw-semibold mb-1"><?= LIST_PRODUCTS . sprintf(LINK_TEXT_EDIT, 'fw-lighter ms-2', $Linker->build('shopping_cart.php')) ?></p>
      
      <table class="table table-hover border">
        <tbody>
          <?php
          foreach ($order->products as $product) {
            echo '<tr>';
              echo '<th class="fs-5 fw-semibold">';
                echo $product['name'] . ' x ' . $product['qty'];
                if ( (isset($product['attributes'])) && (count($product['attributes']) > 0) ) {
                  foreach ($product['attributes'] as $attribute) {
                    echo '<br><small>- ' . $attribute['option'] . ': ' . $attribute['value'] . '</small>';
                  }
                }
              echo '</th>';
              echo '<td class="text-end">';
                echo $currencies->display_price($product['final_price'], $product['tax'], $product['qty']);
              echo '</td>';
            echo '</tr>';
          }
          ?>
        </tbody>
        <?php        
        if (MODULE_ORDER_TOTAL_INSTALLED) {
          echo '<tfoot class="table-group-divider">';
            echo $order_total_modules->output();
          echo '</tfoot>';
        }
        ?>
      </table>
    </div>
    <div class="col-sm-5">
      <p class="fs-5 fw-semibold mb-1"><?= ORDER_DETAILS ?></p>
      
      <div class="border">
        <ul class="list-group list-group-flush">
          <?php
          $address = $customer_data->get_module('address');
          if ($_SESSION['sendto']) {
            echo '<li class="list-group-item">';
              echo SHIPPING_FA_ICON;
              echo '<p class="fs-5 fw-semibold">' . HEADING_DELIVERY_ADDRESS . sprintf(LINK_TEXT_EDIT, 'fw-lighter ms-2', $Linker->build('checkout_shipping_address.php')) . '</p>';
              echo '<p class="w-100 mb-1">' . $address->format($order->delivery, 1, ' ', '<br>') . '</p>';
            echo '</li>';
          }
  
          echo '<li class="list-group-item">';
            echo PAYMENT_FA_ICON;
            echo '<p class="fs-5 fw-semibold">' . HEADING_BILLING_ADDRESS . sprintf(LINK_TEXT_EDIT, 'fw-lighter ms-2', $Linker->build('checkout_payment_address.php')) . '</p>';
            echo '<p class="w-100 mb-1">' . $address->format($order->billing, 1, ' ', '<br>') . '</p>';
          echo '</li>';
          
          if ($order->info['shipping_method']) {
            echo '<li class="list-group-item">';
              echo '<p class="fs-5 fw-semibold">' . HEADING_SHIPPING_METHOD . sprintf(LINK_TEXT_EDIT, 'fw-lighter ms-2', $Linker->build('checkout_shipping.php')) . '</p>';
              echo '<p class="w-100 mb-1">' . $order->info['shipping_method'] . '</p>';
            echo '</li>';
          }
          
          echo '<li class="list-group-item">';
            echo '<p class="fs-5 fw-semibold">' . HEADING_PAYMENT_METHOD . sprintf(LINK_TEXT_EDIT, 'fw-lighter ms-2', $Linker->build('checkout_payment.php')) . '</p>';
            echo '<p class="w-100 mb-1">' . $order->info['payment_method'] . '</p>';
          echo '</li>';
          ?>
        </ul>

      </div>
    </div>
  </div>

  <hr>
  
  <p class="fs-5 fw-semibold mb-1"><?= HEADING_ORDER_COMMENTS ?></p>
  
  <div class="form-floating mb-2">
    <?= new Textarea('comments', ['style' => 'height: 120px', 'id' => 'inputComments', 'placeholder' => ENTRY_COMMENTS_PLACEHOLDER,]) ?>
    <label for="inputComments"><?= ENTRY_COMMENTS ?></label>
  </div>

  <?php
  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
?>
  <hr>

  <p class="fs-5 fw-semibold mb-1"><?= HEADING_PAYMENT_INFORMATION ?></p>

  <div class="row">
    <?php
    if (!Text::is_empty($confirmation['title'])) {
      echo '<div class="col">';
        echo '<div class="border p-3">';
          echo $confirmation['title'];
        echo '</div>';
      echo '</div>';
    }

    if (isset($confirmation['fields'])) {
      echo '<div class="col">';
      foreach ($confirmation['fields'] as $field) {
        echo '<div class="row mb-3 align-items-center">'; 
          echo '<label class="col-sm-3 col-form-label text-start text-sm-end">' . $field['title'] . '</label>';
          echo '<div class="col-sm-9">' . $field['field'] . '</div>';
        echo '</div>';
      }
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
    <div class="d-grid">
      <?php
      if (is_array($payment_modules->modules)) {
        echo $payment_modules->process_button();
      }

      echo new Button(sprintf(IMAGE_BUTTON_FINALISE_ORDER, $currencies->format($order->info['total'])), 'fas fa-check-circle', 'btn-success btn-lg');
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
