<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Select extends Input {

    const ESCAPES = [
      '"' => '&quot;',
      "'" => '&#039;',
      '<' => '&lt;',
      '>' => '&gt;',
    ];

    protected $options;
    protected $required = false;
    protected $selection;

    /**
     *
     * @param string $name
     * @param array $parameters
     * @param string $css
     */
    public function __construct(string $name, array $options = [], array $parameters = []) {
      parent::__construct($name, $parameters, null);
      $this->options = $options;
    }

    /**
     *
     * @param array $option
     * @return Select
     */
    public function add_option(array $option) {
      $this->options[] = $option;
      return $this;
    }

    /**
     *
     * @return string
     */
    protected function build_options() {
      $field = '';

      $selector = ' selected="selected"';
      foreach ($this->options as $option) {
        $field .= '<option value="' . Text::output($option['id']) . '"';
        if ($selector && ($this->selection == $option['id'])) {
          $field .= $selector;
          $selector = '';
        }

        $field .= '>' . Text::output($option['text'], static::ESCAPES) . '</option>';
      }

      return $field;
    }

    /**
     *
     * @return string
     */
    public function draw() {
// default if not already set
      $this->parameters += [
        'class' => 'form-control',
      ];

      if (isset($this->parameters['value'])) {
// select menus do not have values per se; instead an option can be selected
        if (!isset($this->selection)) {
          $this->selection = $this->parameters['value'];
        }

        unset($this->parameters['value']);
      }

      $select = '<select' . $this->stringify_parameters() . '>' . $this->build_options() . '</select>';

      if ($this->required) {
        $select .= TEXT_FIELD_REQUIRED;
      }

      return $select;
    }

    /**
     *
     * @return array
     */
    public function get_options() {
      return $this->options;
    }

    /**
     *
     * @param array $options
     * @return Select
     */
    public function set_options(array $options) {
      $this->options = $options;
      return $this;
    }

    /**
     *
     * @param bool $required
     * @return Select
     */
    public function set_required(bool $required) {
      $this->required = $required;
      return $this;
    }

    /**
     *
     * @param string $selection
     * @return Select
     */
    public function set_selection(string $selection) {
      $this->selection = $selection;
      return $this;
    }

    /**
     *
     * @return string
     */
    public function __toString() {
      return $this->draw();
    }

  }
