<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE_1, $Linker->build('account.php'));
  $breadcrumb->add(NAVBAR_TITLE_2, $Linker->build('account_password.php'));

  $form = new Form('account_password', $Linker->build());
  $form->hide('action', 'process');

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4"><?= HEADING_TITLE ?></h1>

<?php
  if ($messageStack->size($message_stack_area) > 0) {
    echo $messageStack->output($message_stack_area);
  }

  echo $form;
  echo new Input('username', ['value' => $customer->get('username'), 'readonly' => null, 'autocomplete' => 'username'], 'hidden');
?>

  <p class="text-danger text-right"><?= FORM_REQUIRED_INFORMATION ?></p>

  <?php
  $input_id = 'inputCurrent';
  $label_text = ENTRY_PASSWORD_CURRENT;
  $input = (new Input('password_current', [
    'autofocus' => 'autofocus',
    'autocapitalize' => 'none',
    'id' => $input_id,
    'autocomplete' => 'current-password',
    'placeholder' => ENTRY_PASSWORD_CURRENT_TEXT,
  ], 'password'))->require() . FORM_REQUIRED_INPUT;

  include $Template->map('includes/modules/customer_data/cd_whole_row_input.php');

  $customer_data->display_input($page_fields);
?>

  <div class="buttonSet">
    <div class="text-right"><?= new Button(IMAGE_BUTTON_CONTINUE, 'fas fa-angle-right', 'btn-success btn-lg btn-block') ?></div>
    <p><?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', '', [], $Linker->build('account.php')) ?></p>
  </div>

</form>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
