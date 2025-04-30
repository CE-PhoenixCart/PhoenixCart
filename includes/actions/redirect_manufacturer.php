<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  namespace Phoenix\Actions;

  class redirect_manufacturer {
    public static function execute() {
      if (isset($_GET['manufacturers_id']) && !\Text::is_empty($_GET['manufacturers_id'])) {
        $manufacturer_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT manufacturers_url FROM manufacturers_info WHERE manufacturers_id = %d AND languages_id = %d
EOSQL
          , (int)$_GET['manufacturers_id'], (int)$_SESSION['languages_id']));
        if (mysqli_num_rows($manufacturer_query)) {
// url exists in selected language
          $manufacturer = $manufacturer_query->fetch_assoc();

          if (!\Text::is_empty($manufacturer['manufacturers_url'])) {
            $GLOBALS['db']->query(sprintf(<<<'EOSQL'
UPDATE manufacturers_info SET url_clicked = url_clicked+1, date_last_click = NOW() WHERE manufacturers_id = %d AND languages_id = %d
EOSQL
              , (int)$_GET['manufacturers_id'], (int)$_SESSION['languages_id']));

            \Href::redirect($manufacturer['manufacturers_url']);
          }
        } else {
// no url exists for the selected language, let's use the default language then
          $manufacturer_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT mi.languages_id, mi.manufacturers_url FROM manufacturers_info mi, languages l WHERE mi.manufacturers_id = %d AND mi.languages_id = l.languages_id AND l.code = '%s'
EOSQL
            , (int)$_GET['manufacturers_id'], $GLOBALS['db']->escape(DEFAULT_LANGUAGE)));
          if (mysqli_num_rows($manufacturer_query)) {
            $manufacturer = $manufacturer_query->fetch_assoc();

            if (!\Text::is_empty($manufacturer['manufacturers_url'])) {
              $GLOBALS['db']->query(sprintf(<<<'EOSQL'
UPDATE manufacturers_info SET url_clicked = url_clicked+1, date_last_click = NOW() WHERE manufacturers_id = %d AND languages_id = %d
EOSQL
                , (int)$_GET['manufacturers_id'], (int)$manufacturer['languages_id']));

              \Href::redirect($manufacturer['manufacturers_url']);
            }
          }
        }
      }
    }
  }
