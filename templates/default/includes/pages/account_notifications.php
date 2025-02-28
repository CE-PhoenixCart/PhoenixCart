<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE_1, $Linker->build('account.php'));
  $breadcrumb->add(NAVBAR_TITLE_2, $Linker->build('account_notifications.php'));

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4 mb-4"><?= HEADING_TITLE ?></h1>

<?= (new Form('account_notifications', $Linker->build('account_notifications.php')))->hide('action', 'process') ?>

  <div class="alert alert-info" role="alert">
    <?= MY_NOTIFICATIONS_DESCRIPTION ?>
  </div>

  <div class="row mb-2 align-items-center">
    <div class="col-form-label col-sm-4 text-start text-sm-end"><?= GLOBAL_NOTIFICATIONS_TITLE ?></div>
    <div class="col-sm-8">
      <div class="form-check">
        <?= (new Tickable('product_global', ['value' => '1', 'class' => 'form-check-input', 'id' => 'inputGlobalNotification'], 'checkbox'))->tick($global['global_product_notifications'] == '1') ?>
        <label for="inputGlobalNotification" class="form-check-label text-body-secondary"><small><?= GLOBAL_NOTIFICATIONS_DESCRIPTION ?></small></label>
      </div>
    </div>
  </div>

<?php
  if ($global['global_product_notifications'] != '1') {
    $products_check_query = $db->query("SELECT COUNT(*) AS total FROM products_notifications WHERE customers_id = " . (int)$_SESSION['customer_id']);
    $products_check = $products_check_query->fetch_assoc();
    if ($products_check['total'] > 0) {
?>

    <div class="w-100"></div>
    <div class="alert alert-warning" role="alert"><?= NOTIFICATIONS_DESCRIPTION ?></div>

    <div class="row mb-2 align-items-center">
      <div class="col-form-label col-sm-4 text-start text-sm-end"><?= MY_NOTIFICATIONS_TITLE ?></div>
      <div class="col-sm-8">
        <?php
      $products_query = $db->query("SELECT pd.products_id, pd.products_name FROM products_description pd, products_notifications pn WHERE pn.customers_id = " . (int)$_SESSION['customer_id'] . " AND pn.products_id = pd.products_id AND pd.language_id = " . (int)$_SESSION['languages_id'] . " ORDER BY pd.products_name");
      while ($products = $products_query->fetch_assoc()) {
        echo '<div class="form-check">';
        echo (new Tickable('products[]', ['value' => $products['products_id'], 'class' => 'form-check-input', 'id' => 'input_' . $products['products_id'] . 'Notification'], 'checkbox'))->tick();
        echo '<label for="input_' . $products['products_id'] . 'Notification" class="form-check-label text-body-secondary"><small>' . $products['products_name'] . '</small></label>';
        echo '</div>';
      }
?>
      </div>
    </div>

<?php
    } else {
?>

    <div class="alert alert-warning" role="alert">
      <?= NOTIFICATIONS_NON_EXISTING ?>
    </div>

<?php
    }
  }
?>

  <div class="d-grid">
    <?= new Button(IMAGE_BUTTON_UPDATE_PREFERENCES, 'fas fa-users-cog', 'btn-success btn-lg') ?>
  </div>
  
  <div class="my-2">
    <?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', 'btn-light', [], $Linker->build('account.php')) ?>
  </div>

</form>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
