<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  require language::map_to_translation('specials.php');

  $listing_sql = sprintf(<<<'EOSQL'
SELECT m.*, %s
 FROM
  products_description pd
    INNER JOIN products p ON p.products_id = pd.products_id
    LEFT JOIN manufacturers m ON p.manufacturers_id = m.manufacturers_id
    LEFT JOIN specials s ON p.products_id = s.products_id
    LEFT JOIN (SELECT products_id, COUNT(*) AS attribute_count FROM products_attributes GROUP BY products_id) a ON p.products_id = a.products_id
 WHERE p.products_status = 1 AND s.status = 1 AND pd.language_id = %d
EOSQL
    , Product::COLUMNS, (int)$_SESSION['languages_id']);

  require $Template->map(__FILE__, 'page');

  require 'includes/application_bottom.php';
