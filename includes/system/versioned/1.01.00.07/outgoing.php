<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

class Outgoing {
  
  protected static $cache = [
    'modules' => null,
    'merge_tags' => null,
    'templates' => null,
    'replacement_tags' => null,
  ];

  protected static function ensureModules(): array {
    if (self::$cache['modules'] === null) {
      self::$cache['modules'] = [];
      $files = glob(DIR_FS_CATALOG . 'includes/modules/outgoing/*.php') ?: [];
      
      foreach ($files as $file) {
        require_once $file;
        $class = 'Outgoing_' . basename($file, '.php');
        if (class_exists($class)) {
          self::$cache['modules'][] = $class;
        }
      }
    }
    return self::$cache['modules'];
  }

  protected static function executeModules(string $method): void {
    foreach (self::ensureModules() as $class) {
      if (method_exists($class, $method)) {
        $class::$method();
      }
    }
  }

  public static function parse(): void {
    self::executeModules('execute');
  }

  public static function delete(): void {
    self::executeModules('remove');
  }

  public static function show_pages(): array {
    $pages = ['checkout_success.php'];
    foreach (self::ensureModules() as $class) {
      if (method_exists($class, 'pages')) {
        $result = $class::pages();
        $pages = array_merge($pages, is_array($result) ? $result : []);
      }
    }
    return array_unique($pages);
  }

  public static function merge_tags(): array {
    if (self::$cache['merge_tags'] !== null) {
      return self::$cache['merge_tags'];
    }

    self::$cache['merge_tags'] = [];
    foreach (self::ensureModules() as $class) {
      if (method_exists($class, 'merge_tags')) {
        $tags = $class::merge_tags();
        if (is_array($tags)) {
          self::$cache['merge_tags'] = array_replace_recursive(
            self::$cache['merge_tags'], $tags
          );
        }
      }
    }
    return self::$cache['merge_tags'];
  }

  public static function email_dropdown(): array {
    $items = [['id' => '', 'text' => SLUG_SELECT]];
    foreach (self::ensureModules() as $class) {
      if (method_exists($class, 'email')) {
        $result = $class::email();
        if ($result !== null) $items[] = $result;
      }
    }
    return array_filter($items);
  }

  public static function all_dropdown(): array {
    $items = [['id' => '', 'text' => SLUG_SELECT]];
    foreach (self::ensureModules() as $class) {
      if (method_exists($class, 'dropdown')) {
        $result = $class::dropdown();
        if ($result !== null) $items[] = $result;
      }
    }
    return array_filter($items);
  }

  protected static function getReplacementTags(): array {
    if (self::$cache['replacement_tags'] !== null) {
      return self::$cache['replacement_tags'];
    }

    $tags = [];
    foreach (self::merge_tags() as $group) {
      foreach (array_keys($group) as $tag) {
        $tags[] = $tag;
      }
    }
    return self::$cache['replacement_tags'] = array_unique($tags);
  }

  protected static function processTemplate(string $template, array $replacements): string {
    $template = self::processLoopTags($template, $replacements);
    
    $search = $replace = [];
    foreach ($replacements as $tag => $value) {
      if (!is_array($value) && $value !== null) {
        $search[] = $tag;
        $replace[] = $value;
      }
    }
    
    return $search ? str_replace($search, $replace, $template) : $template;
  }

  protected static function processLoopTags(string $template, array $replacements): string {
    if (preg_match_all('/{{#EACH\s+(\w+)}}(.*?){{\/EACH}}/s', $template, $matches, PREG_SET_ORDER)) {
      foreach ($matches as $match) {
        $array_name = $match[1];
        $loop_content = $match[2];
        $full_match = $match[0];
        $array_tag = '{{' . strtoupper($array_name) . '}}';
        
        if (!isset($replacements[$array_tag]) || !is_array($replacements[$array_tag])) {
          $replacement = '';
        } else {
          $replacement = '';
          foreach ($replacements[$array_tag] as $item) {
            $item_content = $loop_content;
            if (is_array($item)) {
              $item_search = $item_replace = [];
              foreach ($item as $key => $value) {
                $item_search[] = '{{' . strtoupper($key) . '}}';
                $item_replace[] = $value;
              }
              $item_content = str_replace($item_search, $item_replace, $item_content);
            }
            $replacement .= $item_content;
          }
        }
        $template = str_replace($full_match, $replacement, $template);
      }
    }
    return $template;
  }

  protected static function ensureTemplates(): array {
    if (self::$cache['templates'] !== null) {
      return self::$cache['templates'];
    }

    $result = $GLOBALS['db']->query("
      SELECT ot.*, oti.title, oti.text, oti.languages_id
      FROM outgoing_tpl ot
      JOIN outgoing_tpl_info oti ON oti.id = ot.id
    ");

    $templates = [];
    while ($row = $result->fetch_assoc()) {
      $templates[$row['slug']][$row['languages_id']] = $row;
    }
    return self::$cache['templates'] = $templates;
  }

  public static function sendEmail(): void {
    $templates = self::ensureTemplates();
    if (empty($templates)) return;

    $pending = $GLOBALS['db']->query("SELECT * FROM outgoing WHERE send_at < NOW()");
    if ($pending->num_rows === 0) return;

    $replacement_tags = self::getReplacementTags();
    $email_count = 0;

    while ($email = $pending->fetch_assoc()) {
      $email_data = $templates[$email['slug']][$email['languages_id']] ?? null;
      if (!$email_data || empty($email_data['text'])) continue;

      $replacements = array_fill_keys($replacement_tags, null) + [
        '{{FNAME}}' => $email['fname'],
        '{{LNAME}}' => $email['lname'],
        '{{EMAIL}}' => $email['email_address'],
      ];

      $merge_tags = json_decode($email['merge_tags'], true);
      if (json_last_error() === JSON_ERROR_NONE && is_array($merge_tags)) {
        foreach ($merge_tags as $k => $v) {
          $replacements['{{' . strtoupper($k) . '}}'] = $v;
        }
      }

      $title = self::processTemplate($email_data['title'], $replacements);
      $text = self::processTemplate($email_data['text'], $replacements);

      // Send email
      $mimemessage = new email();
      $mimemessage->add_message($text);
      $mimemessage->build_message();
      $mimemessage->send(
        trim($email['fname'] . ' ' . $email['lname']),
        $email['email_address'],
        STORE_OWNER,
        STORE_OWNER_EMAIL_ADDRESS,
        $title
      );

      $GLOBALS['db']->query("DELETE FROM outgoing WHERE id = " . (int)$email['id']);
    }
  }

  public static function getEmail(array $arr): string {
    $templates = self::ensureTemplates();
    $replacement_tags = self::getReplacementTags();

    $id = (int)$arr['id'];
    $result = $GLOBALS['db']->query("SELECT * FROM outgoing WHERE id = {$id}");
    
    if ($result->num_rows === 0) {
      return json_encode([]);
    }

    $outgoing = [];
    $email = $result->fetch_assoc();
    $email_data = $templates[$email['slug']][$email['languages_id']] ?? null;

    if ($email_data) {
      $replacements = array_fill_keys($replacement_tags, null) + [
        '{{FNAME}}' => $email['fname'],
        '{{LNAME}}' => $email['lname'],
        '{{EMAIL}}' => $email['email_address'],
      ];

      $merge_tags = json_decode($email['merge_tags'], true);
      if (json_last_error() === JSON_ERROR_NONE && is_array($merge_tags)) {
        foreach ($merge_tags as $k => $v) {
          $replacements['{{' . strtoupper($k) . '}}'] = $v;
        }
      }

      $outgoing[$id] = [
        'email' => [
          'title' => self::processTemplate($email_data['title'], $replacements),
          'text' => self::processTemplate($email_data['text'], $replacements),
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

  public static function deBug(): string {
    $templates = self::ensureTemplates();
    $replacement_tags = self::getReplacementTags();

    $debug = [];
    foreach ($templates as $slug => $lang_data) {
      foreach ($lang_data as $lang => $data) {
        $debug[$slug][$lang] = [
          'title' => $data['title'] ?? '',
          'text' => $data['text'] ?? '',
          'replacements' => array_fill_keys($replacement_tags, null) + [
            '{{FNAME}}' => null, '{{LNAME}}' => null, '{{EMAIL}}' => null
          ],
        ];
      }
    }

    return json_encode($debug, JSON_PRETTY_PRINT);
  }
  
}