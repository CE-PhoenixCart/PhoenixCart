<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($_POST['categories_id']) && ($_POST['categories_id'] != $_POST['move_to_category_id'])) {
    $categories_id = Text::input($_POST['categories_id']);
    $new_parent_id = Text::input($_POST['move_to_category_id']);

    $path = Guarantor::ensure_global('category_tree')->get_ancestors($new_parent_id);

    if (in_array($categories_id, $path)) {
      $messageStack->add_session(ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');

      Href::redirect($Admin->link('catalog.php', ['cPath' => $cPath, 'cID' => $categories_id]));
    } else {
      $db->query("UPDATE categories SET parent_id = " . (int)$new_parent_id . ", last_modified = NOW() WHERE categories_id = " . (int)$categories_id);
      $path = array_reverse($path);
      $path[] = $new_parent_id;

      return $Admin->link('catalog.php', ['cPath' => implode('_', $path), 'cID' => $categories_id]);
    }
  }
