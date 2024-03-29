<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License

  mime.php - a class to assist in building mime-HTML eMails

  The original class was made by Richard Heyes <richard@phpguru.org>

  Modified by Jan Wildeboer, et. al.
*/

  class mime {

    protected $_encoding;
    protected $_subparts = [];
    protected $_encoded = [];
    protected $_headers = [];
    protected $_body;
    protected $lf = "\r\n";

/**
 * Constructor.
 *
 * Sets up the object.
 *
 * @param $body   - The body of the mime part if any.
 * @param $params - An associative array of parameters:
 *                  content_type - The content type for this part eg multipart/mixed
 *                  encoding     - The encoding to use, 7bit, base64, or quoted-printable
 *                  cid          - Content ID to apply
 *                  disposition  - Content disposition, inline or attachment
 *                  dfilename    - Optional filename parameter for content disposition
 *                  description  - Content description
 * @access public
 */
    function __construct($body, $params = []) {
// Make sure we use the correct linefeed sequence
      $this->lf = (EMAIL_LINEFEED === 'CRLF') ? "\r\n" : "\n";

      foreach ($params as $key => $value) {
        switch ($key) {
          case 'content_type':
            $headers['Content-Type'] = $value . (isset($charset) ? '; charset="' . $charset . '"' : '');
            break;
          case 'encoding':
            $this->_encoding = $value;
            $headers['Content-Transfer-Encoding'] = $value;
            break;
          case 'cid':
            $headers['Content-ID'] = '<' . $value . '>';
            break;
          case 'disposition':
            $headers['Content-Disposition'] = $value . (isset($dfilename) ? '; filename="' . $dfilename . '"' : '');
            break;
          case 'dfilename':
            if (isset($headers['Content-Disposition'])) {
              $headers['Content-Disposition'] .= '; filename="' . $value . '"';
            } else {
              $dfilename = $value;
            }
            break;
          case 'description':
            $headers['Content-Description'] = $value;
            break;
          case 'charset':
            if (isset($headers['Content-Type'])) {
              $headers['Content-Type'] .= '; charset="' . $value . '"';
            } else {
              $charset = $value;
            }
            break;
        }
      }

// Default content-type
      if (!isset($headers['Content-Type'])) {
        $headers['Content-Type'] = 'text/plain';
      }

// Assign stuff to member variables
      $this->_headers = $headers;
      $this->_body = $body;
    }

/**
 * encode()
 *
 * Encodes and returns the email. Also stores
 * it in the encoded member variable
 *
 * @return An associative array containing two elements,
 *         body and headers. The headers element is itself
 *         an indexed array.
 * @access public
 */
    function encode() {
      $encoded = $this->_encoded;

      if ([] === $this->_subparts) {
        $encoded['body'] = $this->_getEncodedData($this->_body, $this->_encoding) . $this->lf;
      } else {
        $boundary = '=_' . md5(uniqid(mt_rand()) . microtime());
        $this->_headers['Content-Type'] .= ';' . $this->lf . chr(9) . 'boundary="' . $boundary . '"';

// Add body parts to $subparts
        foreach ($this->_subparts as $_subpart) {
          $tmp = $_subpart->encode();

          $headers = [];
          foreach ($tmp['headers'] as $key => $value) {
            $headers[] = $key . ': ' . $value;
          }

          $subparts[] = implode($this->lf, $headers) . $this->lf . $this->lf . $tmp['body'];
        }

        $encoded['body'] = '--' . $boundary . $this->lf . implode('--' . $boundary . $this->lf, $subparts) . '--' . $boundary.'--' . $this->lf;
      }

// Add headers to $encoded
      $encoded['headers'] = $this->_headers;

      return $encoded;
    }

/**
 * &addSubPart()
 *
 * Adds a subpart to current mime part and returns
 * a reference to it
 *
 * @param $body   The body of the subpart, if any.
 * @param $params The parameters for the subpart, same
 *                as the $params argument for constructor.
 * @return The part you just added.
 * @access public
 */
    function addSubPart($body, $params) {
      $this->_subparts[] = new mime($body, $params);

      return $this->_subparts[count($this->_subparts) - 1];
    }

/**
 * _getEncodedData()
 *
 * Returns encoded data based upon encoding passed to it
 *
 * @param $data     The data to encode.
 * @param $encoding The encoding type to use, 7bit, base64,
 *                  or quoted-printable.
 * @access private
 */
    function _getEncodedData($data, $encoding) {
      switch ($encoding) {
       case '7bit':
         return $data;
         break;
       case 'quoted-printable':
         return quoted_printable_encode($data);
         break;
       case 'base64':
         return rtrim(chunk_split(base64_encode($data), 76, $this->lf));
         break;
      }
    }

/**
 * quotedPrintableEncode()
 *
 * Encodes data to quoted-printable standard.
 *
 * @param $input    The data to encode
 * @param $line_max Optional max line length. Should
 *                  not be more than 76 chars
 *
 * @access private
 */
    function _quotedPrintableEncode($input , $line_max = 76) {
      $lines = preg_split("/\r\n|\r|\n/", $input);
      $eol = $this->lf;
      $escape = '=';
      $output = '';

      foreach ($lines as $line) {
        $linlen = strlen($line);
        $newline = '';

        for ($i = 0; $i < $linlen; $i++) {
          $char = substr($line, $i, 1);
          $dec = ord($char);

// convert space at eol only
          if ( ($dec == 32) && ($i == ($linlen - 1)) ) {
            $char = '=20';
          } elseif ($dec == 9) {
// Do nothing if a tab.
          } elseif ( ($dec == 61) || ($dec < 32 ) || ($dec > 126) ) {
            $char = $escape . strtoupper(sprintf('%02s', dechex($dec)));
          }

// $this->lf is not counted
          if ((strlen($newline) + strlen($char)) >= $line_max) {
// soft line break; " =\r\n" is okay
            $output .= $newline . $escape . $eol;
            $newline = '';
          }
          $newline .= $char;
        }
        $output .= $newline . $eol;
      }
// Don't want last crlf
      $output = substr($output, 0, -1 * strlen($eol));

      return $output;
    }
  }
