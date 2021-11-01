<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class tp_account {

    public $group = 'account';

    function prepare() {
      global $customer_data;

      $d = &$GLOBALS['Template']->_data[$this->group];

      $d = [
        'account' => [
          'title' => MY_ACCOUNT_TITLE,
          'sort_order' => 10,
          'links' => [
            'edit' => [
              'title' => MY_ACCOUNT_INFORMATION,
              'link' => $GLOBALS['Linker']->build('account_edit.php'),
              'icon' => 'fas fa-user fa-5x',
            ],
          ],
        ],
        'orders' => [
          'title' => MY_ORDERS_TITLE,
          'sort_order' => 20,
          'links' => [
            'history' => [
              'title' => MY_ORDERS_VIEW,
              'link' => $GLOBALS['Linker']->build('account_history.php'),
              'icon' => 'fas fa-shopping-cart fa-5x',
            ],
          ],
        ],
        'notifications' => [
          'title' => EMAIL_NOTIFICATIONS_TITLE,
          'sort_order' => 30,
          'links' => [],
        ],
      ];

      if ($customer_data->has(['address'])) {
        $d['account']['links']['address_book'] = [
          'title' => MY_ACCOUNT_ADDRESS_BOOK,
          'link' => $GLOBALS['Linker']->build('address_book.php'),
          'icon' => 'fas fa-home fa-5x',
        ];
      }

      if ($customer_data->has(['password'])) {
        $d['account']['links']['password'] = [
          'title' => MY_ACCOUNT_PASSWORD,
          'link' => $GLOBALS['Linker']->build('account_password.php'),
          'icon' => 'fas fa-cog fa-5x',
        ];
      }

      if ($customer_data->has(['newsletter'])) {
        $d['notifications']['links']['newsletters'] = [
          'title' => EMAIL_NOTIFICATIONS_NEWSLETTERS,
          'link' => $GLOBALS['Linker']->build('account_newsletters.php'),
          'icon' => 'fas fa-envelope fa-5x',
        ];
      }

      $d['notifications']['links']['products'] = [
        'title' => EMAIL_NOTIFICATIONS_PRODUCTS,
        'link' => $GLOBALS['Linker']->build('account_notifications.php'),
        'icon' => 'fas fa-paper-plane fa-5x',
      ];
    }

    function build() {
      global $Template;

      uasort($Template->_data[$this->group], function (array $a, array $b) {
        return $a['sort_order'] <=> $b['sort_order'];
      });

      $output = '<div class="col-sm-12">';

      foreach ( $Template->_data[$this->group] as $group ) {
        $output .= '<h4>' . $group['title'] . '</h4>';
        $output .= '<div class="list-group list-group-horizontal-sm">';

        foreach ( $group['links'] as $entry ) {
          if (empty($entry['link'])) {
            $output .= '<span class="text-center col-sm-4 col-lg-3 list-group-item list-group-item-action">';
            $close = '</span>';
          } else {
            $output .= '<a class="text-center col-sm-4 col-lg-3 list-group-item list-group-item-action" href="' . $entry['link'] . '">';
            $close = '</a>';
          }

          $output .= '<i title="' . $entry['title'] . '" class="d-none d-sm-block ' . $entry['icon'] . '"></i>';
          $output .= $entry['title'];

          if ('' !== $close) {
            $output .= $close;
          }
        }

        $output .= '</div>';
      }

      $output .= '</div>';

      $Template->add_content($output, $this->group);
    }

  }
