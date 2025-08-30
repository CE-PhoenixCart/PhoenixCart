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
    $heading = $oInfo->email_address;
    
    $email_query = \Outgoing::getEmail(['slug' => $oInfo->slug, 'id' => $oInfo->id, 'languages_id' => $oInfo->languages_id]);
    $email = json_decode($email_query, true);

    $modal_title = $email[$oInfo->id]['email']['title'];
    $modal_text  = nl2br($email[$oInfo->id]['email']['text']);

    $modal = <<<eod
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="emailModalLabel">{$modal_title}</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
    
    $contents[] = ['text' => sprintf(TEXT_DATE_ADDED, $oInfo->date_added)];
    if (!Text::is_empty($oInfo->last_modified)) {
      $contents[] = ['text' => sprintf(TEXT_LAST_MODIFIED, $oInfo->last_modified)];
    }
  }
