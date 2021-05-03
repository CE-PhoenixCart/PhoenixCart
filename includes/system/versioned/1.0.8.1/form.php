<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Form extends named_html_element {

    protected $stash = [];
    protected $tokenize;
    protected $hide_session = false;

    /**
     *
     * @param string $name
     * @param string $action
     * @param string $method
     * @param array $parameters
     * @param boolean $tokenize
     */
    public function __construct(string $name, string $action,
      string $method = 'post', array $parameters = [], bool $tokenize = null)
    {
      parent::__construct($name, [
        'action' => $action,
        'method' => $method,
      ] + $parameters);

      if (is_null($tokenize)) {
        $tokenize = 'post' === $method;
      }

      if ($tokenize && isset($_SESSION['sessiontoken'])) {
        $this->stash['formid'] = $_SESSION['sessiontoken'];
      }
    }

    /**
     *
     * @return string
     */
    public function draw() {
      $form = '<form'  . $this->stringify_parameters() . '>';

      foreach ($this->stash as $name => $value) {
        $input = new Input($name, ['type' => 'hidden']);
        if (!Text::is_empty($value)) {
          $input->set('value', $value);
        } elseif ( is_string($request_value = Request::value($name)) ) {
          $input->set('value', $request_value);
        }
        $form .= $input;
      }

      return $form;
    }

    public function close() {
      return '</form>';
    }

    /**
     *
     * @param string $name
     * @param string $value
     */
    public function hide(string $name, string $value) {
      $this->stash[$name] = $value;
      return $this;
    }

    /**
     * Add the session ID to the values to be hidden.
     */
    public function hide_session_id() {
      if (Session::is_started() && !Text::is_empty($GLOBALS['SID'] ?? SID)) {
        $this->hide(session_name(), session_id());
      }

      return $this;
    }

    /**
     *
     * @return string
     */
    public function __toString() {
      return $this->draw();
    }

    /**
     *
     * @param string|array $action The action or actions to validate.
     * @return boolean
     */
    public static function validate_action_is($action = 'process') {
      $requested_action = Request::value('action');
      $formid = $_POST['formid'] ?? $_GET['formid'] ?? null;
      if (is_null($requested_action)
       || is_null($formid)
       || (strlen($formid) !== strlen($_SESSION['sessiontoken'])))
      {
        return false;
      }

      $matched = is_array($action)
               ? in_array($requested_action, $action)
               : ($requested_action === $action);

      return ($matched && (hash_equals($_SESSION['sessiontoken'], $formid)));
    }

/**
 * For use by injectFormVerify hooks and Apps that need to block form processing.
 */
    public static function block_processing() {
      $GLOBALS['error'] = true;
    }

    public static function is_valid() {
      return !($GLOBALS['error'] ?? false);
    }

    public static function reset_session_token() {
      $_SESSION['sessiontoken'] = bin2hex(random_bytes(32));
    }

  }
