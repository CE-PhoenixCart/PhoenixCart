<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class url_query {

    public static function parse(string $query) : array {
      parse_str($query, $parameters);
      return $parameters;
    }

  }
