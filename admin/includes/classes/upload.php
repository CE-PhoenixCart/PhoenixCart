<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class upload {

    public $file, $filename, $destination, $permissions, $extensions, $tmp_filename, $message_location;

    public function __construct($file = '', $destination = '', $permissions = '777', $extensions = '') {
      $this->set_file($file);
      $this->set_destination($destination);
      $this->set_permissions($permissions);
      $this->set_extensions($extensions);

      $this->set_output_messages('direct');

      if (!Text::is_empty($this->file) && !Text::is_empty($this->destination)) {
        $this->set_output_messages('session');

        return $this->parse() && $this->save();
      }
    }

    public function parse() {
      if (isset($_FILES[$this->file])) {
        $file = [
          'name' => $_FILES[$this->file]['name'],
          'type' => $_FILES[$this->file]['type'],
          'size' => $_FILES[$this->file]['size'],
          'tmp_name' => $_FILES[$this->file]['tmp_name'],
        ];
      } else {
        $file = [];
      }

      if ( Text::is_empty($file['tmp_name']) || ($file['tmp_name'] === 'none') || !is_uploaded_file($file['tmp_name']) ) {
        $this->message(WARNING_NO_FILE_UPLOADED, 'warning');

        return false;
      }

      if ((count($this->extensions) > 0) && !in_array(strtolower(substr($file['name'], strrpos($file['name'], '.')+1)), $this->extensions)) {
        $this->message(ERROR_FILETYPE_NOT_ALLOWED, 'error');

        return false;
      }

      $this->set_file($file);
      $this->set_filename($file['name']);
      $this->set_tmp_filename($file['tmp_name']);

      return $this->check_destination();
    }

    public function save() {
      if (substr($this->destination, -1) !== '/') {
        $this->destination .= '/';
      }

      if (move_uploaded_file($this->file['tmp_name'], $this->destination . $this->filename)) {
        chmod($this->destination . $this->filename, $this->permissions);

        $this->message(SUCCESS_FILE_SAVED_SUCCESSFULLY, 'success');

        return true;
      } else {
        $this->message(ERROR_FILE_NOT_SAVED, 'error');

        return false;
      }
    }

    public function set_file($file) {
      $this->file = $file;
    }

    public function set_destination($destination) {
      $this->destination = $destination;
    }

    public function set_permissions($permissions) {
      $this->permissions = octdec($permissions);
    }

    public function set_filename($filename) {
      $this->filename = $filename;
    }

    public function set_tmp_filename($filename) {
      $this->tmp_filename = $filename;
    }

    public function set_extensions($extensions) {
      if (is_array($extensions)) {
        $this->extensions = $extensions;
      } elseif (is_string($extensions) && Text::is_empty($extensions)) {
        $this->extensions = [];
      } else {
        $this->extensions = [$extensions];
      }
    }

    public function check_destination() {
      if (Path::is_writable($this->destination)) {
        return true;
      }

      $error_template = is_dir($this->destination)
                      ? ERROR_DESTINATION_NOT_WRITEABLE
                      : ERROR_DESTINATION_DOES_NOT_EXIST;
      $this->message(sprintf($error_template, $this->destination), 'error');

      return false;
    }

    public function message($text, $type) {
      if ('direct' === $this->message_location) {
        $GLOBALS['messageStack']->add($text, $type);
      } else {
        $GLOBALS['messageStack']->add_session($text, $type);
      }
    }

    public function set_output_messages($location) {
      switch ($location) {
        case 'session':
          $this->message_location = 'session';
          break;
        case 'direct':
        default:
          $this->message_location = 'direct';
          break;
      }
    }
  }
