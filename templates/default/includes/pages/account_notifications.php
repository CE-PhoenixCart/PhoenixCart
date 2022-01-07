<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE_1, $Linker->build('account.php'));
  $breadcrumb->add(NAVBAR_TITLE_2, $Linker->build('account_notifications.php'));

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4"><?= HEADING_TITLE ?></h1>

<?= (new Form('account_notifications', $Linker->build('account_notifications.php')))->hide('action', 'process') ?>

  <div class="alert alert-info" role="alert">
    <?= MY_NOTIFICATIONS_DESCRIPTION ?>
  </div>

  <div class="form-group row align-items-center">
    <div class="col-form-label col-sm-4 text-left text-sm-right"><?= GLOBAL_NOTIFICATIONS_TITLE ?></div>
    <div class="col-sm-8">
      <div class="custom-control custom-switch">
        <?= (new Tickable('product_global', ['value' => '1', 'class' => 'custom-control-input', 'id' => 'inputGlobalNotification'], 'checkbox'))->tick($global['global_product_notifications'] == '1');
        echo '<label for="inputGlobalNotification" class="custom-control-label">' . GLOBAL_NOTIFICATIONS_DESCRIPTION . '&nbsp;</label>';
        ?>
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

    <div class="form-group row align-items-center">
      <div class="col-form-label col-sm-4 text-left text-sm-right"><?= MY_NOTIFICATIONS_TITLE ?></div>
      <div class="col-sm-8">
        <?php
      $products_query = $db->query("SELECT pd.products_id, pd.products_name FROM products_description pd, products_notifications pn WHERE pn.customers_id = " . (int)$_SESSION['customer_id'] . " AND pn.products_id = pd.products_id AND pd.language_id = " . (int)$_SESSION['languages_id'] . " ORDER BY pd.products_name");
      while ($products = $products_query->fetch_assoc()) {
        echo '<div class="custom-control custom-switch">';
        echo (new Tickable('products[]', ['value' => $products['products_id'], 'class' => 'custom-control-input', 'id' => 'input_' . $products['products_id'] . 'Notification'], 'checkbox'))->tick();
        echo '<label for="input_' . $products['products_id'] . 'Notification" class="custom-control-label">' . $products['products_name'] . '</label>';
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

  <div class="buttonSet">
    <div class="text-right"><?= new Button(IMAGE_BUTTON_UPDATE_PREFERENCES, 'fas fa-users-cog', 'btn-success btn-lg btn-block') ?></div>
    <p><?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', '', [], $Linker->build('account.php')) ?></p>
  </div>

</form>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
