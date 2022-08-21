<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $extension = substr($_GET['file'], -3);
  switch (substr($_GET['file'], -3)) {
    case 'zip':
    case '.gz':
    case 'sql':
      $path = Path::normalize(realpath(DIR_FS_BACKUP . $_GET['file']));
      if (Text::is_prefixed_by($path, DIR_FS_BACKUP) && ($buffer = file_get_contents($path))) {
        header('Content-type: application/x-octet-stream');
        header('Content-disposition: attachment; filename=' . $_GET['file']);

        echo $buffer;

        exit();
      } else {
        error_log("Bad file [{$_GET['file']}] requested.");
      }
    default:
      $messageStack->add(ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE, 'error');
  }
