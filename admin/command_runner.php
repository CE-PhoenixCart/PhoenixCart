<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2026 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';
  require 'includes/command/dispatcher.php';

  $cmd = $_GET['cmd'] ?? '';

  if ($cmd === '') {
    exit;
  }

  admin_command_dispatch($cmd);

  exit;
  