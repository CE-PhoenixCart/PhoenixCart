<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  System::set_time_limit(0);

  $sql_file = new upload('sql_file', DIR_FS_BACKUP);
  $sql_file->set_extensions(['sql']);

  if ($sql_file->parse()) {
    $pathinfo = pathinfo($sql_file->tmp_filename);
    $sql_runner = new sql_file($pathinfo['basename'], $pathinfo['dirname'], $sql_file->filename);
    $sql_runner->restore();
  }

  return $Admin->link();
