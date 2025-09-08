<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  $always_valid_actions = ['delete', 'product_view'];
  require 'includes/application_top.php';
  
  $link = $Admin->link();
  if (isset($_GET['page'])) {
    $link->set_parameter('page', (int)$_GET['page']);
  }

  Guarantor::ensure_global('currencies');

  require 'includes/segments/process_action.php';

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col-12 col-lg-8 text-start text-lg-end align-self-center pb-1">
      <?=
      isset($_GET['action']) ? '' : $Admin->button('<i class="fas fa-search"></i>', '', 'btn-light me-2', $Admin->link('pulse_analytics.php'), ['data-bs-toggle' => 'collapse', 'data-bs-target' => '#collapseSearch', 'aria-expanded' => 'false', 'aria-controls' => 'collapseSearch']),
      $admin_hooks->cat('extraButtons'),
      empty($action)
      ? $Admin->button(BUTTON_PRODUCT_VIEW, 'fas fa-funnel-dollar', 'btn-danger', $Admin->link('pulse_analytics.php', ['action' => 'product_view']))
      : $Admin->button(IMAGE_CANCEL, 'fas fa-angle-left', 'btn-light', $Admin->link('pulse_analytics.php'))
      ?>
    </div>
  </div>
  
  <div class="collapse mb-2" id="collapseSearch">
    <div class="align-self-center">
      <?php
      $customer = $product = '';
      if (!Text::is_empty($_GET['customer'] ?? '')) $customer = Text::input($_GET['customer']);
      if (!Text::is_empty($_GET['product'] ?? '')) $product = Text::input($_GET['product']);
      
      echo (new Form('search', $Admin->link('pulse_analytics.php'), 'get'))->hide_session_id();
      ?>
      <div class="row">
        <div class="col">
          <div class="input-group mt-0">
            <span class="input-group-text"><i class="fa-solid fa-user-clock"></i></span>
            <?= new Input('customer', ['value' => $customer]) ?>
          </div>
        </div>
        <div class="col">
          <div class="input-group mt-0">
            <span class="input-group-text"><i class="fa-solid fa-boxes-packing"></i></span>
            <?= new Input('product', ['value' => $product]) ?>
          </div>
        </div>
        <div class="col">
          <button type="submit" class="btn btn-success"><?= BUTTON_FILTER ?></button>
        </div>
      </div>
      </form>
    </div>
  </div>

<?php
  if ($view_file = $Admin->locate('/views', $action)) {
    require $view_file;
  }

  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
