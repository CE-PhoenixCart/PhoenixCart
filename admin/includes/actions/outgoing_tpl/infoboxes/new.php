<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $slug_array = [];
  $slug_array[] = ['id'   => '', 'text' => SLUG_SELECT];
  $available_slugs = glob(DIR_FS_CATALOG . 'includes/modules/outgoing/*.php');
  foreach ($available_slugs as $as) {
    $slug_array[] = ['id'   => basename($as, '.php'), 'text' => basename($as, '.php')];
  }

  $heading = HEADING_NEW_SLUG;

  $contents = ['form' => new Form('outgoing_tpl', $GLOBALS['Admin']->link()->set_parameter('action', 'insert'), 'post', ['enctype' => 'multipart/form-data'])];
  $contents[] = ['text' => TEXT_NEW_INTRO];

  $contents[] = ['text' => TEXT_OUTGOING_SLUG . '<br>' . (new Select('slug', $slug_array))->require()];
  $contents[] = ['text' => TEXT_OUTGOING_SLUG_TITLE . '<br>' . (new Input('title'))->require()];
  $contents[] = ['text' => TEXT_OUTGOING_SLUG_TEXT . (new Textarea('text', ['cols' => '80', 'rows' => '10']))->require()];

  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['link']),
  ];
  