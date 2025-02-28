<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  class product_searcher extends searcher {

    public function find() {
      $chain = [
        'db_tables' => array_merge(
          $this->db_tables,
          ['manufacturers' => [], 'products' => [], 'products_description' => [], 'specials' => []]),
        'criteria' => array_merge($this->criteria, [
          'products' => ['products_status' => 1],
          'products_description' => ['language_id' => (int)$_SESSION['languages_id']],
        ]),
        'columns' => Product::COLUMNS,
        'custom' => [
          'select' => '',
          'from' => '',
          'where' => '',
        ],
      ];
      $chain = $GLOBALS['hooks']->chain('productListing', $chain);
      return products_query::build_read($chain['db_tables'], $chain['criteria'], $chain);
    }

    public static function hook($chain) {
      if (Guarantor::ensure_global('currencies')->is_set($_SESSION['currency'])) {
        $rate = $GLOBALS['currencies']->get_value($_SESSION['currency']);
        if (!Text::is_empty($GLOBALS['pfrom'])) {
          $GLOBALS['pfrom'] /= $rate;
        }

        if (!Text::is_empty($GLOBALS['pto'])) {
          $GLOBALS['pto'] /= $rate;
        }
      }

      $custom = &Guarantor::guarantee_subarray($chain, 'custom');
      $criteria = &Guarantor::guarantee_subarray($chain, 'criteria');

      if (isset($_GET['categories_id']) && !Text::is_empty($_GET['categories_id'])) {
        if (empty($_GET['inc_subcat']) || !($descendants = Guarantor::ensure_global('category_tree')->get_descendants($_GET['categories_id']))) {
          $criteria['products_to_categories'] = ['categories_id' => (int)$_GET['categories_id']];
        } else {
          $criteria['products_to_categories'] =  ['categories_id' => [(int)$_GET['categories_id'], ...array_map('intval', $descendants)]];
        }
      }

      if (isset($_GET['manufacturers_id']) && !Text::is_empty($_GET['manufacturers_id'])) {
        $criteria['manufacturers'] = ['manufacturers_id' => (int)$_GET['manufacturers_id']];
      }

      if (!empty($GLOBALS['search_keywords'])) {
        $custom['where'] .= " AND (";
        foreach ($GLOBALS['search_keywords'] as $search_keyword) {
          switch ($search_keyword) {
            case '(':
            case ')':
            case 'and':
            case 'or':
              $custom['where'] .= " " . $search_keyword . " ";
              break;
            default:
              $keyword = $GLOBALS['db']->escape(Text::input($search_keyword));
              $custom['where'] .= "(pd.products_name LIKE '%" . $keyword . "%' OR p.products_model LIKE '%" . $keyword . "%' OR m.manufacturers_name LIKE '%" . $keyword . "%'";
              if ( (defined('MODULE_HEADER_TAGS_PRODUCT_META_KEYWORDS_STATUS')) && (MODULE_HEADER_TAGS_PRODUCT_META_KEYWORDS_STATUS == 'True') ) {
                $custom['where'] .= " OR pd.products_seo_keywords LIKE '%" . $keyword . "%'";
              }
              if (isset($_GET['search_in_description']) && ($_GET['search_in_description'] == '1')) {
                $custom['where'] .= " OR pd.products_description LIKE '%" . $keyword . "%'";
              }
              $custom['where'] .= ')';
              break;
          }
        }
        $custom['where'] .= " )";
      }

      if (DISPLAY_PRICE_WITH_TAX === 'true') {
        if (!Text::is_empty($GLOBALS['pfrom']) || !Text::is_empty($GLOBALS['pto'])) {
          $chain['columns'] .= ", tax.tax_rate";

          if (isset($customer) && ($customer instanceof customer)) {
            $country_id = $customer->get('country_id');
            $zone_id = $customer->get('zone_id');
          } else {
            $country_id = STORE_COUNTRY;
            $zone_id = STORE_ZONE;
          }

          $custom['from'] .= sprintf(<<<'EOSQL'
            LEFT JOIN (SELECT tlog.tax_class_id, COALESCE((EXP(SUM(tlog.log_tax_rate)), 1.0) - 1.0) * 100 AS tax_rate FROM (
                SELECT tr.tax_class_id, LOG(1.0 + SUM(tr.tax_rate) / 100) AS log_tax_rate FROM tax_rates tr
                LEFT JOIN zones_to_geo_zones gz ON tr.tax_zone_id = gz.geo_zone_id
                        AND (gz.zone_country_id IS NULL OR gz.zone_country_id IN (0, %d))
                        AND (gz.zone_id IS NULL OR gz.zone_id IN (0, %d))
                GROUP BY tr.tax_class_id, tr.tax_priority
              ) tlog
              GROUP BY tlog.tax_class_id
            ) tax ON p.products_tax_class_id = tax.tax_class_id
EOSQL
          , (int)$country_id, (int)$zone_id);

          if ($GLOBALS['pfrom'] > 0) {
            $custom['where'] .= " AND (IF(s.status, s.specials_new_products_price, p.products_price) * (1.0 + (tax.tax_rate / 100) ) >= " . (double)$GLOBALS['pfrom'] . ")";
          }
          if ($GLOBALS['pto'] > 0) {
            $custom['where'] .= " AND (IF(s.status, s.specials_new_products_price, p.products_price) * (1.0 + (tax.tax_rate / 100) ) <= " . (double)$GLOBALS['pto'] . ")";
          }
        }
      } else {
        if ($GLOBALS['pfrom'] > 0) {
          $custom['where'] .= " AND IF(s.status, s.specials_new_products_price, p.products_price) >= " . (double)$GLOBALS['pfrom'];
        }
        if ($GLOBALS['pto'] > 0) {
          $custom['where'] .= " AND IF(s.status, s.specials_new_products_price, p.products_price) <= " . (double)$GLOBALS['pto'];
        }
      }

      return $chain;
    }

  }
