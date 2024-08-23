<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';
  
  $get_addons_link = '';
  $get_addons_link .= '<div class="btn-group" role="group">';
    $get_addons_link .= '<button type="button" class="btn btn-dark mr-2 dropdown-toggle" data-toggle="dropdown" aria-expanded="false">';
      $get_addons_link .= GET_ADDONS;
    $get_addons_link .= '</button>';
    $get_addons_link .= '<div class="dropdown-menu">';
    foreach (GET_ADDONS_LINKS as $k => $v) {
      $get_addons_link .= '<a class="dropdown-item" target="_blank" href="' . $v . '">' . $k . '</a>';
    }
    $get_addons_link .= '</div>';
  $get_addons_link .= '</div>';

  $link = $Admin->link()->retain_query_except(['oID', 'action']);
  require 'includes/segments/process_action.php';

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_SLUG,
        'is_heading' => false,
        'function' => function ($row) {
          return $row['slug'];
        },
      ],
      [
        'name' => TABLE_HEADING_TITLE,
        'is_heading' => false,
        'function' => function ($row) {
          return $row['title'];
        },
      ],
      [
        'name' => TABLE_HEADING_DATE_ADDED,
        'is_heading' => false,
        'function' => function ($row) {
          return $row['date_added'];
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-right',
        'function' => function ($row) {
          return (isset($row['info']->id) && ($row['id'] == $row['info']->id) )
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_OUTGOING,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'oID',
    'db_id' => 'id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => "SELECT * FROM outgoing_tpl ORDER BY slug",
  ];

  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $row['onclick'] = $GLOBALS['link']->set_parameter(
      'oID', $row['id']);

    if (!isset($table_definition['info'])
      && (!isset($_GET['oID']) || ($_GET['oID'] == $row['id']))
      && !Text::is_prefixed_by($GLOBALS['action'], 'new'))
    {
      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['css'] = ' class="table-active"';
      $row['onclick'] = (clone $row['onclick'])->set_parameter('action', 'edit');
    } else {
      $row['css'] = '';
    }
  };

  $table_definition['split'] = new Paginator($table_definition);
  
  $slug_array = [];
  $slug_a = [];
  $available_slugs = glob(DIR_FS_CATALOG . 'includes/modules/outgoing/*.php');
  foreach ($available_slugs as $as) {
    $slug_array[] = ['id'   => basename($as, '.php'),
                     'text' => basename($as, '.php')];
    $slug_a[] = basename($as, '.php');
  }

  $slug_b = [];
  $slug_query = $db->query("select slug from outgoing_tpl order by slug");
  while ($slug = $slug_query->fetch_assoc()) {
    $slug_b[] = $slug['slug'];
  }

  $missing_slugs = array_diff($slug_a, $slug_b);

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE; ?></h1>
    </div>
    <div class="col text-right align-self-center">
      <?=
      $get_addons_link,
      $Admin->button(GET_HELP, '', 'btn-dark mr-2', GET_HELP_LINK, ['newwindow' => true]),
      $admin_hooks->cat('extraButtons'),
      empty($action)
      ? $Admin->button(BUTTON_INSERT_NEW_SLUG, 'fas fa-id-card', 'btn-danger', $Admin->link('outgoing_tpl.php', ['action' => 'new']))
      : $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', $link)
      ?>
    </div>
  </div>

<?php
  if (sizeof($missing_slugs) > 0) {
    $missing = implode(', ', $missing_slugs);
    echo sprintf(MISSING_SLUGS, $missing);
  }
  
  $table_definition['split']->display_table();

  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
