<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

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

  $link = $Admin->link()->retain_query_except(['oID', 'action']);
  require 'includes/segments/process_action.php';
  
  $existing_slugs = array_column($db->fetch_all("SELECT DISTINCT slug FROM outgoing_tpl ORDER BY slug"), 'slug');
  $available_slugs = array_map(function($e){
    return pathinfo($e, PATHINFO_FILENAME);
  }, glob(DIR_FS_CATALOG . 'includes/modules/outgoing/*.php'));
 
  $missing_slugs = array_diff($available_slugs, $existing_slugs);

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE; ?></h1>
    </div>
    <div class="col text-end align-self-center">
      <?=
      $get_addons_link,
      $Admin->button(GET_HELP, '', 'btn-dark me-2', GET_HELP_LINK, ['newwindow' => true]),
      $admin_hooks->cat('extraButtons'),
      empty($action)
      ? ''
      : $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', $link)
      ?>
    </div>
  </div>

<?php
  if (sizeof($missing_slugs) > 0) {
    $missing = implode(', ', $missing_slugs);
    echo sprintf(MISSING_SLUGS, $missing, $Admin->button(BUTTON_INSERT_NEW_SLUG, 'fas fa-id-card', 'btn-danger', $Admin->link('outgoing_tpl.php', ['action' => 'new'])));
  }
  
  if ($view_file = $Admin->locate('/views', $action)) {
    require $view_file;
  }

  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
