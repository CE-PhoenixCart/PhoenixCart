<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $rInfo = new objectInfo([]);
  $form = new Form('review', $Admin->link('reviews.php', ['action' => 'add_new']));
  $hook_action = 'formNew';
  $button = new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2');
  $action = 'save';
