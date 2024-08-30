<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class product_card {

    public static function inject($parameters) {
      if ($parameters['card']['show_buttons'] ?? false) {
        unset($parameters['buttons']['buy']);
        
        $parameters['buttons']['view'] = new Button(IS_PRODUCT_BUTTON_VIEW, '', 'btn-info btn-product-listing btn-view', [], $parameters['product']->get('link'));
        if (!$parameters['product']->get('has_attributes')) {
          $parameters['buttons']['buy'] = new Button(
            IS_PRODUCT_BUTTON_BUY,
            '',
            'btn-success btn-product-listing btn-buy',
            [],
            $GLOBALS['Linker']->build()->retain_query_except()->set_parameter('action', 'buy_now')->set_parameter('products_id', (int)$parameters['product']->get('id')));
        }
      }
    }

  }
