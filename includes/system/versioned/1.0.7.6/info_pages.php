<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class info_pages {
    /*
    Example getContainer
    $pages = info_pages::getContainer(['pd.languages_id' => '1',
                                       'p.pages_status' => '1'], 'p.slug');

    Makes array of pages in english (1) where the page status is active (1), ordered by slug
    */
    public static function getContainer($container = [], $order_by = 'p.sort_order') {
      $pages_query_raw = "select * from pages p left join pages_description pd on p.pages_id = pd.pages_id where 1=1 ";
      if ( count($container) > 0 ) {
        foreach ($container as $k => $v) {
          $pages_query_raw .= "AND $k = '$v' ";
        }
      }
      $pages_query_raw .= "order by $order_by";

      return $GLOBALS['db']->fetch_all($pages_query_raw);
    }

    /*
    Example getElement
    $pages = info_pages::getElement(['p.slug' => 'privacy',
                                     'pd.languages_id' => '1'], 'pages_text');

    Get the Text of the privacy page in the english language (1)
    */
    public static function getElement($container = [], $element = null) {
      if ( (count($container) > 0) && (!Text::is_empty($element ?? '')) ) {
        $page_query_raw = "select $element from pages p left join pages_description pd on p.pages_id = pd.pages_id where 1=1 ";
        foreach ($container as $k => $v) {
          $page_query_raw .= "AND $k = '$v' ";
        }

        $page = $GLOBALS['db']->query($page_query_raw)->fetch_assoc();

        return $page[$element];
      }
    }

    public static function get_page($arr) {
      $page_arr = info_pages::getContainer($arr);

      // will always be the first and only item in the returned array
      return $page_arr[0];
    }

    public static function get_pages($order_by = null) {
      $sort_order = $order_by ?? 'p.sort_order';

      // may be 1 or more pages
      return $GLOBALS['db']->fetch_all("SELECT * FROM pages p LEFT JOIN pages_description pd ON p.pages_id = pd.pages_id WHERE pd.languages_id = '" . (int)$_SESSION['languages_id'] . "' ORDER BY $sort_order");
    }

    public static function split_page_results() {
      global $pages_query_numrows;

      $pages_query_raw = "SELECT * FROM pages p LEFT JOIN pages_description pd ON p.pages_id = pd.pages_id WHERE pd.languages_id = '" . (int)$_SESSION['languages_id'] . "' ORDER BY p.last_modified DESC, p.pages_id DESC";
      $pages_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $pages_query_raw, $pages_query_numrows);

      return $pages_split;
    }

    public static function requirements($required_slugs = ['conditions', 'privacy', 'shipping']) {

      $missing_requirements = array_diff($required_slugs,
        array_column($GLOBALS['db']->fetch_all("SELECT slug FROM pages ORDER BY slug"), 'slug'));

      return $missing_requirements;
    }

  }
