<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($_GET['lngdir'], $_GET['filename'])) {
    $file = DIR_FS_CATALOG_LANGUAGES . $_GET['filename'];

    if (empty($_POST['download']) && file_exists($file) && File::is_writable($file)) {
      file_put_contents($file, $_POST['file_contents']);
    } elseif (Text::is_prefixed_by($_POST['file_contents'], '<?php')) {
      header('Content-type: application/x-octet-stream');
      header('Content-disposition: attachment; filename=' . basename($_GET['filename']));

      echo $_POST['file_contents'];

      exit();
    }

    return $link;
  }
