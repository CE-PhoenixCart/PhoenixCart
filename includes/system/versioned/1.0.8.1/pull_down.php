<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Input extends named_html_element {

    /**
     *
     * @param string $name
     * @param string $type
     * @param array $parameters
     * @param string $css
     */
    public function __construct(string $name, array $parameters = [], string $css = null, string $type = null) {
      if (isset($type)) {
        $parameters = ['type' => $type] + $parameters;
      }

      parent::__construct($name, $parameters, $css);
    }

    /**
     * Set the value to either the default or (if present) the previously requested value.
     * @param string $value
     */
    public function default_value(string $value) {
      $this->set('value', Request::value($name) ?? $value);
    }

    public function __toString() {
// default if not already set
      $this->parameters += [
        'type' => 'text',
        'class' => 'form-control',
      ];

      return '<input' . $this->stringify_parameters() . ' />';
    }

  }
