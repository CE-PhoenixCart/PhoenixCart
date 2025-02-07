<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  $languages = [];
  $language_selected = DEFAULT_LANGUAGE;
  foreach (language::load_all() as $l) {
    $languages[] = ['id' => $l['code'], 'text' => $l['name']];
    if ($l['directory'] == $_SESSION['language']) {
      $language_selected = $l['code'];
    }
  }

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4"><?= STORE_NAME ?></h1>
    </div>
    <?php
    if (count($languages) > 1) {
      ?>
      <div class="col-sm-4 text-end"><?=
        (new Form('adminlanguage', $Admin->link('index.php'), 'get'))->hide_session_id(),
        (new Select('language', $languages, ['class' => 'form-select', 'onchange' => 'this.form.submit();']))->set_selection($language_selected),
        '</form>'
      ?></div>
      <?php
    }
    ?>
  </div>

  <div class="row">
    <?php
    if ( defined('MODULE_ADMIN_DASHBOARD_INSTALLED') && !Text::is_empty(MODULE_ADMIN_DASHBOARD_INSTALLED) ) {
      foreach (explode(';', MODULE_ADMIN_DASHBOARD_INSTALLED) as $adm) {
        $class = pathinfo($adm, PATHINFO_FILENAME);

        $module = new $class();

        if ( $module->isEnabled() ) {
          $module_width = $module->content_width;

          echo '<div class="' . $module_width . '">';
            echo '<div class="h-100 card p-1">';
              echo $module->getOutput();
            echo '</div>';
          echo '</div>' . PHP_EOL;
        }
      }
    }
    ?>
  </div>

<?php
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
