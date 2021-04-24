<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  abstract class abstract_customer_data_module extends abstract_module {

    const REQUIRED_ATTRIBUTE = 'required aria-required="true" ';
    const REQUIRED_ATTRIBUTES = [
      'required' => null,
      'aria-required' => 'true',
    ];

    protected $pages;

    public function __construct() {
      parent::__construct();

      $pages_string = $this->base_constant('PAGES');
      $this->pages = empty($pages_string) ? [] : explode(';', $pages_string);
    }

    public function get_group() {
      return $this->base_constant('GROUP');
    }

    public function get_template() {
      return $this->base_constant('TEMPLATE');
    }

    public function has_page($page) {
      return in_array($page, $this->pages);
    }

    public function is_required() {
      return (('True' === $this->base_constant('REQUIRED'))
        && ('customers.php' !== $GLOBALS['PHP_SELF']));
    }

  }
