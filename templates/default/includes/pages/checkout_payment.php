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
  $breadcrumb->add(NAVBAR_TITLE_2, $Linker->build('checkout_payment.php'));

  $comments_textarea = new Textarea('comments', [
    'cols' => '60',
    'rows' => '5',
    'id' => 'inputComments',
    'placeholder' => ENTRY_COMMENTS_PLACEHOLDER,
  ]);
  if (isset($_SESSION['comments'])) {
    $comments_textarea->set_text($_SESSION['comments']);
  }

  require $Template->map('template_top.php', 'component');

  echo $payment_modules->javascript_validation();
?>

<h1 class="display-4"><?= HEADING_TITLE ?></h1>

<?php
  echo new Form('checkout_payment', $Linker->build('checkout_confirmation.php'), 'post', ['onsubmit' => 'return check_form();'], true);

  if (isset($_GET['payment_error']) && is_object(${$_GET['payment_error']}) && ($error = ${$_GET['payment_error']}->get_error())) {
    echo '<div class="alert alert-danger">' . "\n";
    echo '<p class="lead"><b>' . htmlspecialchars($error['title']) . "</b></p>\n";
    echo '<p>' . htmlspecialchars($error['error']) . "</p>\n";
    echo '</div>';
  }

  $selection = $payment_modules->selection();
?>

  <div class="row">
    <div class="col-sm-7">
      <h5 class="mb-1"><?= TABLE_HEADING_PAYMENT_METHOD ?></h5>
      <div>
        <table class="table border-right border-left border-bottom table-hover m-0">
          <?php
          foreach ($selection as $choice) {
            ?>
            <tr class="table-selection">
              <td><label for="p_<?= $choice['id'] ?>"><?= $choice['module'] ?></label></td>
              <td class="text-right">
                <?php
                if (count($selection) > 1) {
                  $tickable = new Tickable('payment', ['value' => $choice['id'], 'id' => "p_{$choice['id']}", 'class' => 'custom-control-input'], 'radio');
                  echo '<div class="custom-control custom-radio custom-control-inline">';
                  echo $tickable->require()->tick($choice['id'] === ($_SESSION['payment'] ?? false));
                  echo '<label class="custom-control-label" for="p_' . $choice['id'] . '">&nbsp;</label>';
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
          echo '<p class="m-2 font-weight-lighter">' . TEXT_ENTER_PAYMENT_INFORMATION . "</p>\n";
        }
        ?>
      </div>
    </div>
    <div class="col-sm-5">
      <h5 class="mb-1">
        <?=
        TABLE_HEADING_BILLING_ADDRESS,
        sprintf(LINK_TEXT_EDIT, 'font-weight-lighter ml-3', $Linker->build('checkout_payment_address.php'))
        ?>
      </h5>
      <div class="border">
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><?= PAYMENT_FA_ICON . $customer->make_address_label($_SESSION['billto'], true, ' ', '<br>') ?>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <hr>

  <div class="form-group row">
    <label for="inputComments" class="col-form-label col-sm-4 text-sm-right"><?= ENTRY_COMMENTS ?></label>
    <div class="col-sm-8"><?= $comments_textarea ?></div>
  </div>

  <?= $hooks->cat('injectFormDisplay') ?>

  <div class="buttonSet">
    <div class="text-right"><?= new Button(BUTTON_CONTINUE_CHECKOUT_PROCEDURE, 'fas fa-angle-right', 'btn-success btn-lg btn-block') ?></div>
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
