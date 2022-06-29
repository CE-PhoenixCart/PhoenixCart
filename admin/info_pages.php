<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $always_valid_actions = ['delete'];
  require 'includes/application_top.php';
  $link = $Admin->link()->retain_query_except(['action', 'pID']);

  require 'includes/segments/process_action.php';

  require 'includes/template_top.php';
  ?>

  <div class="row">
    <div class="col"><h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1></div>
    <div class="col text-right align-self-center">
      <?=
        empty($action)
      ? $Admin->button(IMAGE_BUTTON_ADD_PAGE, 'fas fa-pen', 'btn-danger', $Admin->link()->set_parameter('action', 'new'))
      : $Admin->button(IMAGE_CANCEL, 'fas fa-angle-left', 'btn-light mt-2', $Admin->link())
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

