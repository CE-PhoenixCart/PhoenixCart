<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $hooks->register_pipeline('progress');

  $breadcrumb->add(NAVBAR_TITLE_1, $Linker->build('checkout_payment.php'));
  $breadcrumb->add(NAVBAR_TITLE_2, $Linker->build('checkout_payment_address.php'));

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4 mb-4"><?= HEADING_TITLE ?></h1>

<?php
  if ($messageStack->size($message_stack_area) > 0) {
    echo $messageStack->output($message_stack_area);
  }
?>

  <div class="row">
    <div class="col-sm-7 mb-3">
      <h5 class="mb-1"><?= TABLE_HEADING_ADDRESS_BOOK_ENTRIES ?></h5>
      <div><?= (new Form('select_address', $Linker->build('checkout_payment_address.php'), 'post', ['class' => 'was-validated']))->hide('action', 'select') ?>
        <table class="table border table-hover m-0">
          <?php
  $addresses_query = $customer->get_all_addresses_query();
  while ($address = $addresses_query->fetch_assoc()) {
    $label_for = 'cpa_' . $address['address_book_id'];
    $tickable = new Tickable('address', ['value' => $address['address_book_id'], 'id' => $label_for, 'aria-describedby' => $label_for, 'class' => 'form-check-input'], 'radio');
?>
          <tr class="table-selection">
            <td>
              <label for="<?= $label_for ?>"><?= $customer_data->get_module('address')->format($address, true, ' ', ', ') ?></label>
            </td>
            <td class="text-end">
              <div class="form-check">
                <?= $tickable->tick($address['address_book_id'] == $_SESSION['billto']) ?>
              </div>
            </td>
          </tr>
          <?php
  }
?>
        </table>
        <div class="mt-1">
          <div class="d-grid">
            <?= new Button(BUTTON_SELECT_ADDRESS, 'fas fa-user-cog', 'btn-success btn-lg') ?>
          </div>
        </div>
      </form></div>
    </div>
    <div class="col-sm-5">
      <h5 class="mb-1"><?= TABLE_HEADING_PAYMENT_ADDRESS ?></h5>
      <div class="border">
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><?= PAYMENT_FA_ICON . $customer->make_address_label($_SESSION['billto'], true, ' ', '<br>') ?>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <?php
  if ($addresses_count < MAX_ADDRESS_BOOK_ENTRIES) {
    $form = new Form('checkout_new_address', $Linker->build('checkout_payment_address.php'), 'post', ['class' => 'was-validated']);
?>

    <hr>

    <h5 class="mb-1"><?= TABLE_HEADING_NEW_PAYMENT_ADDRESS ?></h5>

    <p class="fw-lighter"><?= TEXT_CREATE_NEW_PAYMENT_ADDRESS ?></p>

    <?php
    echo $form->hide('action', 'submit') . PHP_EOL;
    require $Template->map('checkout_new_address.php', 'component');
    echo $hooks->cat('injectFormDisplay');
    
    echo '<div class="d-grid">';
    echo new Button(BUTTON_ADD_NEW_ADDRESS, 'fas fa-user-cog', 'btn-success btn-lg');
    echo '</div>';
    echo '</form>' . PHP_EOL;
  }
?>

  <div class="my-2">
    <?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', 'btn-light', [], $Linker->build('checkout_payment.php')) ?>
  </div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
