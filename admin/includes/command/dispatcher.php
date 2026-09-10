<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2026 Phoenix Cart

  Released under the GNU General Public License
*/

  function admin_command_dispatch(string $input) {
    $parts = preg_split('/\s+/', trim($input));

    $verb = strtolower(array_shift($parts) ?? '');
    $subject = strtolower(array_shift($parts) ?? '');
    $args = trim(implode(' ', $parts));

    if ($verb === 'help') {
      $base = DIR_FS_ADMIN . 'includes/command/';
      $items = [];

      if ($subject === '') {
        foreach (glob($base . '*', GLOB_ONLYDIR) as $dir) {
          $items[] = basename($dir);
        }

        $title = CP_AVAILABLE_VERBS;
      } else {
        $path = $base . $subject;

        if (is_dir($path)) {
          foreach (glob($path . '/*.php') as $file) {
            $items[] = basename($file, '.php');
          }
        }

        $title = sprintf(CP_VERB_SUBJECTS, $subject);
      }

      echo '<h5>' . htmlspecialchars($title) . '</h5>';
      echo '<ul class="list-group">';
      foreach ($items as $item) {
        echo '<li class="list-group-item">' . htmlspecialchars($item) . '</li>';
      }
      echo '</ul>';
      exit;
    }

    if ($verb === '' || $subject === '') {
      $GLOBALS['messageStack']->add_session(
        sprintf(CP_INCOMPLETE_COMMAND, $verb),
        'danger'
      );

      Href::redirect($GLOBALS['Admin']->link('index.php'));
    }

    if (!preg_match('/^[a-z0-9_-]+$/', $verb) || !preg_match('/^[a-z0-9_-]+$/', $subject)) {
      $GLOBALS['messageStack']->add_session(
        CP_INVALID_COMMAND,
        'danger'
      );

      Href::redirect($GLOBALS['Admin']->link('index.php'));
    }

    $file = DIR_FS_ADMIN . "includes/command/{$verb}/{$subject}.php";

    if (!file_exists($file)) {
      $GLOBALS['messageStack']->add_session(
        sprintf(CP_VERB_SUBJECT_NOT_FOUND, $verb, $subject),
        'danger'
      );

      Href::redirect($GLOBALS['Admin']->link('index.php'));
    }

    return include $file;
  }
