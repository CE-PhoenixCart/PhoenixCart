<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $always_valid_actions = ['new', 'edit', 'set_flag'];
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
      $Admin->button(GET_HELP, '', 'btn-dark me-2', GET_HELP_LINK, ['newwindow' => true]),
      $admin_hooks->cat('extraButtons'),
      empty($action)
      ? $Admin->button(BUTTON_INSERT_SPECIAL, 'fas fa-funnel-dollar', 'btn-danger', $Admin->link('specials.php', ['action' => 'new']))
      : $Admin->button(IMAGE_CANCEL, 'fas fa-angle-left', 'btn-light', $Admin->link('specials.php')->retain_query_except(['action']))
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
