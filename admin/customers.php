<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $always_valid_actions = ['edit'];
  require 'includes/application_top.php';

  if (!$customer_data->has([ 'sortable_name', 'name', 'email_address', 'country_id', 'id' ])) {
    $messageStack->add_session(ERROR_PAGE_HAS_UNMET_REQUIREMENT, 'error');
    foreach ($customer_data->get_last_missing_abilities() as $missing_ability) {
      $messageStack->add_session($missing_ability);
    }

    Href::redirect($Admin->link('modules.php', ['set' => 'customer_data']));
  }

  require 'includes/segments/process_action.php';

  require 'includes/template_top.php';
  ?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col text-right align-self-center">
      <?=
      isset($_GET['action'])
      ? ''
      : (new Form('search', $Admin->link('customers.php'), 'get'))->hide_session_id()
        . '<div class="input-group mt-0">'
          . '<div class="input-group-prepend">'
            . '<span class="input-group-text">' . HEADING_TITLE_SEARCH . '</span>'
          . '</div>'
          . new Input('search')
        . '</div>'
      . '</form>'
      ?>
    </div>
    <div class="col-12 col-lg-4 text-left text-lg-right align-self-center pb-1">
      <?= 
      $Admin->button('<img src="images/icon_phoenix.png" class="mr-2">' . GET_HELP, '', 'btn-dark', GET_HELP_LINK, ['newwindow' => true]),
      isset($_GET['action']) ? $Admin->button(IMAGE_CANCEL, 'fas fa-angle-left', 'ml-2 btn-light', $Admin->link('customers.php')->retain_query_except(['action'])) : ''; 
      ?>
    </div>
  </div>

  <?php
  if ($view_file = $Admin->locate('/views', $action)) {
    require $view_file;
  }

  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
