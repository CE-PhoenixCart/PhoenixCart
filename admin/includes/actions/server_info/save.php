<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  header('Content-type: text/plain');
  header('Content-disposition: attachment; filename=server_info-' . date('YmdHis') . '.txt');
  
  echo $system_info;
  exit();
