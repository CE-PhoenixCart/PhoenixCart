<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = TEXT_INFO_HEADING_RESTORE_LOCAL;

  $contents = ['form' => new Form('restore', (clone $GLOBALS['link'])->set_parameter('action', 'restore_local_now'), 'post', ['enctype' => 'multipart/form-data'])];
  $contents[] = ['text' => TEXT_INFO_RESTORE_LOCAL . '<br><br>' . TEXT_INFO_BEST_THROUGH_HTTPS];
  $contents[] = ['text' => (new Input('sql_file', ['accept' => '.sql', 'id' => 'upload', 'class' => 'form-control'], 'file'))->require() . '<label class="form-label" for="upload">&nbsp;</label>'];
  $contents[] = ['text' => TEXT_INFO_RESTORE_LOCAL_RAW_FILE];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_RESTORE, 'fas fa-file-upload', 'btn-warning me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['link']),
  ];
