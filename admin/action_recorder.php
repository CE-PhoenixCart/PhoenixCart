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
    <div class="col self-align-center">
      <?=
      (new Form('search', $Admin->link('action_recorder.php'), 'get'))->hide_session_id()->hide('module', ''),
        new Input('search', ['placeholder' => TEXT_FILTER_SEARCH, 'class' => 'form-control form-control-sm mb-1']),
      '</form>',
      (new Form('filter', $Admin->link('action_recorder.php'), 'get'))->hide_session_id()->hide('module', ''),
        new Select('module', $modules_list, ['onchange' => 'this.form.submit();', 'class' => 'custom-select custom-select-sm']),
      '</form>'
      ?>
    </div>
    <div class="col-12 col-lg-8 text-left text-lg-right align-self-center pb-1">
      <?= 
      $Admin->button(GET_HELP, '', 'btn-dark mr-2', GET_HELP_LINK, ['newwindow' => true]),
      $admin_hooks->cat('extraButtons'),
      $Admin->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger', $delete_link) 
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
