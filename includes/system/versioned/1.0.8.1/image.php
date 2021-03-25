<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Image extends html_element {

    public $prefix = DIR_FS_CATALOG;

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

      parent::__construct($parameters);
    }

    /**
     * If this points to a valid image.
     * @return boolean
     */
    public function is_valid() {
      if ((empty($this->parameters['src'])
          || !is_file("{$this->prefix}{$this->parameters['src']}"))
        && (!defined('DEFAULT_IMAGE') || Text::is_empty(DEFAULT_IMAGE)))
      {
        return false;
      }

      if ( (CONFIG_CALCULATE_IMAGE_SIZE === 'true')
        && (empty($this->parameters['width']) && empty($this->parameters['height']))
        && (false === $this->size()) )
      {
        return false;
      }
    }

    public function set_prefix(string $prefix) {
      $this->prefix = $prefix;
      return $this;
    }

    /**
     * Set the correct CSS class for responsive images.
     */
    public function set_responsive() {
      $this->append_css('img-fluid');
      return $this;
    }

    /**
     * Calculate and parameterize the image size.
     * @return boolean
     */
    public function size() {
      if ($image_size = @getimagesize($this->get('src'))) {
        if (empty($this->parameters['width']) && empty($this->parameters['height'])) {
          $this->set('width', $image_size[0]);
          $this->set('height', $image_size[1]);
        } elseif (empty($this->parameters['width'])) {
          $ratio = $this->parameters['height'] / $image_size[1];
          $this->set('width', (int)($image_size[0] * $ratio));
        } else {
          $ratio = $this->parameters['width'] / $image_size[0];
          $this->set('height', (int)($image_size[1] * $ratio));
        }

        return true;
      }

      return (IMAGE_REQUIRED !== 'false');
    }

    /**
     *
     * @return string
     */
    public function __toString() {
      if (defined('DEFAULT_IMAGE') && !Text::is_empty(DEFAULT_IMAGE) && !is_file("{$this->prefix}$src")) {
        $src = DEFAULT_IMAGE;
      } elseif ( (empty($src) || ($src === 'images/')) && (IMAGE_REQUIRED === 'false') ) {
        return '';
      }

      if ( (CONFIG_CALCULATE_IMAGE_SIZE === 'true')
        && (empty($this->parameters['width']) && empty($this->parameters['height']))
        && (false === $this->size()) )
      {
        return '';
      }

// alt is added as the img title even if  null to prevent browsers from outputting
// the image filename as default
      if (!isset($this->parameters['title'])) {
        $this->set('title', $this->parameters['alt'] ?? '');
      }

      return '<img' . $this->stringify_parameters() . ' />';
    }

  }
