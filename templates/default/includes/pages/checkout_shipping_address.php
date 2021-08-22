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
  $breadcrumb->add(NAVBAR_TITLE_2, $Linker->build('checkout_shipping_address.php'));

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4"><?= HEADING_TITLE ?></h1>

<?php
  if ($messageStack->size($message_stack_area) > 0) {
    echo $messageStack->output($message_stack_area);
  }
?>

  <div class="row">
    <div class="col-sm-7">
      <h5 class="mb-1"><?= TABLE_HEADING_ADDRESS_BOOK_ENTRIES ?></h5>
      <div><?= (new Form('select_address', $Linker->build('checkout_shipping_address.php')))->hide('action', 'select') ?>
        <table class="table border-right border-left border-bottom table-hover m-0">
          <tbody>
            <?php
  $address_query = $customer->get_all_addresses_query();
  while ($address = $address_query->fetch_assoc()) {
    $input_id = "csa_{$address['address_book_id']}";
    $tickable = new Tickable('address', [
      'value' => $address['address_book_id'],
      'id' => $input_id,
      'aria-describedby' => $input_id,
      'class' => 'custom-control-input',
    ], 'radio');
?>
            <tr class="table-selection">
              <td><label for="<?= $input_id ?>"><?= $customer_data->get_module('address')->format($address, true, ' ', ', ') ?></label></td>
              <td align="text-right">
                <div class="custom-control custom-radio custom-control-inline">
                  <?= $tickable->tick($address['address_book_id'] == $_SESSION['sendto']) ?>
                  <label class="custom-control-label" for="<?= $input_id ?>">&nbsp;</label>
                </div>
              </td>
            </tr>
            <?php
  }
?>
          </tbody>
        </table>
        <div class="buttonSet mt-1">
          <?= new Button(BUTTON_SELECT_ADDRESS, 'fas fa-user-cog', 'btn-success btn-lg btn-block') ?>
        </div>
      </form></div>
    </div>
    <div class="col-sm-5">
      <h5 class="mb-1"><?= TABLE_HEADING_SHIPPING_ADDRESS ?></h5>
      <div class="border">
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><?= SHIPPING_FA_ICON . $customer->make_address_label($_SESSION['sendto'], true, ' ', '<br>') ?></li>
        </ul>
      </div>
    </div>
  </div>

<?php
  if ($addresses_count < MAX_ADDRESS_BOOK_ENTRIES) {
?>
    <hr>

    <h5 class="mb-1"><?= TABLE_HEADING_NEW_SHIPPING_ADDRESS ?></h5>

    <p class="font-weight-lighter"><?= TEXT_CREATE_NEW_SHIPPING_ADDRESS ?></p>
<?php
    echo (new Form('checkout_new_address', $Linker->build('checkout_shipping_address.php')))->hide('action', 'submit') . PHP_EOL;
    require $Template->map('checkout_new_address.php', 'component');
    echo $hooks->cat('injectFormDisplay');
    echo new Button(BUTTON_ADD_NEW_ADDRESS, 'fas fa-user-cog', 'btn-success btn-lg btn-block');
    echo '</form>' . PHP_EOL;
  }
?>

  <div class="buttonSet">
    <?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', 'btn-light mt-1', [], $Linker->build('checkout_shipping.php')) ?>
  </div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
