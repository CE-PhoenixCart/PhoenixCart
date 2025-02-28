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

  $contents = ['form' => new Form('outgoing', (clone $link)->set_parameter('action', 'save'), 'post', ['enctype' => 'multipart/form-data'])];
  $contents[] = ['text' => TEXT_EDIT_INTRO];
  
  $send_at_date = substr($oInfo->send_at ?? '', 0, 10);

  $contents[] = ['text' => TEXT_OUTGOING_DATE . '<br>' . new Input('send_at', ['value' => $send_at_date], 'date')];
  $contents[] = ['text' => TEXT_OUTGOING_SLUG . '<br>' . new Input('slug', ['value' => $oInfo->slug])];
  $contents[] = ['text' => TEXT_OUTGOING_EMAIL . '<br>' . new Input('email_address', ['value' => $oInfo->email_address])];
  $contents[] = ['text' => TEXT_OUTGOING_MERGE_TAGS . (new Textarea('text', ['cols' => '80', 'rows' => '10']))->set_text($oInfo->merge_tags)];

  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
  ];
