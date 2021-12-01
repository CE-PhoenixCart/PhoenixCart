<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class ht_opensearch extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_HEADER_TAGS_OPENSEARCH_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      $GLOBALS['Template']->add_block(
        '<link rel="search" type="application/opensearchdescription+xml" href="'
        . $GLOBALS['Linker']->build('opensearch.php', [], false)
        . '" title="' . Text::output(STORE_NAME) . '" />', $this->group);
    }

    protected function get_parameters() {
      return [
        'MODULE_HEADER_TAGS_OPENSEARCH_STATUS' => [
          'title' => 'Enable OpenSearch Module',
          'value' => 'True',
          'desc' => 'Add shop search functionality to the browser?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_HEADER_TAGS_OPENSEARCH_SITE_SHORT_NAME' => [
          'title' => 'Short Name',
          'value' => $GLOBALS['db']->escape(STORE_NAME),
          'desc' => 'Short name to describe the search engine.',
        ],
        'MODULE_HEADER_TAGS_OPENSEARCH_SITE_DESCRIPTION' => [
          'title' => 'Description',
          'value' => 'Search ' . $GLOBALS['db']->escape(STORE_NAME),
          'desc' => 'Description of the search engine.',
        ],
        'MODULE_HEADER_TAGS_OPENSEARCH_SITE_CONTACT' => [
          'title' => 'Contact',
          'value' => $GLOBALS['db']->escape(STORE_OWNER_EMAIL_ADDRESS) ,
          'desc' => 'E-Mail address of the search engine maintainer. (optional)',
        ],
        'MODULE_HEADER_TAGS_OPENSEARCH_SITE_TAGS' => [
          'title' => 'Tags',
          'value' => '',
          'desc' => 'Keywords to identify and categorize the search content, separated by an empty space. (optional)',
        ],
        'MODULE_HEADER_TAGS_OPENSEARCH_SITE_ATTRIBUTION' => [
          'title' => 'Attribution',
          'value' => 'Copyright (c) ' . $GLOBALS['db']->escape(STORE_NAME),
          'desc' => 'Attribution for the search content. (optional)',
        ],
        'MODULE_HEADER_TAGS_OPENSEARCH_SITE_ADULT_CONTENT' => [
          'title' => 'Adult Content',
          'value' => 'False',
          'desc' => 'Search content contains material suitable only for adults.',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_HEADER_TAGS_OPENSEARCH_SITE_ICON' => [
          'title' => '16x16 Icon',
          'value' => (defined('HTTP_CATALOG_SERVER') ? HTTP_CATALOG_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . 'favicon.ico',
          'desc' => 'A 16x16 sized icon (must be in .ico format, eg http://server/favicon.ico). (optional)',
        ],
        'MODULE_HEADER_TAGS_OPENSEARCH_SITE_IMAGE' => [
          'title' => '64x64 Image',
          'value' => '',
          'desc' => 'A 64x64 sized image (must be in .png format, eg http://server/images/logo.png). (optional)',
        ],
        'MODULE_HEADER_TAGS_OPENSEARCH_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
