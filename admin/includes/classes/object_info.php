<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class objectInfo {

    public function __construct($object_data) {
      $this->objectInfo($object_data);
    }

    public function objectInfo($object_data) {
      foreach($object_data as $key => $value) {
        $this->$key = is_null($value) ? null
          : filter_var($value, FILTER_CALLBACK, ['options' => 'Text::prepare']);
      }
    }

  }
