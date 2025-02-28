<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($GLOBALS['table_definition']['info']->testimonials_id)) {
    $tInfo = &$GLOBALS['table_definition']['info'];

    $GLOBALS['link']->set_parameter('tID', $tInfo->testimonials_id);
    $heading = $tInfo->customers_name;

    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $GLOBALS['link'])->set_parameter('action', 'edit'))
              . $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger', $GLOBALS['link']->set_parameter('action', 'delete'))];
    $contents[] = ['text' => sprintf(TEXT_INFO_DATE_ADDED, Date::abridge($tInfo->date_added))];
    if (!Text::is_empty($tInfo->last_modified)) {
      $contents[] = ['text' => sprintf(TEXT_INFO_LAST_MODIFIED, Date::abridge($tInfo->last_modified))];
    }
    $contents[] = ['text' => sprintf(TEXT_INFO_TESTIMONIAL_AUTHOR, $tInfo->customers_name)];
    $contents[] = ['text' => sprintf(TEXT_INFO_TESTIMONIAL_SIZE, str_word_count($tInfo->testimonials_text))];
  }
