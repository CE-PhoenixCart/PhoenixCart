<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $always_valid_actions = ['expire'];
  require 'includes/application_top.php';
  class_exists('abstract_page_cfgm');

  $classes = [];
  if ($dir = @dir(DIR_FS_CATALOG . 'includes/modules/action_recorder/')) {
    while ($file = $dir->read()) {
      if (!is_dir(DIR_FS_CATALOG . 'includes/modules/action_recorder/' . $file)) {
        if ('php' === pathinfo($file, PATHINFO_EXTENSION)) {
          $classes[] = pathinfo($file, PATHINFO_FILENAME);
        }
      }
    }
    $dir->close();
    sort($classes);
  }

  foreach (array_filter($classes, 'class_exists') as $class) {
    ${$class} = new $class();
  }

  $modules_list = [['id' => '', 'text' => TEXT_ALL_MODULES]];

  $modules = array_column($db->fetch_all("SELECT DISTINCT module FROM action_recorder ORDER BY module"), 'module');
  foreach ($modules as $module) {
    $modules_list[] = [
      'id' => $module,
      'text' => (${$module}->title ?? $module),
    ];
  }

  require 'includes/segments/process_action.php';

  $delete_link = $Admin->link('action_recorder.php', ['action' => 'expire']);
  if (isset($_GET['module']) && in_array($_GET['module'], $modules)) {
    $delete_link->set_parameter('module', $_GET['module']);
  }

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col-12 col-lg-6 text-start text-lg-end align-self-center pb-1">
      <?= 
      $Admin->button(GET_HELP, '', 'btn-dark me-2', GET_HELP_LINK, ['newwindow' => true]),
      $admin_hooks->cat('extraButtons'),
      $Admin->button('<i class="fas fa-search"></i>', '', 'btn-light me-2', $Admin->link('action_recorder.php'), ['data-bs-toggle' => 'collapse', 'data-bs-target' => '#collapseSearch', 'aria-expanded' => 'false', 'aria-controls' => 'collapseSearch']),
      $Admin->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger', $delete_link) 
      ?>
    </div>
  </div>
  
  <div class="collapse row" id="collapseSearch">
    <div class="col-6 align-self-center">
      <?= (new Form('search', $Admin->link('action_recorder.php'), 'get'))->hide_session_id()->hide('module', ''),
         '<div class="input-group mb-1">',
          '<span class="input-group-text">', TEXT_FILTER_SEARCH, '</span>',
           new Input('search', ['placeholder' => TEXT_FILTER_SEARCH]),
         '</div>',
       '</form>'
      ?>
    </div>
    <div class="col-6 align-self-center">
      <?= (new Form('filter', $Admin->link('action_recorder.php'), 'get'))->hide_session_id()->hide('module', ''),
        '<div class="input-group mb-1">',
           '<span class="input-group-text">', TABLE_HEADING_MODULE, '</span>',
           new Select('module', $modules_list, ['onchange' => 'this.form.submit();', 'class' => 'form-select']),
         '</div>',
       '</form>'
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
