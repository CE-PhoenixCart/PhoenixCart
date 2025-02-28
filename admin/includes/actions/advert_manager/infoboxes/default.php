<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($GLOBALS['table_definition']['info']->advert_id)) {
    $aInfo = &$GLOBALS['table_definition']['info'];

    $GLOBALS['link']->set_parameter('aID', $aInfo->advert_id);
    $heading = $aInfo->advert_title;

    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $GLOBALS['link'])->set_parameter('action', 'edit'))
              . $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger', $GLOBALS['link']->set_parameter('action', 'delete'))];

    if (!Text::is_empty($aInfo->advert_url)) {
      if (filter_var($aInfo->advert_url, FILTER_VALIDATE_URL)) {
        $contents[] = ['text' => sprintf(TEXT_ADVERT_EXTERNAL_URL, $aInfo->advert_url)];
      } else {
        parse_str($aInfo->advert_fragment ?? '', $fragment);
        $contents[] = ['text' => sprintf(TEXT_ADVERT_INTERNAL_URL, $GLOBALS['Admin']->catalog($aInfo->advert_url, $fragment))];
      }
    }

    if (!Text::is_empty($aInfo->advert_image)) {
      $contents[] = ['text' => $GLOBALS['Admin']->catalog_image("images/{$aInfo->advert_image}", ['alt' => $aInfo->advert_image])];
    }
    if (!Text::is_empty($aInfo->advert_html_text)) {
      $contents[] = ['text' => $aInfo->advert_html_text];
    }

    if ($aInfo->date_status_change) {
      $contents[] = ['text' => sprintf(TEXT_ADVERT_STATUS_CHANGE, Date::abridge($aInfo->date_status_change))];
    }
  }
