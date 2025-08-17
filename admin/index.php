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

  require 'includes/segments/process_action.php';

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= STORE_NAME ?></h1>
    </div>
    <div class="col-12 col-lg-6 text-start text-lg-end align-self-center pb-1">
      <?=
      $Admin->button(GET_HELP, '', 'btn-dark me-2', GET_HELP_LINK, ['newwindow' => true]),
      $admin_hooks->cat('extraButtons') ?>
      <?php
      if (count($languages) > 1) {
        echo $Admin->button('<i class="fas fa-language"></i>', '', 'btn-light me-2', $Admin->link('index.php'), ['data-bs-toggle' => 'collapse', 'data-bs-target' => '#collapseLanguage', 'aria-expanded' => 'false', 'aria-controls' => 'collapseLanguage']);
      }
      ?>
    </div>
  </div>
  
  <?php
  if (count($languages) > 1) {
    ?>
    <div class="collapse" id="collapseLanguage">
      <?= 
      (new Form('adminlanguage', $Admin->link('index.php'), 'get'))->hide_session_id(),
       '<div class="input-group mb-2">',
         '<span class="input-group-text">', HEADING_TITLE_LANGUAGE, '</span>',
         (new Select('language', $languages, ['class' => 'form-select', 'onchange' => 'this.form.submit();']))->set_selection($language_selected),
       '</div>',
      '</form>'
      ?>
    </div>
    <?php
  }
  ?>

  <div class="row">
    <?php
    if ( defined('MODULE_ADMIN_DASHBOARD_INSTALLED') && !Text::is_empty(MODULE_ADMIN_DASHBOARD_INSTALLED) ) {
      foreach (explode(';', MODULE_ADMIN_DASHBOARD_INSTALLED) as $adm) {
        $class = pathinfo($adm, PATHINFO_FILENAME);

        $module = new $class();

        if ( $module->isEnabled() ) {
          $module_width = $module->content_width;

          echo '<div class="' . $module_width . '">';
            echo $module->getOutput();
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
