<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Button extends html_element {

    protected static $count = 1;

    protected $icon;
    protected $title;

    public function __construct(string $title = '', string $icon = null, string $style = null, array $parameters = [], $link = null) {
      parent::__construct($parameters);
      $this->append_css('btn ' . ($style ?? 'btn-outline-secondary'));
      $this->icon = $icon;
      $this->title = $title;
      if (is_string($link)) {
        $this->set('href', $link);
      } elseif (!is_null($link)) {
        $this->parameters['href'] = $link;
      }
    }

    public function get_count() {
      return static::$count;
    }

    public function get_icon() {
      return $this->icon;
    }

    public function get_title() {
      return $this->title;
    }

    public function set_icon(string $icon) {
      $this->icon = $icon;
      return $this;
    }

    public function set_title(string $title) {
      $this->title = $title;
      return $this;
    }

    public function __toString() {
      if ( !isset($this->parameters['type']) || !in_array($this->parameters['type'], ['submit', 'button', 'reset']) ) {
        $this->parameters['type'] = 'submit';
      }

      if ( isset($this->parameters['href']) && ('reset' === $this->parameters['type']) ) {
        trigger_error('Cannot use links with reset buttons.');
        unset($this->parameters['href']);
      }

      if ( isset($this->parameters['href']) ) {
        unset($this->parameters['type']);
        if ( isset($this->parameters['newwindow']) ) {
          $this->set('target', '_blank');
          $this->set('rel', 'noreferrer');
          $this->delete('newwindow');
        }

        $this->set('id', 'btn' . static::$count);
        ++static::$count;

        $button = '<a';
        $closing_tag = '</a>';
      } else {
        $button = '<button';
        $closing_tag = '</button>';
      }

      $button .= $this->stringify_parameters() . '>';

      if (isset($this->icon) && !Text::is_empty($this->icon)) {
        $button .= ' <span class="' . $this->icon . '" aria-hidden="true"></span> ';
      }

      return "$button{$this->title}$closing_tag";
    }
  }
