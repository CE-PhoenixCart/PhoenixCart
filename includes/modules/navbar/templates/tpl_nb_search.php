<?php
$form = new Form('quick_find', $GLOBALS['Linker']->build('advanced_search_result.php')->set_include_session(false), 'get', ['class' => 'form-inline']);
$form->hide_session_id()->hide('search_in_description', '0');

$form_output = "$form";
  $form_output .= '<div class="input-group">';
    $form_output .= (new Input('keywords', ['autocomplete' => 'off', 'placeholder' => TEXT_SEARCH_PLACEHOLDER, 'aria-label' => MODULE_NAVBAR_SEARCH_ARIA_LABEL], 'search'))->require();
    $form_output .= '<button type="submit" class="btn btn-secondary btn-sm btn-search">' . MODULE_NAVBAR_SEARCH_SEARCH_TEXT . '</button>';
  $form_output .= '</div>';
$form_output .= '</form>';
      
echo $form_output;

/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2026 Phoenix Cart

  Released under the GNU General Public License
*/
?>
