<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $always_valid_actions = ['set_flag'];
  
  require 'includes/application_top.php';
  
  $link = $Admin->link()->retain_query_except(['action', 'cID']);
  
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
      isset($_GET['action']) ? '' : $Admin->button('<i class="fas fa-search"></i>', '', 'btn-light me-2', $Admin->link('countries.php'), ['data-bs-toggle' => 'collapse', 'data-bs-target' => '#collapseSearch', 'aria-expanded' => 'false', 'aria-controls' => 'collapseSearch']),
      $admin_hooks->cat('extraButtons'),
      empty($action)
      ? $Admin->button(IMAGE_NEW_COUNTRY, 'fas fa-map-marker-alt', 'btn-danger', $Admin->link('countries.php', ['action' => 'new']))
      : $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', $link)
      ?>
    </div>
  </div>
  
  <div class="collapse mb-2" id="collapseSearch">
    <div class="align-self-center">
      <?php
      $keywords = '';
      if (!Text::is_empty($_GET['search'] ?? '')) {
        $keywords = Text::input($_GET['search']);
      }
      
      echo (new Form('search', $Admin->link('countries.php'), 'get'))->hide_session_id()
        . '<div class="input-group mt-0">'
          . '<span class="input-group-text">' . HEADING_TITLE_SEARCH . '</span>'
          . new Input('search', ['value' => $keywords])
        . '</div>'
      . '</form>'
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
