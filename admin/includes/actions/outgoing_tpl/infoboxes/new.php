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
  
  $slug_title_string = $slug_text_string = '';
  foreach (language::load_all() as $l) {
    $flag_image = $GLOBALS['Admin']->catalog_image("includes/languages/{$l['directory']}/images/{$l['image']}", [], $l['name']);
    
    $slug_title_string .= '<div class="input-group mb-1"><span class="input-group-text">' . $flag_image . '</span>' . new Input("title[{$l['id']}]", ['id' => "oTitle-{$l['code']}"]) . '</div>';
    $slug_text_string .= '<div class="input-group mb-1"><span class="input-group-text">' . $flag_image . '</span>' . new Textarea("text[{$l['id']}]", ['cols' => '80', 'rows' => '10', 'id' => "oText-{$l['code']}"]) . '</div>';
  }
  
  $contents[] = ['text' => TEXT_OUTGOING_SLUG_TITLE . $slug_title_string];
  $contents[] = ['text' => TEXT_OUTGOING_SLUG_TEXT . $slug_text_string];

  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['link']),
  ];
  