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
  $breadcrumb->add(NAVBAR_TITLE_2, $Linker->build('checkout_shipping.php'));

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4 mb-4"><?= HEADING_TITLE ?></h1>

<?= (new Form('checkout_address', $Linker->build('checkout_shipping.php'), 'post', ['class' => 'was-validated']))->hide('action', 'process') ?>

  <div class="row">
    <div class="col-sm-7 mb-3">
      <p class="fs-5 fw-semibold mb-1"><?= TABLE_HEADING_SHIPPING_METHOD ?></p>
      <div>
        <?php
  if ($module_count > 0) {
    if ($free_shipping) {
?>
        <div class="alert alert-info mb-0" role="alert">
          <p class="lead"><b><?= FREE_SHIPPING_TITLE ?></b></p>
          <p class="lead"><?= sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)), new Input('shipping', ['value' => 'free_free'], 'hidden') ?></p>
        </div>
            <?php
    } else {
            ?>
        <table class="table border table-hover m-0">
          <?php
      $method_input = new Tickable('shipping', [
        'class' => 'form-check-input',
      ], 'radio');
      $method_input->require();

      $n = count($quotes);
      foreach ($quotes as $quote) {
        $n2 = count($quote['methods']);
        foreach (($quote['methods'] ?? []) as $method) {
          $bg = (isset($quote['error'])) ? 'table-danger' : 'table-selection';
          ?>
          <tr class="<?= $bg ?>">
            <td>
              <?php
              $label_for = "d_{$method['id']}";
              
              echo '<label class="form-check-label" for="' . $label_for . '">' . $quote['module'] . '</label>';

              if (!Text::is_empty($quote['icon'] ?? '')) {
                echo '&nbsp;' . $quote['icon'];
              }

              if (isset($quote['error'])) {
                echo '<div class="form-text">' . $quote['error'] . '</div>';
              }

              if (!Text::is_empty($method['title'])) {
                echo '<div class="form-text">' . $method['title'] . '</div>';
              }
              ?>
            </td>
            <?php
            $method_value = "{$quote['id']}_{$method['id']}";
            $method_price = $currencies->format(Tax::price($method['cost'], $quote['tax'] ?? 0));
            if ( ($n > 1) || ($n2 > 1) ) {
              ?>
            <td class="text-end">
              <?php
              if (isset($quote['error'])) {
                $error_input = new Tickable('error', [], 'radio');      
      
                echo $error_input->set('value', '')->set('id', $label_for)->set('hidden', '')->set('aria-hidden', 'true');
              } else {
                echo '<div class="form-check form-check-inline">';
                if (isset($_SESSION['shipping']['id'])) {
                  $method_input->tick($method_value === $_SESSION['shipping']['id']);
                }
                echo $method_input
                       ->set('value', $method_value)
                       ->set('id', $label_for)
                       ->set('aria-describedby', $label_for);

                echo '<span class="form-check-label" for="' . $label_for . '">' . $method_price . '</span>';
                echo '</div>';
              }
              ?>
            </td>
            <?php
          } else {
            $method_input = new Input('shipping', ['value' => $method_value], 'hidden');
            ?>
            <td class="text-end"><?= $method_price, $method_input ?></td>
            <?php
          }
          ?>
          </tr>
          <?php
        }
      }
    }
    ?>
    </table>
    <?php
    if ( !$free_shipping && (1 === $module_count) ) {
      ?>
      <p class="m-2 fw-lighter"><?= TEXT_ENTER_SHIPPING_INFORMATION ?></p>
      <?php
    }
  }
  ?>
      </div>
    </div>

    <div class="col-sm-5">
      <p class="fs-5 fw-semibold mb-1">
        <?=
        TABLE_HEADING_SHIPPING_ADDRESS,
        sprintf(LINK_TEXT_EDIT, 'fw-lighter ms-3', $Linker->build('checkout_shipping_address.php'))
?>
      </p>
      <div class="border">
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><?= SHIPPING_FA_ICON . $customer->make_address_label($_SESSION['sendto'], true, ' ', '<br>') ?></li>
        </ul>
      </div>
    </div>
  </div>

 <?= $hooks->cat('injectFormDisplay') ?>

  <div class="d-grid mt-3">
    <?= new Button(BUTTON_CONTINUE_CHECKOUT_PROCEDURE, 'fas fa-angle-right', 'btn-success btn-lg') ?>
  </div>

  <?php
  $parameters = ['style' => 'progress-bar progress-bar-striped progress-bar-animated bg-info', 'markers' => ['position' => 1, 'min' => 0, 'max' => 100, 'now' => 33]];
  echo $hooks->cat('progressBar', $parameters);
?>

</form>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
