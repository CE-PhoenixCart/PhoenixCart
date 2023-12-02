<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

// needs to be included earlier to set the success message in the messageStack
  require language::map_to_translation('create_account.php');

  $message_stack_area = 'create_account';

  $page_fields = $customer_data->get_fields_for_page('create_account');
  $customer_details = null;
  if (Form::validate_action_is('process')) {
    $customer_details = $customer_data->process($page_fields);

    $hooks->cat('injectFormVerify');

    if (Form::is_valid()) {
      $customer_data->create($customer_details);

      $hooks->cat('postRegistration');
    }
  }

  $grouped_modules = $customer_data->get_grouped_modules();
  $customer_data_group_query = $db->query(sprintf(<<<'EOSQL'
SELECT *
 FROM customer_data_groups
 WHERE language_id = %d
 ORDER BY cdg_vertical_sort_order
EOSQL
    , (int)$_SESSION['languages_id']));

  require $Template->map(__FILE__, 'page');

  require 'includes/application_bottom.php';
