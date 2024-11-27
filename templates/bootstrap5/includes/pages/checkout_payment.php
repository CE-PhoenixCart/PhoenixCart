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
  $breadcrumb->add(NAVBAR_TITLE_2, $Linker->build('checkout_payment.php'));

  require $Template->map('template_top.php', 'component');

  echo $payment_modules->javascript_validation();
?>

<h1 class="display-4 mb-4"><?= HEADING_TITLE ?></h1>

<?php
  echo new Form('checkout_payment', $Linker->build('checkout_confirmation.php'), 'post', ['class' => 'was-validated', 'onsubmit' => 'return check_form();'], true);

  if (isset($_GET['payment_error']) && is_object(${$_GET['payment_error']}) && ($error = ${$_GET['payment_error']}->get_error())) {
    echo '<div class="alert alert-danger">' . "\n";
    echo '<p class="lead"><b>' . htmlspecialchars($error['title']) . "</b></p>\n";
    echo '<p>' . htmlspecialchars($error['error']) . "</p>\n";
    echo '</div>';
  }

  $selection = $payment_modules->selection();
?>

  <div class="row">
    <div class="col-sm-7 mb-3">
      <p class="fs-5 fw-semibold mb-1"><?= TABLE_HEADING_PAYMENT_METHOD ?></p>
      
      <div>
        <table class="table border table-hover m-0">
          <?php
          foreach ($selection as $choice) {
            ?>
            <tr class="table-selection">
              <td><label class="form-check-label" for="p_<?= $choice['id'] ?>"><?= $choice['module'] ?></label></td>
              <td class="text-end">
                <?php
                if (count($selection) > 1) {
                  $tickable = new Tickable('payment', ['value' => $choice['id'], 'id' => "p_{$choice['id']}", 'class' => 'form-check-input'], 'radio');
                  echo '<div class="form-check form-check-inline">';
                    echo $tickable->require()->tick($choice['id'] === ($_SESSION['payment'] ?? false));
                  echo '</div>';
                } else {
                  echo new Input('payment', ['value' => $choice['id']], 'hidden');
                }
                ?>
              </td>
            </tr>
          <?php
          if (isset($choice['error'])) {
            ?>
          <tr>
            <td colspan="2"><?= $choice['error'] ?></td>
          </tr>
          <?php
            } elseif (isset($choice['fields']) && is_array($choice['fields'])) {
              foreach ($choice['fields'] as $field) {
              ?>
              <tr>
                <td><?= $field['title'] ?></td>
                <td><?= $field['field'] ?></td>
              </tr>
              <?php
              }
            }
          }
          ?>
        </table>

        <?php
        if (count($selection) == 1) {
          echo '<p class="m-2 fw-lighter">' . TEXT_ENTER_PAYMENT_INFORMATION . '</p>';
        }
        ?>
      </div>
    </div>
    <div class="col-sm-5">
      <p class="fs-5 fw-semibold mb-1"><?= TABLE_HEADING_BILLING_ADDRESS, sprintf(LINK_TEXT_EDIT, 'fw-lighter ms-3', $Linker->build('checkout_payment_address.php')) ?></p>
        
      <div class="border">
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><?= PAYMENT_FA_ICON . $customer->make_address_label($_SESSION['billto'], true, ' ', '<br>') ?>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <?= $hooks->cat('injectFormDisplay') ?>

  <div class="d-grid mt-3">
    <?= new Button(BUTTON_CONTINUE_CHECKOUT_PROCEDURE, 'fas fa-angle-right', 'btn-success btn-lg') ?>
  </div>

  <div class="progressBarHook">

  <?php
  $parameters = ['style' => 'progress-bar progress-bar-striped progress-bar-animated bg-info', 'markers' => ['position' => 2, 'min' => 0, 'max' => 100, 'now' => 67]];
  echo $hooks->cat('progressBar', $parameters);
  ?>

  </div>

</form>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
