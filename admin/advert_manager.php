<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  $link = $Admin->link();
  if (isset($_GET['page'])) {
    $link->set_parameter('page', (int)$_GET['page']);
  }

  require 'includes/segments/process_action.php';
  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col"><h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1></div>
    <div class="col text-right align-self-center">
      <?=
      empty($action)
      ? $Admin->button(IMAGE_NEW_ADVERT, 'fas fa-pen', 'btn-danger', $Admin->link('advert_manager.php', ['action' => 'new']))
      : $Admin->button(IMAGE_CANCEL, 'fas fa-angle-left', 'btn-light mt-2', $Admin->link())
      ?>
    </div>
  </div>

<?php
  if ($view_file = $Admin->locate('/views', $action)) {
    require $view_file;
  }
?>

  <script>
    var upload = document.querySelector('#advert_image');
    if (upload) {
      upload.addEventListener('change', function (event) {
        var n = this;
        while (n = n.nextElementSibling) {
          if (n.matches('.custom-file-label')) {
            n.innerHTML = event.target.files[0].name;
          }
        }
      });
    }
  </script>

<?php
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
