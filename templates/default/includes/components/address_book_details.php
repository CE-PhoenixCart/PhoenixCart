<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>

  <div class="contentText">

<?php
  if (!isset($customer_details)) {
    if (is_numeric($_GET['edit'] ?? null)) {
      $customer_details = $customer->fetch_to_address($_GET['edit']);
    } else {
      $customer_details = null;
    }
  }

  $customer_data->display_input($customer_data->get_fields_for_page('address_book'), $customer_details);

  if ( !isset($_GET['edit']) || ($customer->get('default_address_id') != $_GET['edit']) ) {
?>

      <div class="row mb-2">
        <label for="primary" class="form-check-label col-sm-3 text-start text-sm-end"><?= SET_AS_PRIMARY ?></label>
        <div class="col-sm-9">
          <div class="form-check">
            <?= new Tickable('primary', ['value' => 'on', 'id' => 'primary', 'class' => 'form-check-input'], 'checkbox') ?>
          </div>
        </div>
      </div>

<?php
  }
?>
  </div>
