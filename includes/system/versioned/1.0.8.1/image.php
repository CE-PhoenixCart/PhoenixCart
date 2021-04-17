<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Image extends html_element {

    public $path_prefix = DIR_FS_CATALOG;
    protected $responsive = true;
    protected $web_prefix = '';
    protected $default = false;

    /**
     *
     * @param string $src
     * @param array $parameters
     * @param string $alt
     * @param numeric $width
     * @param numeric $height
     */
    public function __construct(string $src, array $parameters = [],
      string $alt = null, $width = null, $height = null)
    {
      foreach (['src', 'alt', 'width', 'height'] as $key) {
        if (isset($$key)) {
          $parameters[$key] = $$key;
        }
      }

      if (!isset($parameters['border'])) {
        $parameters['border'] = 0;
      }

      if (defined('DEFAULT_IMAGE') && !Text::is_empty(DEFAULT_IMAGE)) {
        $this->default = DEFAULT_IMAGE;
      }

      parent::__construct($parameters);
    }

    /**
     * If this points to a valid image.
     * @return boolean
     */
    public function is_valid() {
      if (!$this->default
       && (empty($this->parameters['src'])
        || !is_file("{$this->path_prefix}{$this->parameters['src']}")))
      {
        return false;
      }

      return ( (empty($this->parameters['width'])
             && empty($this->parameters['height']))
        && (('true' !== CONFIG_CALCULATE_IMAGE_SIZE)
         || (false === $this->size())) );
    }

    /**
     * Set a default image or false not to use a default.
     * @param bool|string $default
     * @return Image
     */
    public function set_default($default) {
      $this->default = $default;
      return $this;
    }

    /**
     *
     * @param string $prefix
     * @return Image
     */
    public function set_prefix(string $prefix) {
      $this->path_prefix = $prefix;
      return $this;
    }

    /**
     * Set whether to make the image responsive.
     */
    public function set_responsive(bool $responsive) {
      $this->responsive = $responsive;
      return $this;
    }

    /**
     *
     * @param string|false $prefix
     * @return Image
     */
    public function set_web_prefix($prefix) {
      $this->web_prefix = $prefix;
      return $this;
    }

    /**
     * Calculate and parameterize the image size.
     * @return boolean
     */
    public function size() {
      if ($image_size = @getimagesize($this->get('src'))) {
        if (empty($this->parameters['width']) && empty($this->parameters['height'])) {
          $this->set('width', "{$image_size[0]}");
          $this->set('height', "{$image_size[1]}");
        } elseif (empty($this->parameters['width'])) {
          $scale = $this->get('height') / $image_size[1];
          $this->set('width', (string)(int)($image_size[0] * $scale));
        } else {
          $scale = $this->get('width') / $image_size[0];
          $this->set('height', (string)(int)($image_size[1] * $scale));
        }

        return true;
      }

      return ('false' !== IMAGE_REQUIRED);
    }

    /**
     *
     * @return string
     */
    public function __toString() {
      $relative_path = $this->get('src');
      if ($this->web_prefix) {
        if (Text::is_prefixed_by($relative_path, $this->web_prefix)) {
          $relative_path = Text::ltrim_once($relative_path, $this->web_prefix);
        } else {
          $this->set('src', "{$this->web_prefix}$relative_path");
        }
// $relative_path is the path relative to the prefixes
// $this->parameters['src'] is the web path, as should appear in the img src
      }

      if ($this->default && !is_file("{$this->path_prefix}$relative_path")) {
        $this->set('src', $this->default);
      } elseif ( (empty($this->parameters['src'])
               || ('images/' === $relative_path))
              && ('false' === IMAGE_REQUIRED) )
      {
        return '';
      }

      if ( empty($this->parameters['width'])
        && empty($this->parameters['height'])
        && ('true' === CONFIG_CALCULATE_IMAGE_SIZE)
        && (false === $this->size()) )
      {
        return '';
      }

      if ($this->responsive) {
        $this->append_css('img-fluid');
      }

// alt is added as the img title even if  null to prevent browsers from outputting
// the image filename as default
      if (!isset($this->parameters['title'])) {
        $this->set('title', $this->parameters['alt'] ?? '');
      }

      return '<img' . $this->stringify_parameters() . ' />';
    }

  }
