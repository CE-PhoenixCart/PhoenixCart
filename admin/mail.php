<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  function phoenix_choose_audience($selection) {
    switch ($selection) {
      case '***':
        return TEXT_ALL_CUSTOMERS;
      case '**D':
        return TEXT_NEWSLETTER_CUSTOMERS;
      default:
        if (filter_var($selection, FILTER_VALIDATE_EMAIL)) {
          return $selection;
        }
    }

    if ($GLOBALS['action']) {
      $messageStack->add(sprintf(ERROR_INVALID_EMAIL, htmlspecialchars($selection)), 'error');
      $GLOBALS['action'] = '';
    }
  }

  require 'includes/segments/process_action.php';
  require 'includes/template_top.php';
?>

  <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>

<?php
  if ($view_file = $Admin->locate('/views', $action)) {
    require $view_file;
  }

  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
