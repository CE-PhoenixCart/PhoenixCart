<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($table_definition['info']->code)) {
    $mInfo =& $table_definition['info'];
    $heading = $mInfo->title;

    $link = $Admin->link('modules.php')->retain_query_except()->set_parameter('module', $mInfo->code);
    if (isset($_GET['list']) && ('new' === $_GET['list'])) {
      $contents = ['form' => new Form('install_module', $link->delete_parameter('list')->set_parameter('action', 'install'))];
      $contents[] = [
        'class' => 'text-center',
        'text' => new Button(IMAGE_MODULE_INSTALL, 'fas fa-plus', 'btn-warning'),
      ];

      if (isset($mInfo->api_version)) {
        $contents[] = ['text' => '<i class="fas fa-info-circle text-dark me-2"></i><strong>' . TEXT_INFO_API_VERSION . '</strong> ' . $mInfo->api_version];
      }

      $contents[] = ['text' => $mInfo->description];
    } else {
      $keys = '';
      foreach ($mInfo->keys as $value) {
        $keys .= '<strong>' . $value['title'] . '</strong><br>';

        if ($value['use_function']) {
          if (strpos($value['use_function'], '->')) {
            $class_method = explode('->', $value['use_function']);
            $use_function = [Guarantor::ensure_global($class_method[0]), $class_method[1]];
          } else {
            $use_function = $value['use_function'];
          }

          if (is_callable($use_function)) {
            $keys .= call_user_func($use_function, $value['value']);
          } else {
            $keys .= '0';
            $messageStack->add_session(sprintf(
              WARNING_INVALID_USE_FUNCTION,
              $value['use_function'],
              $value['title']
             ), 'warning');
          }
        } else {
          $keys .= $value['value'] . '<br>';
        }

        $keys .= '<br><br>';
      }
      $keys = Text::rtrim_once($keys, '<br><br>');

      $contents = ['form' => new Form('remove_module', (clone $link)->set_parameter('action', 'remove'))];
      $contents[] = [
        'class' => 'text-center',
        'text' => $Admin->button(IMAGE_EDIT, 'fas fa-plus', 'btn-warning me-2', $link->set_parameter('action', 'edit'))
                . new Button(IMAGE_MODULE_REMOVE, 'fas fa-minus', 'btn-warning'),
      ];

      if (isset($mInfo->api_version)) {
        $contents[] = ['text' => '<i class="fas fa-info-circle text-dark me-2"></i><strong>' . TEXT_INFO_API_VERSION . '</strong> ' . $mInfo->api_version];
      }

      $contents[] = ['text' => $mInfo->description];
      $contents[] = [
        'class' => 'text-break',
        'text' => $keys,
      ];
    }
  }
