<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  class modular {

    public static function display_layout($slots) {
      
      $merged = [];
      
      $colors = [
        'A' => ['color' => 'bg-dark text-white'],
        'B' => ['color' => 'bg-dark-subtle'],
        'C' => ['color' => 'bg-body-secondary'],
        'D' => ['color' => 'bg-body-tertiary'],
        'E' => ['color' => 'bg-dark text-white'],
        'F' => ['color' => 'bg-dark-subtle'],
        'G' => ['color' => 'bg-body-secondary'],
        'H' => ['color' => 'bg-body-tertiary'],
        'I' => ['color' => 'bg-dark text-white'],
      ];

      foreach ($slots as $key => $slot) {
        $merged[$key] = $slot; 
        $merged[$key]['color'] = $colors[$key]['color'];
      }

      $layout = '<div class="alert alert-light">';
        $layout = '<div class="container text-center">';
          $layout .= '<div class="row">';
            foreach ($merged as $k => $slot) {
              $style = ['fw-bold', 'fs-5', 'font-monospace', $slot['width'], $slot['color']];
            
              $layout .= '<div class="' . implode(' ', $style) . '">' . $k . '</div>';
            }
          $layout .= '</div>';
        $layout .= '</div>';
      $layout .= '</div>';

      return $layout;
    }

  }