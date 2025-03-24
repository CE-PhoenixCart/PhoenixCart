<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  $always_valid_actions = ['copy_to_template', 'delete_from_template'];
  require 'includes/application_top.php';

  $tpl = defined('TEMPLATE_SELECTION') ? TEMPLATE_SELECTION : 'default';

  function phoenix_opendir($path) {
    $path = rtrim($path, '/\\') . '/';
    $handle = opendir($path);

    if (!$handle) {
      return;
    }

    while ($filename = readdir($handle)) {
      if (pathinfo($filename, PATHINFO_EXTENSION) === 'php') {
        $filepath = "$path$filename";

        yield [
          'name' => $filepath,
          'is_dir' => is_dir($filepath),
          'writable' => File::is_writable($filepath),
          'size' => filesize($filepath),
          'last_modified' => filemtime($filepath),
        ];
      } elseif (('.' !== $filename[0]) && is_dir("$path$filename")) {
        yield from phoenix_opendir("$path$filename");
      }
    }

    closedir($handle);
  }

  if (!isset($_GET['lngdir'])) {
    $_GET['lngdir'] = $_SESSION['language'];
  }

  $languages = [];
  $language_exists = false;
  foreach (array_values(language::load_all()) as $l) {
    if ($l['directory'] === $_GET['lngdir']) {
      $language_exists = true;
    }

    $languages[] = ['id' => $l['directory'], 'text' => $l['name']];
  }

  if (!$language_exists) {
    $_GET['lngdir'] = $_SESSION['language'];
  }

  require 'includes/segments/process_action.php';

  require 'includes/template_top.php';

  $files_by_group = [];

  foreach (phoenix_opendir(DIR_FS_CATALOG . 'includes/languages/' . $_GET['lngdir']) as $file) {
    if (pathinfo($file['name'], PATHINFO_EXTENSION) === 'php') {
      $filename = Text::ltrim_once($file['name'], DIR_FS_CATALOG . 'includes/languages/');

      $directories = explode('/', $filename);

      $group = implode(' > ', array_slice($directories, 0, -1));
      $file_name = end($directories);

      if (!isset($files_by_group[$group])) {
        $files_by_group[$group] = [];
      }

      $files_by_group[$group][] = ['filename' => $file_name, 'fullpath' => $filename];
    }
  }
  ?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= sprintf(HEADING_TITLE_2, $tpl) ?></h1>
    </div>
    <div class="col-12 col-lg-4 text-start text-lg-end align-self-center pb-1">
      <div class="row">
        <div class="col">
          <?= (new Form('lng', $Admin->link(), 'get'))->hide_session_id(), (new Select('lngdir', $languages, ['onchange' => 'this.form.submit();']))->set_selection($_GET['lngdir']), '</form>' ?>
        </div>
        <div class="col">
          <?=
          $Admin->button(GET_HELP, '', 'btn-dark', GET_HELP_LINK, ['newwindow' => true]),
          $admin_hooks->cat('extraButtons')
          ?>
        </div>
      </div>
    </div>
  </div>

  <?php
  $filename = $_GET['lngdir'] . '.php';
  ?>

  <div class="accordion" id="accordionExplorer">
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMain" aria-expanded="false" aria-controls="collapseMain"><?= $filename ?></button>
      </h2>
      <div id="collapseMain" class="accordion-collapse collapse" data-bs-parent="#accordionExplorer">
        <div class="accordion-body">
          <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <?php
              $file_exists = DIR_FS_CATALOG . 'templates/' . $tpl . '/includes/languages/' .  Text::input($filename);
              $copy_to = (file_exists($file_exists)) ? '<a href="' . $Admin->link('language_explorer.php', ['lang' => $_GET['lngdir'], 'file' => Text::input($filename), 'action' => 'delete_from_template']) . '"><i class="fas fa-trash text-danger me-5"></i></a><i class="fas fa-circle-check text-success"></i>' : '<a href="' . $Admin->link('language_explorer.php', ['lang' => $_GET['lngdir'], 'file' => Text::input($filename), 'action' => 'copy_to_template']) . '"><i class="fas fa-copy"></i></a>';

              echo $filename;
              ?>
              <span><?= $copy_to ?></span>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <?php
    $n = 1;
    foreach ($files_by_group as $group => $files) {
      echo '<div class="accordion-item">';
        echo '<h2 class="accordion-header">';
          echo '<button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_' . $n . '" aria-expanded="false" aria-controls="collapse_' . $n . '">' . $group . '</button>';
        echo '</h2>';
        echo '<div id="collapse_' . $n . '" class="accordion-collapse collapse" data-bs-parent="#accordionExplorer">';
          echo '<div class="accordion-body">';
            echo '<ul class="list-group">';
              foreach ($files as $file) {
                $filename = Text::input($file['fullpath']);
                $file_exists = DIR_FS_CATALOG . "templates/$tpl/includes/languages/$filename";

                $copy_to = (file_exists($file_exists)) ? '<a href="' . $Admin->link('language_explorer.php', ['lang' => $_GET['lngdir'], 'file' => $filename, 'action' => 'delete_from_template']) . '"><i class="fas fa-trash text-danger me-5"></i></a><i class="fas fa-circle-check text-success"></i>' : '<a href="' . $Admin->link('language_explorer.php', ['lang' => $_GET['lngdir'], 'file' => $filename, 'action' => 'copy_to_template']) . '"><i class="fas fa-copy"></i></a>';

                echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                  echo basename($filename);
                  echo '<span>' . $copy_to . '</span>';
                echo '</li>';
              }
            echo '</ul>';
          echo '</div>';
        echo '</div>';
      echo '</div>';

      $n++;
    }
    ?>
  </div>
  
  <script>document.addEventListener('DOMContentLoaded', function () { var active = sessionStorage.getItem('activeETab'); if (active) { var element = document.getElementById(active); if (element) { element.classList.add('show'); var nearestButton = document.querySelector('button[aria-controls="' + active + '"]'); if (nearestButton) { nearestButton.classList.remove('collapsed'); nearestButton.setAttribute('aria-expanded', 'true'); } } } document.getElementById('accordionExplorer').addEventListener('shown.bs.collapse', function (e) { sessionStorage.setItem('activeETab', e.target.id); }); });</script>

  <?php
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
  ?>

