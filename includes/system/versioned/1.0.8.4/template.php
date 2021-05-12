<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Template extends oscTemplate {

    protected $_title;
    protected $_blocks = [];
    protected $_content = [];
    public $_data = [];
    protected $_template;

    public function __construct($template = null) {
      if (is_object($template)) {
        $this->_template = $template;
      } else {
        if (is_null($template)) {
          $template = TEMPLATE_SELECTION;
        }

        $template .= '_template';
        $this->_template = new $template();
      }
    }

    public function get_template() {
      return $this->_template;
    }

    public function set_title($title) {
      $this->_title = $title;
    }

    public function get_title() {
      return $this->_title;
    }

    public function add_block($block, $group) {
      $this->_blocks[$group][] = $block;
    }

    public function has_blocks($group) {
      return !empty($this->_blocks[$group]);
    }

    public function get_blocks($group) {
      if ($this->has_blocks($group)) {
        return implode("\n", $this->_blocks[$group]);
      }
    }

    public function build_blocks() {
      if ( !defined('TEMPLATE_BLOCK_GROUPS') || Text::is_empty(TEMPLATE_BLOCK_GROUPS) ) {
        return;
      }

      foreach (explode(';', TEMPLATE_BLOCK_GROUPS) as $group) {
        $module_key = 'MODULE_' . strtoupper($group) . '_INSTALLED';

        if ( !defined($module_key) || Text::is_empty(constant($module_key)) ) {
          continue;
        }

        foreach ( explode(';', constant($module_key)) as $module ) {
          $class = pathinfo($module, PATHINFO_FILENAME);

          if ( class_exists($class) ) {
            $mb = new $class();

            if ( $mb->isEnabled() ) {
              $mb->execute();
            }
          }
        }
      }
    }

    public function add_content($content, $group) {
      $this->_content[$group][] = $content;
    }

    public function has_content($group) {
      return !empty($this->_content[$group]);
    }

    public function get_content($group) {
      $template_page_class = "tp_$group";
      if ( class_exists($template_page_class) ) {
        $template_page = new $template_page_class();
        $template_page->prepare();
      }

      foreach ( $this->get_content_modules($group) as $module ) {
        if ( class_exists($module) ) {
          $mb = new $module();

          if ( $mb->isEnabled() ) {
            $mb->execute();
          }
        }
      }

      if ( isset($template_page) ) {
        $template_page->build();
      }

      $parameters = [
        'group' => $group,
        'content' => &$this->_content[$group],
      ];
      $GLOBALS['all_hooks']->cat('getContent', $parameters);
      if ($this->has_content($group)) {
        return implode("\n", $this->_content[$group]);
      }
    }

    public function get_content_modules($group) {
      $result = [];

      foreach ( explode(';', MODULE_CONTENT_INSTALLED) as $m ) {
        $module = explode('/', $m, 2);

        if ( $module[0] == $group ) {
          $result[] = $module[1];
        }
      }

      $parameters = [ 'results' => &$results ];
      $GLOBALS['all_hooks']->cat('getContentModules', $parameters);
      return $result;
    }

    public function map($file, $type = 'module') {
      return $this->_template->get_template_mapping_for($file, $type)
          ?? default_template::_get_template_mapping_for($file, $type);
    }

  }
