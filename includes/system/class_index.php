<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class class_index {

    protected $files = [];
    protected $actions = [];
    protected $hooks = [];
    protected $templates = [];
    protected $directory;
    public $translator;

    public function __construct(string $directory) {
      $this->directory = $directory;
    }

    public function autoload(string $original_class) {
// convert camelCase class names to snake_case filenames
      $class = $this->normalize_class_name($original_class);

      if (!isset($this->files[$class])) {
        return;
      }

      if (!empty($_SESSION['language'])
        && (substr($this->files[$class], 0, strlen($this->directory)) === $this->directory))
      {
        $language_file = substr($this->files[$class], strlen($this->directory));
        $language_file = is_callable($this->translator)
                       ? call_user_func($this->translator, $language_file)
                       : "{$this->directory}languages/{$_SESSION['language']}/$language_file";

        if (file_exists($language_file)) {
          include $language_file;
        }
      }

      require $this->files[$class];
    }

    public function find_all_actions_under(string $directory) {
      foreach (scandir($directory, SCANDIR_SORT_ASCENDING) as $file) {
        if (is_file($path = "$directory/$file")) {
          $name = pathinfo($file, PATHINFO_FILENAME);
          $name = "Phoenix\\Actions\\$name";
          $this->files[static::normalize_class_name($name)] = $path;
          $this->actions[] = $name;
        }
      }
    }

    public function find_all_files_under(string $directory) {
      foreach (scandir($directory, SCANDIR_SORT_ASCENDING) as $entry) {
// we have no file or directory names starting with a dot so it's safe
// to screen out anything that does, like the current and parent directories
        if ('.' === $entry[0]) {
          continue;
        }

        $path = "$directory/$entry";
        if (is_file($path)) {
          $this->files[pathinfo($entry, PATHINFO_FILENAME)] = $path;
        } elseif (is_dir($path) && 'templates' !== $entry) {
// templates directories appear in the directory tree but do not contain classes
          $this->find_all_files_under($path);
        }
      }
    }

    public function find_all_hooks_under(string $directory) {
      foreach (scandir($directory, SCANDIR_SORT_ASCENDING) as $site) {
// we have no file or directory names starting with a dot so it's safe
// to screen out anything that does, like the current and parent directories
        if ('.' === $site[0] || !is_dir($site_path = "$directory$site")) {
          continue;
        }

        foreach (scandir($site_path, SCANDIR_SORT_ASCENDING) as $group) {
          if ('.' === $group[0] || !is_dir($group_path = "$site_path/$group")) {
            continue;
          }

          foreach (scandir($group_path, SCANDIR_SORT_ASCENDING) as $file) {
            if (is_file($path = "$group_path/$file")
              && ($pathinfo = pathinfo($path))
              && ('php' === $pathinfo['extension']))
            {
              $name = static::name_hook($directory, $path);
              $this->files[$name] = $path;
              $this->hooks[$site][$group][$pathinfo['filename']] = $path;
            }
          }
        }
      }
    }

    public function find_all_templates_under(string $directory) {
      foreach (scandir($directory, SCANDIR_SORT_ASCENDING) as $template) {
        if (('.' !== $template[0]) && is_file($path = "$directory/$template/includes/template.php")) {
          $name = $template . '_template';
          $this->files[$name] = $path;
          $this->templates[] = $name;
        }
      }
    }

    public function get(string $class) {
      return $this->files[static::normalize_class_name($class)];
    }

    public function get_actions() {
      return array_intersect_key($this->files, array_flip($this->actions));
    }

    public function get_directory() {
      return $this->directory;
    }

    public function get_files() {
      return $this->files;
    }

    public function get_hooks() {
      return $this->hooks;
    }

    public function get_templates() {
      return array_intersect_key($this->files, array_flip($this->templates));
    }

    public function register() {
      if (spl_autoload_register([$this, 'autoload'])) {
        return $this;
      }
    }

    public function set(string $class, string $path) {
      $this->files[static::normalize_class_name($class)] = $path;
    }

    public function set_translator($translator = null) {
      if (is_callable($translator)) {
        $this->translator = $translator;
      }
    }

    public function translate($path) {
      return "{$this->directory}languages/{$_SESSION['language']}/$path";
    }

    public static function name_hook(string $directory, string $path) {
      list ($site, $group, $basename) = explode('/', substr($path, strlen($directory)), 3);
      return static::normalize_class_name(
        'hook_' . $site . '_' . $group . '_' . pathinfo($basename, PATHINFO_FILENAME));
    }

    public static function normalize_class_name(string $original_class) {
      return strtolower(preg_replace('{(?<!^)[A-Z]}', '_$0', $original_class));
    }

  }

  function tep_find_all_hooks_under(string $directory, &$class_files) {
    trigger_error('The tep_find_all_hooks_under function has been deprecated.', E_USER_DEPRECATED);
    $GLOBALS['class_index']->find_all_hooks_under($directory);
    // adds to the regular autoloader, so it isn't necessary to set anything in class files
  }

  function tep_normalize_class_name($original_class) {
    trigger_error('The tep_normalize_class_name function has been deprecated.', E_USER_DEPRECATED);
    return class_index::normalize_class_name($original_class);
  }
