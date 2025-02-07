<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class newsletter {

    const REQUIRES = [
      'name',
      'email_address',
      'newsletter',
    ];

    public $show_choose_audience = false;
    public $title, $content;

    public function __construct($title, $content) {
      $this->title = $title;
      $this->content = $content;
    }

    public function choose_audience() {
      return false;
    }

    public function confirm() {
      if (!$GLOBALS['customer_data']->has(static::REQUIRES)) {
        return '';
      }

      $confirm_string = '<div class="alert alert-danger">' . sprintf(TEXT_COUNT_CUSTOMERS, $GLOBALS['customer_data']->count_by_criteria(['newsletter' => 1])) . '</div>' . "\n";

      $confirm_string .= '<table class="table table-striped">' . "\n";
      $confirm_string .= '  <tr>' . "\n";
      $confirm_string .= '    <th scope="row">' . TEXT_TITLE . '</th>' . "\n";
      $confirm_string .= '    <td>' . $this->title . '</td>' . "\n";
      $confirm_string .= '  </tr>' . "\n";
      $confirm_string .= '  <tr>' . "\n";
      $confirm_string .= '    <th scope="row">' . TEXT_CONTENT . '</th>' . "\n";
      $confirm_string .= '    <td>' . $this->content . '</td>' . "\n";
      $confirm_string .= '  </tr>' . "\n";
      $confirm_string .= '</table>' . "\n";

      $GLOBALS['link']->set_parameter('nID', (int)$_GET['nID']);
      $confirm_string .= '<div class="d-grid mt-2">';
      $confirm_string .= $GLOBALS['Admin']->button(IMAGE_SEND, 'fas fa-paper-plane', 'btn-success', (clone $GLOBALS['link'])->set_parameter('action', 'confirm_send'));
      $confirm_string .= '</div>' . "\n";
      $confirm_string .= $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-angle-left', 'btn-light mt-1', $GLOBALS['link']);
      return $confirm_string;
    }

    public function send(int $newsletter_id) {
      if ($GLOBALS['customer_data'] instanceof customer_data) {
        $customer_data = &$GLOBALS['customer_data'];
      } else {
        $customer_data = new customer_data();
      }

      $mail_query = $GLOBALS['db']->query($customer_data->build_read(['name', 'email_address'], 'customers', ['newsletter' => 1]));

      $mimemessage = new email();
      $mimemessage->add_message($this->content);
      $mimemessage->build_message();
      while ($mail = $mail_query->fetch_assoc()) {
        $mimemessage->send($customer_data->get('name', $mail), $customer_data->get('email_address', $mail), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $this->title);
      }

      $newsletter_id = Text::input($newsletter_id);
      $GLOBALS['db']->query("UPDATE newsletters SET date_sent = NOW(), status = 1 WHERE newsletters_id = " . (int)$newsletter_id);
    }

  }

