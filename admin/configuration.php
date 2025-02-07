<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';
  $link = $Admin->link()->retain_query_except(['action', 'cID']);

  require 'includes/segments/process_action.php';

  $gID = $_GET['gID'] ?? 1;

  $cfg_group = $db->query("SELECT configuration_group_title, configuration_group_help_link, configuration_group_addons_links FROM configuration_group WHERE configuration_group_id = " . (int)$gID)->fetch_assoc();
  
  $get_addons_link = '';
  if (!empty($cfg_group['configuration_group_addons_links'])) {
    $addons = json_decode($cfg_group['configuration_group_addons_links'], true);

    $get_addons_link = '';
    $get_addons_link .= '<div class="btn-group" role="group">';
      $get_addons_link .= '<button type="button" class="btn btn-dark me-2 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">';
        $get_addons_link .= GET_ADDONS;
      $get_addons_link .= '</button>';
      $get_addons_link .= '<div class="dropdown-menu">';
      foreach ($addons as $k => $v) {
        $get_addons_link .= '<a class="dropdown-item" target="_blank" href="' . $v . '">' . constant($k) . '</a>';
      }
      $get_addons_link .= '</div>';
    $get_addons_link .= '</div>';
  }

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= $cfg_group['configuration_group_title'] ?></h1>
    </div>
    <div class="col-12 col-lg-8 text-start text-lg-end align-self-center pb-1">
      <?=
      $get_addons_link,
      $Admin->button(GET_HELP, '', 'btn-dark', $cfg_group['configuration_group_help_link'], ['newwindow' => true]),
      $admin_hooks->cat('extraButtons')
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
