<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($table_definition['info']->id)) {
    $oInfo = &$table_definition['info'];
    $link = $GLOBALS['link']->set_parameter('oID', (int)$oInfo->id);
    $heading = $oInfo->title;

    $modal_title = $oInfo->title;
    $modal_text  = nl2br($oInfo->text);
    $modal = <<<eod
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="emailModalLabel">{$modal_title}</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {$modal_text}
      </div>
    </div>
  </div>
</div>
eod;

    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $link)->set_parameter('action', 'edit'))
              . $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger', (clone $link)->set_parameter('action', 'delete')),
    ];

    $contents[] = [
      'class' => 'd-grid',
      'text' => $modal . '<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#emailModal">' . IMAGE_VIEW_EMAIL . '</button>'];
      
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
    
    if (!Text::is_empty($oInfo->last_modified)) $contents[] = ['text' => sprintf(TEXT_LAST_MODIFIED, $oInfo->last_modified)];
  }