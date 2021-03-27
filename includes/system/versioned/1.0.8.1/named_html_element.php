<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class named_html_element extends html_element {

    /**
     * @param string $name
     * @param array $parameters
     * @param string $css A space-separated list of CSS classes.
     */
    public function __construct(string $name, array $parameters = []) {
      parent::__construct(['name' => $name] + $parameters);
    }

  }
