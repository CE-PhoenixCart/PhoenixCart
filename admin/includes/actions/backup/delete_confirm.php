<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $source = Path::normalize(DIR_FS_BACKUP . $_GET['file']);
  if (Text::is_prefixed_by($source, DIR_FS_BACKUP) && Path::remove($source)) {
    $messageStack->add_session(SUCCESS_BACKUP_DELETED, 'success');

    return $Admin->link();
  } else {
    error_log(sprintf('Could not delete [%s] from [%s]', $source, DIR_FS_BACKUP));
    $messageStack->add(sprintf(ERROR_PATH_NOT_REMOVEABLE, $_GET['file']), 'error');
  }
