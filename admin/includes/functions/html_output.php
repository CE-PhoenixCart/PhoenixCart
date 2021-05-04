<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  function phoenix_parameterize($query, $delimiter = '&', $joiner = '=') {
    $parameters = [];
    foreach (explode($delimiter, $query) as $parameter) {
      $pair = explode($joiner, $parameter, 2);
      if (!empty($pair[0])) {
        $parameters[$pair[0]] = $pair[1] ?? null;
      }
    }

    return $parameters;
  }

  function phoenix_normalize($attributes) {
    $parameters = [];
    foreach (preg_split('{"[^"]*"(*SKIP)(*FAIL)|\s+}', $attributes) as $parameter) {
      $pair = explode('=', $parameter, 2);
      if (!empty($pair[0])) {
        $parameters[$pair[0]] = isset($pair[1]) ? trim($pair[1], '"') : null;
      }
    }

    return $parameters;
  }

  function phoenix_append_css($parameter, $input) {
    if (isset($parameter)) {
      $pair = explode('=', $parameter, 2);
      if (isset($pair[1]) && ('class' === $pair[0])) {
        $input->append_css(trim($pair[1], '"'));
      }
    }
  }

////
// The HTML href link wrapper function
  function tep_href_link($page = '', $parameters = '', $connection = 'SSL', $add_session_id = true) {
    return Guarantor::ensure_global('Admin')->link($page, phoenix_parameterize($parameters), $add_session_id);
  }

  function tep_catalog_href_link($page = '', $parameters = '') {
    return Guarantor::ensure_global('Admin')->catalog($page, phoenix_parameterize($parameters));
  }

////
// The HTML image wrapper function
  function tep_image($src, $alt = '', $width = '', $height = '', $parameters = '', $responsive = true, $bootstrap_css = '') {
    $image = new Image($src, phoenix_normalize($parameters));
    $image->set_prefix(DIR_FS_ADMIN);

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

////
// DEPRECATE THIS ASAP
  function tep_black_line() {
    trigger_error('The tep_black_line function has been deprecated.', E_USER_DEPRECATED);
    return null;
  }

////
// DEPRECATE THIS ASAP
  function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
    trigger_error('The tep_draw_separator function has been deprecated.', E_USER_DEPRECATED);
    return null;
  }

////
// javascript to dynamically update the states/provinces list when the country is changed
// TABLES: zones
  function tep_js_zone_list($country, $form, $field) {
    return (string)(new zone_js($country, $form, $field));
  }

////
// Output a form
  function tep_draw_form($name, $action, $parameters = '', $method = 'post', $params = '') {
    return new Form(
      $name,
      Guarantor::ensure_global('Admin')->link($action, phoenix_parameterize($parameters)),
      $method,
      phoenix_normalize($params));
  }

////
// Output a form input field
  function tep_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true, $class = 'class="form-control"') {
    $input = new Input($name, phoenix_normalize($parameters), $type);
    phoenix_append_css($class, $input);

    if ($reinsert_value) {
      $input->default_value($value ?? '');
    } elseif (isset($value) && !Text::is_empty($value)) {
      $input->set('value', $value);
    }

    return "$input";
  }

////
// Output a form filefield
  function tep_draw_file_field($name) {
    return (string)(new Input($name, [], 'file'));
  }

////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
  function tep_draw_selection_field($name, $type, $value = '', $checked = false, $parameters = '') {
    $input = new Tickable($name, phoenix_normalize($parameters), $type);

    if (!Text::is_empty($value)) {
      $input->set('value', $value);
    }

    if ( $checked ) {
      $input->tick();
    } else {
      $input->tick_if_requested();
    }

    return "$input";
  }

////
// Output a form checkbox field
// DEPRECATE this from Phoenix over time.
  function tep_draw_checkbox_field($name, $value = '', $checked = false, $compare = '') {
    return tep_draw_selection_field($name, 'checkbox', $value, ($checked || (!Text::is_empty($compare) && ($value == $compare))));
  }

////
// DEPRECATE this from Phoenix.
  function tep_draw_radio_field($name, $value = '', $checked = false, $compare = '') {
    trigger_error('The tep_draw_radio_field function has been deprecated.', E_USER_DEPRECATED);
    return tep_draw_selection_field($name, 'radio', $value, ($checked || (!Text::is_empty($compare) && ($value == $compare))));
  }

////
// Output a form textarea field
// The $wrap parameter is no longer used in the core xhtml template
  function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true, $class = 'class="form-control"') {
    $textarea = new Textarea($name, phoenix_normalize($parameters));
    $textarea->set('cols', $width)->set('rows', $height);

    phoenix_append_css($class, $textarea);

    if ( $reinsert_value && is_string(Request::value($name)) ) {
      $textarea->retain_text();
    } elseif (!Text::is_empty($text)) {
      $textarea->set_text($text);
    }

    return "$textarea";
  }

////
// Output a form hidden field
  function tep_draw_hidden_field($name, $value = '', $parameters = '') {
    $input = new Input($name, phoenix_normalize($parameters), 'hidden');

    if (Text::is_empty($value)) {
      if ( is_string($requested_value = Request::value($name)) ) {
        $input->set('value', $requested_value);
      }
    } else {
      $input->set('value', $value);
    }

    return "$input";
  }

////
// Hide form elements
  function tep_hide_session_id() {
    if (defined('SID') && !Text::is_empty(SID)) {
      return new Input(session_name(), ['type' => 'hidden', 'value' => session_id()]);
    }

    return '';
  }

////
// Output a form pull down menu
  function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $class = 'class="form-control"') {
    $select = new Select($name, $values, phoenix_normalize($parameters));

    phoenix_append_css($class, $select);

    if ( !empty($default) ) {
      $select->set_selection($default);
    }

    return $select;
  }

////
// Output a jQuery UI Button
  function tep_draw_button($title = null, $icon = null, $link = null, $priority = null, $params = null) {
    trigger_error('The tep_draw_button function has been deprecated.', E_USER_DEPRECATED);
    static $button_counter = 1;

    if ( !isset($params['type']) || !in_array($params['type'], ['submit', 'button', 'reset']) ) {
      $params['type'] = 'submit';
    }

    if ( ($params['type'] == 'submit') && isset($link) ) {
      $params['type'] = 'button';
    }

    if (!isset($priority)) {
      $priority = 'secondary';
    }

    $button = '<span class="tdbLink">';

    if ( ($params['type'] == 'button') && isset($link) ) {
      $button .= '<a id="tdb' . $button_counter . '" href="' . $link . '"';

      if ( isset($params['newwindow']) ) {
        $button .= ' target="_blank" rel="noreferrer"';
      }
      $close = '</a>';
    } else {
      $button .= '<button id="tdb' . $button_counter . '" type="' . Text::output($params['type']) . '"';
      $close = '</button>';
    }

    if ( isset($params['params']) ) {
      $button .= ' ' . $params['params'];
    }

    $button .= ">$title$close";

    $button .= '</span><script>$("#tdb' . $button_counter . '").button(';

    $args = [];

    if ( isset($icon) ) {
      if ( !isset($params['iconpos']) ) {
        $params['iconpos'] = 'left';
      }

      if ( $params['iconpos'] == 'left' ) {
        $args[] = 'icons:{primary:"ui-icon-' . $icon . '"}';
      } else {
        $args[] = 'icons:{secondary:"ui-icon-' . $icon . '"}';
      }
    }

    if (empty($title)) {
      $args[] = 'text:false';
    }

    if (!empty($args)) {
      $button .= '{' . implode(',', $args) . '}';
    }

    $button .= ').addClass("ui-priority-' . $priority . '").parent().removeClass("tdbLink");</script>';

    $button_counter++;

    return $button;
  }

////
// Output a Bootstrap Button
  function tep_draw_bootstrap_button($title = '', $icon = null, $link = null, $priority = 'secondary', $params = [], $style = null) {
    if (isset($params['params'])) {
      $params = array_merge($params, phoenix_normalize($params['params']));
      unset($params['params']);
    }

    return (string)(new Button($title ?? '', $icon, $style, $params ?? [], $link));
  }

  // review stars
  function tep_draw_stars($rating = 0) {
    return (string)(new star_rating((float)$rating));
  }

