<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

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

      $output = null;

      foreach ( $Template->_data[$this->group] as $group ) {
        $output .= '<h2 class="fs-4">' . $group['title'] . '</h2>';
        $output .= '<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4">';

        foreach ( $group['links'] as $entry ) {
          $output .= '<div class="col">';
          
            $output .= '<div class="card text-center pt-4">';
              $output .= '<a class="link-body-emphasis link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover" href="' . $entry['link'] . '">';
                $output .= '<i aria-hidden="true" title="' . $entry['title'] . '" class="' . $entry['icon'] . '"></i>';
                $output .= '<div class="card-body">';
                  $output .= '<p class="card-text">';
                    $output .= $entry['title'];
                  $output .= '</p>';
                $output .= '</div>';
              $output .= '</a>';
            $output .= '</div>';
          $output .= '</div>';
        }

        $output .= '</div>';
      }

      $Template->add_content($output, $this->group);
    }

  }
