<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  switch ($_GET['action']) {
    case 'url':
      if (isset($_GET['goto']) && !Text::is_empty($_GET['goto'])) {
        $check_query = $db->query("SELECT products_url FROM products_description WHERE products_url = '" . $db->escape($_GET['goto']) . "' LIMIT 1");
        if (mysqli_num_rows($check_query)) {
          Href::redirect($_GET['goto']);
        }
      }
      break;

    case 'manufacturer':
      if (isset($_GET['manufacturers_id']) && !Text::is_empty($_GET['manufacturers_id'])) {
        $manufacturer_query = $db->query(sprintf(<<<'EOSQL'
SELECT manufacturers_url FROM manufacturers_info WHERE manufacturers_id = %d AND languages_id = %d
EOSQL
          , (int)$_GET['manufacturers_id'], (int)$_SESSION['languages_id']));
        if (mysqli_num_rows($manufacturer_query)) {
// url exists in selected language
          $manufacturer = $manufacturer_query->fetch_assoc();

          if (!Text::is_empty($manufacturer['manufacturers_url'])) {
            $db->query(sprintf(<<<'EOSQL'
UPDATE manufacturers_info SET url_clicked = url_clicked+1, date_last_click = NOW() WHERE manufacturers_id = %d AND languages_id = %d
EOSQL
              , (int)$_GET['manufacturers_id'], (int)$_SESSION['languages_id']));

            Href::redirect($manufacturer['manufacturers_url']);
          }
        } else {
// no url exists for the selected language, let's use the default language then
          $manufacturer_query = $db->query(sprintf(<<<'EOSQL'
SELECT mi.languages_id, mi.manufacturers_url FROM manufacturers_info mi, languages l WHERE mi.manufacturers_id = %d AND mi.languages_id = l.languages_id AND l.code = '%s'
EOSQL
            , (int)$_GET['manufacturers_id'], $db->escape(DEFAULT_LANGUAGE)));
          if (mysqli_num_rows($manufacturer_query)) {
            $manufacturer = $manufacturer_query->fetch_assoc();

            if (!Text::is_empty($manufacturer['manufacturers_url'])) {
              $db->query(sprintf(<<<'EOSQL'
UPDATE manufacturers_info SET url_clicked = url_clicked+1, date_last_click = NOW() WHERE manufacturers_id = %d AND languages_id = %d
EOSQL
                , (int)$_GET['manufacturers_id'], (int)$manufacturer['languages_id']));

              Href::redirect($manufacturer['manufacturers_url']);
            }
          }
        }
      }
      break;
  }

  Href::redirect($Linker->build('index.php'));
