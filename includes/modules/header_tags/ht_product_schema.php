<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class ht_product_schema extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_HEADER_TAGS_PRODUCT_SCHEMA_';

    public function __construct() {
      parent::__construct(__FILE__);

      if (static::get_constant('MODULE_HEADER_TAGS_PRODUCT_SCHEMA_PLACEMENT') !== 'Header') {
        $this->group = 'footer_scripts';
      }
    }

    public function execute() {
      global $product;

      if (isset($product) && ($product instanceof Product) && $product->get('status')) {
        $products_image = $product->get('image');

        $schema_product = [
          '@context'    => 'https://schema.org',
          '@type'       => 'Product',
          'name'        => htmlspecialchars($product->get('name')),
          'image'       => $GLOBALS['Linker']->build("images/$products_image", [], false),
          'url'         => $GLOBALS['Linker']->build('product_info.php', ['products_id' => (int)$product->get('id')], false),
          'description' => substr(trim(preg_replace('/\s\s+/', ' ', strip_tags($product->get('description')))), 0, 197) . '...',
        ];

        if (!Text::is_empty($product->get('model') ?? '')) {
          $schema_product['mpn'] = htmlspecialchars($product->get('model'));
        }

        if (!Text::is_empty($product->get('gtin') ?? '') && defined('MODULE_CONTENT_PRODUCT_INFO_GTIN_LENGTH')) {
          $schema_product['gtin' .  MODULE_CONTENT_PRODUCT_INFO_GTIN_LENGTH] = htmlspecialchars(substr($product->get('gtin'), 0-MODULE_CONTENT_PRODUCT_INFO_GTIN_LENGTH));
        }

        $schema_product['offers'] = [
          '@type'         => 'Offer',
          'priceCurrency' => $_SESSION['currency'],
        ];

        $schema_product['offers']['price'] = $product->format_raw();

        if ($product->get('special_expiration')) {
          $schema_product['offers']['priceValidUntil'] = $product->get('special_expiration');
        }

        $availability = ( $product->get('in_stock') > 0 ) ? MODULE_HEADER_TAGS_PRODUCT_SCHEMA_TEXT_IN_STOCK : MODULE_HEADER_TAGS_PRODUCT_SCHEMA_TEXT_OUT_OF_STOCK;
        $schema_product['offers']['availability'] = $availability;

        $schema_product['offers']['seller'] = [
          '@type' => 'Organization',
          'name'  => STORE_NAME,
        ];

        if (($product->get('manufacturers_id') ?? 0) > 0) {
          $schema_product['manufacturer'] = [
            '@type' => 'Organization',
            'name'  => htmlspecialchars($product->get('brand')->getData('manufacturers_name')),
          ];
        }

        if (count($product->get('reviews')) > 0) {
          $schema_product['aggregateRating'] = [
            '@type'       => 'AggregateRating',
            'ratingValue' => number_format($product->get('review_rating'), 2),
            'reviewCount' => (int)count($product->get('reviews')),
          ];

          $schema_product['review'] = [];
          foreach ($product->get('reviews') as $review) {
              $schema_product['review'][] = [
                '@type'         => 'Review',
                'datePublished' => htmlspecialchars($review['date_added']),
                'description'   => htmlspecialchars($review['text']),
                'name'          => htmlspecialchars($product->get('name')),
                'author'  => [
                  '@type' => 'Person',
                  'name'  => htmlspecialchars($review['customers_name']),
                ],
                'reviewRating'  => [
                  '@type'       => 'Rating',
                  'bestRating'  => '5',
                  'ratingValue' => (int)$review['rating'],
                  'worstRating' => '1',
                ],
              ];
          }
        }

        $parameters = ['product_schema' => &$schema_product];
        $GLOBALS['hooks']->cat('injectHtProductSchema', $parameters);

        $data = json_encode($schema_product);

        $GLOBALS['Template']->add_block('<script type="application/ld+json">' . $data . '</script>', $this->group);
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_HEADER_TAGS_PRODUCT_SCHEMA_STATUS' => [
          'title' => 'Enable Product Schema Module',
          'value' => 'True',
          'desc' => 'Do you want to add a product schema to your product page?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_HEADER_TAGS_PRODUCT_SCHEMA_PLACEMENT' => [
          'title' => 'Placement',
          'value' => 'Header',
          'desc' => 'Where should the code be placed?',
          'set_func' => "Config::select_one(['Header', 'Footer'], ",
        ],
        'MODULE_HEADER_TAGS_PRODUCT_SCHEMA_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '950',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }

