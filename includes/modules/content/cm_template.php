<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  ob_start();
  include(Guarantor::ensure_global('Template')->map($tpl_data['file']));

  $GLOBALS['Template']->add_content(ob_get_clean(), $tpl_data['group']);
