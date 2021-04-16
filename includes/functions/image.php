<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

////
// The HTML image wrapper function
  function tep_image(
    string $src,
    string $alt = '',
    string $width = '',
    string $height = '',
    string $parameters = '',
    $responsive = true,
    string $bootstrap_css = '')
  {
    $image = new Image($src, phoenix_normalize($parameters));

    if (!Text::is_empty($alt)) {
      $image->set('alt', $alt);
    }

    if (!Text::is_empty($width)) {
      $image->set('width', $width);
    }

    if (!Text::is_empty($height)) {
      $image->set('height', $height);
    }

    if ($responsive !== true) {
      $image->set_responsive(false);
    }

    if (!Text::is_empty($bootstrap_css)) {
      $image->append_css($bootstrap_css);
    }

    return "$image";
  }
