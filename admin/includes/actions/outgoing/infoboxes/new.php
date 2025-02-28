<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $slug_array = \Outgoing::email_dropdown();

  $heading = TEXT_HEADING_NEW_OUTGOING_EMAIL;

  $contents = ['form' => new Form('outgoing', $GLOBALS['Admin']->link()->set_parameter('action', 'insert'), 'post', ['enctype' => 'multipart/form-data'])];
  $contents[] = ['text' => TEXT_NEW_INTRO];

  $contents[] = ['text' => TEXT_OUTGOING_DATE . '<br>' . (new Input('send_at', ['id' => 'sendAtDate'], 'date'))->require()];
  $contents[] = ['text' => TEXT_OUTGOING_SLUG . '<br>' . (new Select('slug', $slug_array))->require()];
  $contents[] = ['text' => TEXT_OUTGOING_CUSTOMER . '<br>' . Customers::select('customer_id')->require()];

  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['link']),
  ];
