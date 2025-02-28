<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  require 'includes/segments/process_action.php';

  if (!Path::is_writable(DIR_FS_CATALOG . 'images/')) {
    $messageStack->add(sprintf(ERROR_IMAGES_DIRECTORY_NOT_WRITEABLE, $Admin->link('sec_dir_permissions.php')), 'error');
  }

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col-12 col-lg-8 text-start text-lg-end align-self-center pb-1">
      <?=
      $Admin->button(GET_HELP, '', 'btn-dark me-2', GET_HELP_LINK, ['newwindow' => true]),
      $admin_hooks->cat('extraButtons'),
      empty($action)
      ? $Admin->button(BUTTON_EDIT_FAVICON_LOGO, 'fas fa-heart fa-fw', 'btn-danger me-2', $Admin->link('store_logo.php', ['action' => 'edit_favicon'])) . $Admin->button(BUTTON_EDIT_MINI_LOGO, 'fas fa-minimize fa-fw', 'btn-danger me-2', $Admin->link('store_logo.php', ['action' => 'edit_mini'])) . $Admin->button(BUTTON_EDIT_LOGO, 'fas fa-maximize fa-fw', 'btn-danger', $Admin->link('store_logo.php', ['action' => 'edit'])) 
      : $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', $Admin->link('store_logo.php'))
      ?>
    </div>
  </div>

<?php
  if ($view_file = $Admin->locate('/views', $action)) {
    require $view_file;
  }
  
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
