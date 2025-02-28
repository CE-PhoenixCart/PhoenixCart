<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>

  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th><?= TABLE_HEADING_TITLE ?></th>
          <th><?= TABLE_HEADING_MODULE ?></th>
          <th class="w-50"><?= TABLE_HEADING_INFO ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($security_checks->generate_modules() as $module) {
          if ( ${$module['class']}->pass($security_checks->fetch_curl_result($module['class'])) ) {
            $output = '';
            $fa = 'fas fa-fw fa-check-circle text-success';
          } else {
            $output = ${$module['class']}->get_message();

            switch (${$module['class']}->type) {
              case 'info':
                $fa = 'fas fa-fw fa-info-circle text-info';
                break;
              case 'warning':
              case 'error':
                $fa = 'fas fa-fw fa-exclamation-circle text-danger';
                break;
            }
          }

          echo '<tr>',
                 '<td><i class="', $fa, '"></i> ', htmlspecialchars($module['title']), '</td>',
                 '<td>', htmlspecialchars($module['class']), '</td>',
                 '<td>', $output, '</td>',
               '</tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
  