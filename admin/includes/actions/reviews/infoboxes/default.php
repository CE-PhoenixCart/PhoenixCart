<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($GLOBALS['table_definition']['info']->reviews_id)) {
    $rInfo = &$GLOBALS['table_definition']['info'];
    $heading = $rInfo->products_name;
    $link = (clone $GLOBALS['link'])->set_parameter('rID', (int)$rInfo->reviews_id);

    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $link)->set_parameter('action', 'edit'))
              . $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger', $link->set_parameter('action', 'delete')),
    ];
    $contents[] = ['text' => sprintf(TEXT_INFO_DATE_ADDED, Date::abridge($rInfo->date_added))];
    if (!Text::is_empty($rInfo->last_modified)) {
      $contents[] = ['text' => sprintf(TEXT_INFO_LAST_MODIFIED, Date::abridge($rInfo->last_modified))];
    }
    $contents[] = ['text' => $GLOBALS['Admin']->catalog_image("images/{$rInfo->products_image}", [], $rInfo->products_name)];
    $contents[] = ['text' => sprintf(TEXT_INFO_REVIEW_AUTHOR, $rInfo->customers_name)];
    $contents[] = ['text' => sprintf(TEXT_INFO_REVIEW_RATING, new star_rating((float)$rInfo->reviews_rating))];
    $contents[] = ['text' => sprintf(TEXT_INFO_REVIEW_SIZE, str_word_count($rInfo->reviews_text))];
    $contents[] = ['text' => sprintf(TEXT_INFO_PRODUCTS_AVERAGE_RATING, number_format($rInfo->average_rating, 2))];
  }
