<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  System::set_time_limit(0);

  $sql_file = new sql_file($_GET['file'], DIR_FS_BACKUP);
  $sql_file->decompress_and_restore();

  return $Admin->link();
