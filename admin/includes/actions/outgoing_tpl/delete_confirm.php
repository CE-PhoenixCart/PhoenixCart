<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $id = (int)$_GET['oID'];

  $db->query("DELETE FROM outgoing_tpl WHERE id = " . $id);
  
  if (isset($_POST['delete_queued']) && ($_POST['delete_queued'] == 'on')) {
    $del = $_POST['slugworth'];
    
    $db->query("delete from outgoing where slug = '" . $del . "'");
  }

  return $link;
