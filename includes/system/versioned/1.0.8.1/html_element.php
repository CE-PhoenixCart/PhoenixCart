<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class html_element {

    protected $parameters;

    /**
     * @param string $name
     * @param array $parameters
     * @param string $css A space-separated list of CSS classes.
     */
    public function __construct(array $parameters = []) {
      $this->parameters = $parameters;
    }

    /**
     * Append to any existing CSS.  Will create if not already there.
     * @param string $css A space-separated list of CSS classes.
     */
    public function append_css(string $css) {
      if (isset($this->parameters['class']) && !Text::is_empty($this->parameters['class'])) {
        $this->parameters['class'] .= " $css";
      } else {
        $this->set('class', $css);
      }

      return $this;
    }

    /**
     * Unset a parameter.
     * @param string $name
     */
    public function delete(string $name) {
      unset($this->parameters[$name]);
      return $this;
    }

    /**
     * Get the current parameter value.
     * @param string $name
     * @return number
     */
    public function get(string $name) {
      return $this->parameters[$name];
    }

    /**
     * Set an element parameter.
     * @param string $name
     * @param string $value
     */
    public function set(string $name, string $value = null) {
      $this->parameters[$name] = $value;
      return $this;
    }

    /**
     * Convert the parameters array into a query string.
     * @return string
     */
    public function stringify_parameters() {
      return implode('', array_map(function ($parameter, $value) {
        if (isset($value)) {
          $parameter .= '="' . Text::output("$value") . '"';
        }

        return " $parameter";
      }, array_keys($this->parameters), $this->parameters));
    }

  }
