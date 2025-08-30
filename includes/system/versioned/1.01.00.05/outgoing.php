<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

class Outgoing {

  protected static $modules = null;
  protected static $merge_tags_cache = null;

  protected static function loadModules() {
    if (self::$modules === null) {
      self::$modules = [];
      $files = glob(DIR_FS_CATALOG . 'includes/modules/outgoing/*.php');
      foreach ($files as $file) {
        include_once($file);
        $class = 'Outgoing_' . basename($file, '.php');
        if (class_exists($class)) {
            self::$modules[] = $class;
        }
      }
    }
    
    return self::$modules;
  }

  public static function parse() {
    foreach (self::loadModules() as $class) {
      if (method_exists($class, 'execute')) {
        call_user_func([$class, 'execute']);
      }
    }
  }

  public static function delete() {
    foreach (self::loadModules() as $class) {
      if (method_exists($class, 'remove')) {
        call_user_func([$class, 'remove']);
      }
    }
  }

  public static function show_pages() {
    $display_pages = [];
    foreach (self::loadModules() as $class) {
      if (method_exists($class, 'pages')) {
        $pages = call_user_func([$class, 'pages']);
        if (is_array($pages)) {
          $display_pages = array_merge($display_pages, $pages);
        }
      }
    }
    $display_pages[] = 'checkout_success.php';
    
    return array_unique($display_pages);
  }

  public static function merge_tags() {
    if (self::$merge_tags_cache !== null) {
      return self::$merge_tags_cache;
    }
    $merge_tags = [];
    foreach (self::loadModules() as $class) {
      if (method_exists($class, 'merge_tags')) {
        $mt = call_user_func([$class, 'merge_tags']);
        if (is_array($mt)) {
          $merge_tags = array_replace_recursive($merge_tags, $mt);
        }
      }
    }
    self::$merge_tags_cache = $merge_tags;
    
    return $merge_tags;
  }

  public static function email_dropdown() {
    $slug_array = [['id' => '', 'text' => SLUG_SELECT]];
    foreach (self::loadModules() as $class) {
      if (method_exists($class, 'email')) {
        $result = call_user_func([$class, 'email']);
        if ($result !== null) {
          $slug_array[] = $result;
        }
      }
    }
    
    return array_filter($slug_array);
  }

  public static function all_dropdown() {
    $slug_array = [['id' => '', 'text' => SLUG_SELECT]];
    foreach (self::loadModules() as $class) {
      if (method_exists($class, 'dropdown')) {
        $result = call_user_func([$class, 'dropdown']);
        if ($result !== null) {
          $slug_array[] = $result;
        }
      }
    }
    
    return array_filter($slug_array);
  }

  protected static function buildReplacementArray() {
    static $replacement_array = null;
    if ($replacement_array !== null) {
      return $replacement_array;
    }
    $merge_tags = self::merge_tags();
    $replacement_array = [];
    foreach ($merge_tags as $group) {
      foreach ($group as $tag => $_) {
        $replacement_array[] = $tag;
      }
    }
    $replacement_array = array_unique($replacement_array);
    
    return $replacement_array;
  }

  public static function sendEmail() {
    $replacement_array = self::buildReplacementArray();

    $slugworth  = ['{{FNAME}}', '{{LNAME}}', '{{EMAIL}}'];
    foreach ($replacement_array as $tag) {
      $slugworth[] = $tag;
    }

    $query = $GLOBALS['db']->query("
        SELECT ot.*, oti.title, oti.text, oti.languages_id
        FROM outgoing_tpl ot
        JOIN outgoing_tpl_info oti ON oti.id = ot.id
    ");
    $slugs = [];
    while ($row = $query->fetch_assoc()) {
      $slugs[$row['slug']][$row['languages_id']] = $row;
    }

    $outgoing_emails_query = $GLOBALS['db']->query("SELECT * FROM outgoing WHERE send_at < NOW()");
    if ($outgoing_emails_query->num_rows === 0) {
      return;
    }

    while ($email = $outgoing_emails_query->fetch_assoc()) {
      $email_data = $slugs[$email['slug']][$email['languages_id']] ?? null;
      if ($email_data === null || empty($email_data['text'])) {
        continue;
      }

      $replacements = [
        '{{FNAME}}' => $email['fname'],
        '{{LNAME}}' => $email['lname'],
        '{{EMAIL}}' => $email['email_address'],
      ];

      foreach ($replacement_array as $tag) {
        $replacements[$tag] = null;
      }

      $merge_tags_json = json_decode($email['merge_tags'], true);
      if (json_last_error() === JSON_ERROR_NONE && is_array($merge_tags_json)) {
        foreach ($merge_tags_json as $k => $v) {
          $replacements['{{' . strtoupper($k) . '}}'] = $v;
        }
      }

      $email_title = str_replace(array_keys($replacements), array_values($replacements), $email_data['title']);
      $email_text = str_replace(array_keys($replacements), array_values($replacements), $email_data['text']);

      $mimemessage = new email();
      $mimemessage->add_message($email_text);
      $mimemessage->build_message();
      $mimemessage->send(
        $email['fname'] . ' ' . $email['lname'],
        $email['email_address'],
        STORE_OWNER,
        STORE_OWNER_EMAIL_ADDRESS,
        $email_title
      );

      $GLOBALS['db']->query("DELETE FROM outgoing WHERE id = " . (int)$email['id']);
    }
  }

  public static function getEmail($arr) {
    $replacement_array = self::buildReplacementArray();

    $slugworth  = ['{{FNAME}}', '{{LNAME}}', '{{EMAIL}}'];
    foreach ($replacement_array as $tag) {
      $slugworth[] = $tag;
    }

    $slug = $GLOBALS['db']->real_escape_string($arr['slug']);
    $languages_id = (int)$arr['languages_id'];
    $id = (int)$arr['id'];

    $query = $GLOBALS['db']->query("
      SELECT ot.*, oti.title, oti.text
      FROM outgoing_tpl ot
      JOIN outgoing_tpl_info oti ON oti.id = ot.id
      WHERE ot.slug = '{$slug}'
      AND oti.languages_id = {$languages_id}
    ");

    $slugs = [];
    while ($row = $query->fetch_assoc()) {
      $slugs[$row['slug']] = $row;
    }

    $outgoing_emails_query = $GLOBALS['db']->query("SELECT * FROM outgoing WHERE id = {$id}");
    if ($outgoing_emails_query->num_rows === 0) {
      return json_encode([]);
    }

    $outgoing = [];

    while ($email = $outgoing_emails_query->fetch_assoc()) {
      $email_data = $slugs[$email['slug']] ?? null;
      if ($email_data === null) {
        continue;
      }

      $replacements = [
        '{{FNAME}}' => $email['fname'],
        '{{LNAME}}' => $email['lname'],
        '{{EMAIL}}' => $email['email_address'],
      ];

      foreach ($replacement_array as $tag) {
        $replacements[$tag] = null;
      }

      $merge_tags_json = json_decode($email['merge_tags'], true);
      if (json_last_error() === JSON_ERROR_NONE && is_array($merge_tags_json)) {
        foreach ($merge_tags_json as $k => $v) {
          $replacements['{{' . strtoupper($k) . '}}'] = $v;
        }
      }

      $email_title = str_replace(array_keys($replacements), array_values($replacements), $email_data['title']);
      $email_text = str_replace(array_keys($replacements), array_values($replacements), $email_data['text']);

      $outgoing[$email['id']] = [
        'email' => [
          'title' => $email_title,
          'text' => $email_text,
          'slug' => $email['slug'],
        ],
        'TO' => [
          'FNAME' => $email['fname'],
          'LNAME' => $email['lname'],
          'EMAIL' => $email['email_address'],
          'SEND_AT' => $email['send_at'],
        ],
        'REPLACEMENTS' => $replacements,
      ];
    }

    return json_encode($outgoing, JSON_PRETTY_PRINT);
  }

  public static function deBug() {
    $replacement_array = self::buildReplacementArray();

    $slugworth  = ['{{FNAME}}', '{{LNAME}}', '{{EMAIL}}'];
    foreach ($replacement_array as $tag) {
      $slugworth[] = $tag;
    }

    $query = $GLOBALS['db']->query("
      SELECT ot.*, oti.title, oti.text, oti.languages_id
      FROM outgoing_tpl ot
      JOIN outgoing_tpl_info oti ON oti.id = ot.id
    ");

    $slugs = [];
    while ($row = $query->fetch_assoc()) {
        $slugs[$row['slug']][$row['languages_id']] = $row;
    }

    $debug_out = [];
    foreach ($slugs as $slug => $lang_data) {
      foreach ($lang_data as $lang => $data) {
        $replacements = array_fill_keys($slugworth, null);

        $email_title = $data['title'] ?? '';
        $email_text = $data['text'] ?? '';

        $debug_out[$slug][$lang] = [
          'title' => $email_title,
          'text' => $email_text,
          'replacements' => $replacements,
        ];
      }
    }

    return json_encode($debug_out, JSON_PRETTY_PRINT);
  }
  
}
