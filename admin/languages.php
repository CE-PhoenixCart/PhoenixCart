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
  
  $get_addons_link = '';
  $get_addons_link .= '<div class="btn-group" role="group">';
    $get_addons_link .= '<button type="button" class="btn btn-dark me-2 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">';
      $get_addons_link .= GET_ADDONS;
    $get_addons_link .= '</button>';
    $get_addons_link .= '<div class="dropdown-menu">';
    foreach (GET_ADDONS_LINKS as $k => $v) {
      $get_addons_link .= '<a class="dropdown-item" target="_blank" href="' . $v . '">' . $k . '</a>';
    }
    $get_addons_link .= '</div>';
  $get_addons_link .= '</div>';
  
  $link = $Admin->link()->retain_query_except(['action', 'lID']);

  require 'includes/segments/process_action.php';

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col-12 col-lg-8 text-start text-lg-end align-self-center pb-1">
      <?=
      $get_addons_link,
      $Admin->button(GET_HELP, '', 'btn-dark me-2', GET_HELP_LINK, ['newwindow' => true]),
      $admin_hooks->cat('extraButtons'),
      empty($action)
      ? $Admin->button(IMAGE_NEW_LANGUAGE, 'fas fa-comment-dots', 'btn-danger', $Admin->link('languages.php', ['action' => 'new']))
      : $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', $Admin->link('languages.php'))
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
