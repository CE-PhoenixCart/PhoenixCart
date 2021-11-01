<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

ob_start();
include Guarantor::ensure_global('Template')->map($tpl_data['file'], $tpl_data['type'] ?? 'module');

$GLOBALS['Template']->add_block(ob_get_clean(), $tpl_data['group']);
