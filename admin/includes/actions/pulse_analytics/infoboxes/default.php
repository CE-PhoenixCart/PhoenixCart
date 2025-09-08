<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  if (is_object($table_definition['info'] ?? null)) {
    $aInfo = $table_definition['info'];
    $heading = $aInfo->event_type;

    $link = $GLOBALS['Admin']->link('pulse_analytics.php')->retain_query_except()->set_parameter('aID', $aInfo->id);
    
    function render_payload($payload) {
      $decoded = json_decode($payload, true);

      if (empty($decoded)) {
        return '<em class="text-muted">' . JSON_NO_PAYLOAD . '</em>';
      }

      $out = '<ul class="list-group list-group-flush">';

      foreach ($decoded as $key => $value) {
        $out .= '<li class="list-group-item d-flex justify-content-between align-items-center">';

        $out .= '<strong>' . htmlspecialchars($key) . '</strong>';

        if (is_string($value) && ($try = json_decode($value, true)) && json_last_error() === JSON_ERROR_NONE) {
          if (is_array($try)) {
            $out .= '<pre class="bg-primary p-2 rounded mb-0 text-white small font-monospace">' . htmlspecialchars(json_encode($try, JSON_PRETTY_PRINT)) . '</pre>';
          } else {
            $out .= htmlspecialchars((string)$try);
          }
        } elseif (is_array($value)) {
          $out .= '<pre class="bg-primary p-2 rounded mb-0 text-white small font-monospace">' . htmlspecialchars(json_encode($value, JSON_PRETTY_PRINT)) . '</pre>';
        } else {
          $out .= '<span class="badge text-bg-primary rounded-pill">' . htmlspecialchars((string)$value) . '</span>';
        }

        $out .= '</li>';
      }

      $out .= '</ul>';

      return $out;
    }
    
    $contents[] = [
      'text' => render_payload($aInfo->payload)
    ];

    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2', $link->set_parameter('action', 'delete')),
    ];
    
  }
