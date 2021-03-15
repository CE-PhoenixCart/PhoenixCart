<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class star_rating {

    protected $rating;

    public function __construct(float $rating = 0.0) {
      $this->rating = $rating;
    }

    public function __toString() {
      $star_rating = round($this->rating, 0, PHP_ROUND_HALF_UP);
      return '<span class="text-warning" title="' . $this->rating . '">'
           . str_repeat('<i class="fas fa-star"></i>', $star_rating)
           . str_repeat('<i class="far fa-star"></i>', 5 - $star_rating)
           . '</span>';
    }

  }
