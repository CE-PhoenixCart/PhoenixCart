<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Tickable extends Input {

    public function tick(bool $tick = true) {
      if ($tick) {
        $this->set('checked', 'checked');
      } else {
        $this->delete('checked');
      }

      return $this;
    }

    /**
     * Set the value to either the default or (if present) the previously requested value.
     */
    public function tick_if_requested() {
      $requested = Request::value($this->parameters['name']);
      if (is_string($requested) && (('on' === $requested) || ($this->get('value') == $requested))) {
        $this->tick();
      }

      return $this;
    }

    public function __toString() {
// default if not already set
      $this->parameters += [
        'type' => 'checkbox',
        'class' => '',
      ];

      return parent::__toString();
    }

  }
