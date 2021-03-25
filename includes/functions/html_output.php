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

////
// Output a form
  function tep_draw_form($name, $action, $method = 'post', $parameters = '', $tokenize = false) {
    $form = new Form($name, $action, $method, phoenix_normalize($parameters));

    if ( $tokenize && isset($_SESSION['sessiontoken']) ) {
      $form->hide('formid', Text::output($_SESSION['sessiontoken']));
    }

    return "$form";
  }

////
// Output a form input field
  function tep_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true, $class = 'class="form-control"') {
    $parameters = phoenix_normalize($parameters);

    if (isset($class)) {
      $pair = explode('=', $class, 2);
      if (isset($pair[1]) && ('class' === $pair[0])) {
        $parameters['class'] = trim($pair[1], '"');
      }
    }

    $input = new Input($name, $parameters, $type);

    if (is_null($value)) {
    } elseif ($reinsert_value) {
      $input->default_value($value);
    } elseif (!Text::is_empty($value)) {
      $input->set('value', $value);
    }

    return "$input";
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
  function tep_draw_checkbox_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'checkbox', $value, $checked, $parameters);
  }

////
// Output a form radio field
  function tep_draw_radio_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'radio', $value, $checked, $parameters);
  }

////
// Output a form textarea field
// The $wrap parameter is no longer used in the core xhtml template
  function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    $textarea = new Textarea($name, phoenix_normalize($parameters));
    $textarea->set('cols', $width)->set('rows', $height);

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
  function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    $select = new Select($name, $values, phoenix_normalize($parameters));

    if ( !empty($default) ) {
      $select->set_selection($default);
    }

    if ($required) {
      $select->set_required($required);
    }

    return $select;
  }

////
// Creates a pull-down list of countries
  function tep_get_country_list($name, $selected = '', $parameters = '') {
    trigger_error('The tep_get_country_list function has been deprecated.', E_USER_DEPRECATED);
    return Country::draw_menu($name, $selected, phoenix_normalize($parameters));
  }

////
// Output a jQuery UI Button
  function tep_draw_button($title = null, $icon = null, $link = null, $priority = null, $params = [], $style = null) {
    return (string)(new Button($title, $icon, $style, $params ?? [], $link));
  }

// review stars
  function tep_draw_stars($rating = 0) {
    return (string)(new star_rating((float)$rating));
  }

