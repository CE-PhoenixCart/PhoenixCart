<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $link = $GLOBALS['Admin']->link('customers.php')->retain_query_except(['action'])->set_parameter('cID', (int)$table_definition['info']->id);
  $heading = TEXT_INFO_HEADING_DELETE_CUSTOMER;

  $contents = ['form' => new Form('customers', $link->set_parameter('action', 'delete_confirm'))];
  $contents[] = ['text' => TEXT_DELETE_INTRO . '<br><br><strong>' . $table_definition['info']->name . '</strong>'];
  if (isset($table_definition['info']->number_of_reviews) && ($table_definition['info']->number_of_reviews > 0)) {
    $contents[] = [
      'text' => '<div class="form-check form-switch">'
              . (new Tickable('delete_reviews', ['value' => 'on', 'class' => 'form-check-input', 'id' => 'cDeleteReview'], 'checkbox'))->tick()
              . '<label for="cDeleteReview" class="form-check-label text-muted"><small>'
              . sprintf(TEXT_DELETE_REVIEWS, $table_definition['info']->number_of_reviews)
              . '</small></label></div>',
    ];
  }
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link->delete_parameter('action')),
  ];
