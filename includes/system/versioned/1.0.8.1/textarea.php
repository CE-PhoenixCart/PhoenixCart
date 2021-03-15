<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Textarea extends Input {

    protected $text = '';

    /**
     *
     * @param string $name
     * @param array $parameters
     * @param string $css
     */
    public function __construct(string $name, array $parameters = []) {
      parent::__construct($name, $parameters, null);
    }

    public function retain_text() {
      if (is_string($text = Request::value($this->get('name'))) && !Text::is_empty($text)) {
        $this->set_text($text);
      }
    }

    public function set_text($text) {
      $this->text = $text;
    }

    public function __toString() {
// default if not already set
      $this->parameters += [
        'class' => 'form-control',
      ];

      return '<textarea' . $this->stringify_parameters() . ' >' . htmlspecialchars($this->text). '</textarea>';
    }

  }
