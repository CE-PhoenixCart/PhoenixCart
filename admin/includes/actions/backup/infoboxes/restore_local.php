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
  $contents[] = [
    'text' => '<div class="custom-file mb-2">'
            . (new Input('sql_file', ['id' => 'upload', 'class' => 'custom-file-input'], 'file'))->require()
            . '<label class="custom-file-label" for="upload">&nbsp;</label></div>',
  ];
  $contents[] = ['text' => TEXT_INFO_RESTORE_LOCAL_RAW_FILE];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_RESTORE, 'fas fa-file-upload', 'btn-warning mr-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['link']),
  ];
