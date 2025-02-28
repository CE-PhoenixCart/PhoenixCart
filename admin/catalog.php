<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $always_valid_actions = ['set_flag'];
  require 'includes/application_top.php';

  $currencies = new currencies();

  // calculate category path
  if (empty($_GET['cPath'])) {
    $current_category_id = 0;
    $cPath = '';
  } else {
    $cPath_array = array_unique(
      array_map('intval', explode('_', $_GET['cPath'])),
      SORT_NUMERIC);
    $cPath = implode('_', $cPath_array);
    $current_category_id = end($cPath_array);
  }

  const DIR_FS_CATALOG_IMAGES = DIR_FS_CATALOG . 'images/';

  require 'includes/segments/process_action.php';

// check if the catalog image directory exists
  if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!Path::is_writable(DIR_FS_CATALOG_IMAGES)) {
      $messageStack->add(sprintf(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, DIR_FS_CATALOG_IMAGES), 'error');
    }
  } else {
    $messageStack->add(sprintf(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, DIR_FS_CATALOG_IMAGES), 'error');
  }

  require 'includes/template_top.php';

  $base_url = HTTP_SERVER . DIR_WS_ADMIN;

  if ($view_file = $Admin->locate('/views', $action)) {
    require $view_file;
  }
?>

<script>
document.addEventListener('change', function(event) {
  var target = event.target;

  if (target.id === 'cImg' || target.id.startsWith('pImg')) {
    var fileName = target.files[0]?.name || 'No file chosen';
    var nextElement = target.nextElementSibling;

    if (nextElement && nextElement.classList.contains('form-label')) {
      nextElement.textContent = fileName;
    }
  }
});
</script>

<?php
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
