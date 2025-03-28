<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = TEXT_INFO_HEADING_NEW_BACKUP;

  $contents = ['form' => new Form('backup', (clone $GLOBALS['link'])->set_parameter('action', 'backup_now'))];
  $contents[] = ['text' => TEXT_INFO_NEW_BACKUP];

  $contents[] = [
    'text' => '<div class="form-check">'
            . (new Tickable('compress', ['value' => 'no', 'id' => 'cNo', 'class' => 'form-check-input'], 'radio'))->tick()
            . '<label class="form-check-label" for="cNo"><small>' . TEXT_INFO_USE_NO_COMPRESSION . '</small></label></div>',
  ];
  if (file_exists(LOCAL_EXE_GZIP)) {
    $contents[] = [
      'text' => '<div class="form-check">'
              . new Tickable('compress', ['value' => 'gzip', 'id' => 'cGzip', 'class' => 'form-check-input'], 'radio')
              . '<label class="form-check-label" for="cGzip"><small>' . TEXT_INFO_USE_GZIP . '</small></label></div>',
    ];
  }
  if (file_exists(LOCAL_EXE_ZIP)) {
    $contents[] = [
      'text' => '<div class="form-check">'
              . new Tickable('compress', ['value' => 'zip', 'id' => 'czip', 'class' => 'form-check-input'], 'radio')
              . '<label class="form-check-label" for="czip"><small>' . TEXT_INFO_USE_ZIP . '</small></label></div>',
    ];
  }

  if ($GLOBALS['dir_ok']) {
    $contents[] = [
      'text' => '<div class="form-check form-switch">'
              . new Tickable('download', ['value' => 'yes', 'class' => 'form-check-input', 'id' => 'd'], 'checkbox')
              . '<label for="d" class="form-check-label text-muted"><small>' . TEXT_INFO_DOWNLOAD_ONLY . '<br>' . TEXT_INFO_BEST_THROUGH_HTTPS . '</small></label></div>',
    ];
  } else {
    $contents[] = [
      'text' => '<div class="form-check">'
              . (new Tickable('download', ['value' => 'yes', 'id' => 'd', 'class' => 'form-check-input'], 'radio'))->tick()
              . '<label class="form-check-label" for="d"><small>' . TEXT_INFO_DOWNLOAD_ONLY . '<br>' . TEXT_INFO_BEST_THROUGH_HTTPS . '</small></label></div>',
    ];
  }

  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_BACKUP, 'fas fa-download', 'btn-warning me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['Admin']->link()),
  ];
