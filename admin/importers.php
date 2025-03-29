<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  $link = $Admin->link()->retain_query_except(['iID', 'action']);
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
      $admin_hooks->cat('extraButtons'),
      empty($action)
      ? $Admin->button(BUTTON_INSERT_NEW_IMPORTER, 'fas fa-id-card', 'btn-danger', $Admin->link('importers.php', ['action' => 'new']))
      : $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', $link)
      ?>
    </div>
  </div>

<?php
  if ($view_file = $Admin->locate('/views', $action)) {
    require $view_file;
  }
?>

  <script>
  var upload = document.querySelector('#inputImportersImage');
  if (upload) {
    upload.addEventListener('change', function (event) {
      var labels = document.querySelectorAll('LABEL.form-label');
      for (var i = 0; i < labels.length; i++) {
        if ('inputImportersImage' === labels[i].htmlFor) {
          labels[i].innerHTML = event.target.files[0].name;
        }
      }
    });
  }
  </script>

<?php
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
