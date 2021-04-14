<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Search {

    protected $tokens = [];

    /**
     *
     * @param string $search_str
     */
    public function __construct(string $search_str) {
      $search_str = trim(strtolower($search_str));

      $strings = explode('"', $search_str);
      for ($i = 0, $n = count($strings); $i < $n; $i += 2) {
        $string = trim($strings[$i]);
        if ('' !== $string) {
          $this->tokenize($string);
        }

// If there is a second string, it will be quoted
// Use ?? '' because there won't always be a second string
// This will implicitly add a quote at the end of the string if they are unbalanced
        $string = trim($strings[$i + 1] ?? '');
        if ('' !== $string) {
          $this->tokens[] = $string;
        }
      }
    }

    /**
     *
     * @param string $search_str
     */
    protected function tokenize(string $search_str = '') {
// Break up $search_str on whitespace
      foreach (preg_split('{[[:space:]]+}', $search_str) as $token) {
        $ltrimmed = ltrim($token, '(');
        $ltrim_count = strlen($token) - strlen($ltrimmed);
        $token = rtrim($ltrimmed, ')');

        $this->tokens = array_merge(
          $this->tokens,
          array_fill(0, $ltrim_count, '('),
          ('' === $token) ? [] : [$token],
          array_fill(0, strlen($ltrimmed) - strlen($token), ')'));
      }
    }

    public function ensure_operator_separated() {
// add default logical operators if needed
      $tokens = [array_shift($this->tokens)];
      while (count($this->tokens)) {
        $token = array_shift($this->tokens);
        if ( !in_array(end($tokens), ['and', 'or', '('])
          && !in_array($token, ['and', 'or', ')']) )
        {
          $tokens[] = ADVANCED_SEARCH_DEFAULT_OPERATOR;
        }

        $tokens[] = $token;
      }

      return $tokens;
    }

    /**
     *
     * @param string[] $tokens
     * @return boolean
     */
    public static function is_balanced($tokens) {
      $surplus = 0;
      $open_count = 0;
      foreach ($tokens as $token) {
        if ('(' === $token) {
          $open_count++;
        } else if (')' === $token) {
          $open_count--;
        } else if ( ('and' === $token) || ('or' === $token) ) {
          $surplus--;
        } else if ($token) {
          $surplus++;
        }
      }

      return ( (0 < $surplus) && ($open_count == 0) );
    }

    /**
     *
     * @param string $search_string
     * @return null|string[]
     */
    public static function build(string $search_string) {
      $search = new Search($search_string);
      $tokens = $search->ensure_operator_separated();
      return static::is_balanced($tokens)
           ? $tokens
           : null;
    }

  }
