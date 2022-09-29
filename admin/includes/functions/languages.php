<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  function tep_get_languages_directory($code) {
    trigger_error('The tep_get_languages_directory function has been deprecated.', E_USER_DEPRECATED);

    $language_query = $GLOBALS['db']->query("SELECT languages_id, directory FROM languages WHERE code = '" . $GLOBALS['db']->escape($code) . "'");
    if (mysqli_num_rows($language_query)) {
      $language = $language_query->fetch_assoc();
      $_SESSION['languages_id'] = $language['languages_id'];
      return $language['directory'];
    } else {
      return false;
    }
  }
