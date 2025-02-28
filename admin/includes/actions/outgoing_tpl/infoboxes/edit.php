<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $oInfo = &$table_definition['info'];
  $heading = TEXT_HEADING_EDIT_OUTGOING_EMAIL;
  $link = $GLOBALS['link']->set_parameter('oID', (int)$oInfo->id);

  $contents = ['form' => new Form('outgoing_tpl', (clone $link)->set_parameter('action', 'save'), 'post', ['enctype' => 'multipart/form-data'])];
  $contents[] = ['text' => TEXT_EDIT_INTRO];

  $contents[] = ['text' => TEXT_OUTGOING_SLUG . '<br>' . new Input('slug', ['value' => $oInfo->slug, 'readonly' => 'readonly'])];
  $contents[] = ['text' => TEXT_OUTGOING_SLUG_TITLE . '<br>' . new Input('title', ['value' => $oInfo->title])];
  $contents[] = ['text' => TEXT_OUTGOING_SLUG_TEXT . (new Textarea('text', ['cols' => '80', 'rows' => '10']))->set_text($oInfo->text)];
  
  $merged_tags = $GLOBALS['available_merge_tags'];
     
  include_once(DIR_FS_CATALOG . 'includes/modules/outgoing/' . $oInfo->slug . '.php');
  $merge_tags = call_user_func(array('Outgoing_' . $oInfo->slug, 'merge_tags'));

  foreach ($merge_tags[$oInfo->slug] as $nn => $mm) {
    $merged_tags[$nn] = $mm;
  }

  $slug_tags = null;
  foreach ($merged_tags as $am => $bm) {
    $slug_tags .= "<p><b>$am</b> - $bm</p>";
  }
  
  $contents[] = ['text' => $slug_tags];
    
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
  ];
