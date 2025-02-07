<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  $hooks = new hooks('shop');
  $template_name = defined('TEMPLATE_SELECTION') ? TEMPLATE_SELECTION : 'default';
  $template_name .= '_template';
  $template = new $template_name();
  $directories = $hooks->get_hook_directories();

  function phoenix_find_contents($base, $test) {
    $contents = [];
    if (is_dir($base) && ($handle = @dir($base))) {
      while ($file = $handle->read()) {
        if (('.' !== $file[0]) && $test("$base/$file")) {
          $contents[] = $file;
        }
      }

      $handle->close();
    }

    return $contents;
  }

  function phoenix_find_listeners($class) {
    $listeners = [];

    if (class_exists($class)) {
      $prefix = 'listen_';
      $length = strlen($prefix);
      foreach (get_class_methods($class) as $method) {
        if (substr($method, 0, $length) === $prefix) {
          $listeners[] = substr($method, $length);
        }
      }
    }

    return $listeners;
  }

  $contents = [];
  foreach ($directories as $directory) {
    $directory = dirname($directory);
    foreach (phoenix_find_contents($directory, 'is_dir') as $site) {
      foreach (phoenix_find_contents("$directory/$site", 'is_dir') as $group) {
        foreach (phoenix_find_contents("$directory/$site/$group", 'is_file') as $file) {
          $pathinfo = pathinfo("$directory/$site/$group/$file");
          if ('php' !== ($pathinfo['extension'] ?? null)) {
            continue;
          }

          $class = "hook_{$site}_{$group}_{$pathinfo['filename']}";
          foreach (phoenix_find_listeners($class) as $listener) {
            Guarantor::guarantee_all(
              $contents,
              $site,
              $group,
              $listener,
              $pathinfo['filename']
            )[] = $directory;
          }
        }
      }
    }
  }

  $hooks_query = $db->query(sprintf(<<<'EOSQL'
SELECT hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method
 FROM hooks
EOSQL
    , $db->escape(Text::input($file))));
  while ($hook = $hooks_query->fetch_assoc()) {
    $callable = [];
    if (!empty($hook['hooks_class'])) {
      $callable[] = $hook['hooks_class'];
    }

    if (!empty($hook['hooks_method'])) {
      $callable[] = $hook['hooks_method'];
    }

    Guarantor::guarantee_all(
      $contents,
      $hook['hooks_site'],
      $hook['hooks_group'],
      $hook['hooks_action'],
      $hook['hooks_code']
    )[] = $callable;
  }
  
  require 'includes/segments/process_action.php';

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col-12 col-lg-8 text-start text-lg-end align-self-center pb-1">
      <?=
      $Admin->button(GET_HELP, '', 'btn-dark', GET_HELP_LINK, ['newwindow' => true]),
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
