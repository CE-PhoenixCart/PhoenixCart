<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $db->query("DELETE FROM configuration WHERE configuration_key = 'DB_LAST_RESTORE'");

  $messageStack->add_session(SUCCESS_LAST_RESTORE_CLEARED, 'success');

  return $Admin->link();
