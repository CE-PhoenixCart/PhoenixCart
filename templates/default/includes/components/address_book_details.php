<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>

  <p class="text-right"><?= FORM_REQUIRED_INFORMATION ?></p>

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

  if ( !isset($_GET['edit']) || ($customer->get_default_address_id() != $_GET['edit']) ) {
?>

      <div class="form-group row">
        <label for="primary" class="col-form-label col-sm-3 text-left text-sm-right"><?= SET_AS_PRIMARY ?></label>
        <div class="col-sm-9">
          <div class="checkbox">
            <label><?= new Tickable('primary', ['value' => 'on', 'id' => 'primary', 'class' => ''], 'checkbox') ?></label>
          </div>
        </div>
      </div>

<?php
  }
?>
  </div>
