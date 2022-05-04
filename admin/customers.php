<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $always_valid_actions = ['edit'];
  require 'includes/application_top.php';

  if (!$customer_data->has([ 'sortable_name', 'name', 'email_address', 'country_id', 'id' ])) {
    $messageStack->add_session(ERROR_PAGE_HAS_UNMET_REQUIREMENT, 'error');
    foreach ($customer_data->get_last_missing_abilities() as $missing_ability) {
      $messageStack->add_session($missing_ability);
    }

    Href::redirect($Admin->link('modules.php', ['set' => 'customer_data']));
  }

  require 'includes/segments/process_action.php';

  require 'includes/template_top.php';
  ?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col text-right align-self-center">
      <?=
      isset($_GET['action'])
      ? $Admin->button(IMAGE_CANCEL, 'fas fa-angle-left', 'btn-light', $Admin->link('customers.php')->retain_query_except(['action']))
      : (new Form('search', $Admin->link('customers.php'), 'get'))->hide_session_id()
        . '<div class="input-group">'
          . '<div class="input-group-prepend">'
            . '<span class="input-group-text">' . HEADING_TITLE_SEARCH . '</span>'
          . '</div>'
          . new Input('search')
        . '</div>'
      . '</form>'
      ?>
    </div>
  </div>

  <?php
  if ($action === 'edit' || $action === 'update') {
    $hooks =& $admin_hooks;
    $Template = new Template();
    $oscTemplate =& $Template;
    echo (new Form('customers', $Admin->link('customers.php')->retain_query_except()->set_parameter('action', 'update'), 'post'))
      ->hide('default_address_id', $customer_data->get('default_address_id', $customer_details));

    $cwd = getcwd();
    chdir(DIR_FS_CATALOG);

    $page_fields = $customer_data->get_fields_for_page('customers');
    $grouped_modules = $customer_data->get_grouped_modules();
    $customer_data_group_query = $db->query(sprintf(<<<'EOSQL'
SELECT customer_data_groups_id, customer_data_groups_name
 FROM customer_data_groups
 WHERE language_id = %d
 ORDER BY cdg_vertical_sort_order, cdg_horizontal_sort_order
EOSQL
      , (int)$_SESSION['languages_id']));

    while ($customer_data_group = $customer_data_group_query->fetch_assoc()) {
      if (empty($grouped_modules[$customer_data_group['customer_data_groups_id']])) {
        continue;
      }
      ?>

     <h5><?= $customer_data_group['customer_data_groups_name'] ?></h5>

      <?php
      foreach ((array)$grouped_modules[$customer_data_group['customer_data_groups_id']] as $module) {
        if (count(array_intersect(get_class($module)::PROVIDES, $page_fields)) > 0) {
          $module->display_input($customer_details);
        }
      }
    }

    chdir($cwd);

    echo $admin_hooks->cat('editForm');
    echo $admin_hooks->cat('injectFormDisplay');

    echo new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success btn-block btn-lg');
    ?>

  </form>

<?php
  } else {
    $customers_sql = $customer_data->build_read([
        'id',
        'sortable_name',
        'email_address',
        'country_id',
        'date_account_created',
        'date_account_last_modified',
        'date_last_logon',
        'date_last_logon',
      ],
      'customers');
    $keywords = null;
    if (!Text::is_empty($_GET['search'] ?? '')) {
      $keywords = Text::input($_GET['search']);
      $customers_sql = $customer_data->add_search_criteria($customers_sql, $keywords);
      $admin_hooks->set('customersListButtons', 'reset_keywords', function () {
        return $GLOBALS['Admin']->button(IMAGE_RESET, 'fas fa-angle-left', 'btn-light', $GLOBALS['Admin']->link('customers.php'));
      });
    }
    $customers_sql = $customer_data->add_order_by($customers_sql, ['sortable_name']);

    $table_definition = [
      'columns' => [
        [
          'name' => TABLE_HEADING_NAME,
          'function' => function (&$row) use ($customer_data) {
              return $customer_data->get('sortable_name', $row);
            },
        ],
        [
          'name' => TABLE_HEADING_ACCOUNT_CREATED,
          'class' => 'text-right',
          'function' => function (&$row) use ($customer_data) {
            return Date::abridge($customer_data->get('date_account_created', $row));
          },
        ],
        [
          'name' => TABLE_HEADING_ACTION,
          'class' => 'text-right',
          'function' => function ($row) use ($customer_data) {
            return (isset($row['info']->id) && ($row['info']->id === $customer_data->get('id', $row)))
                 ? '<i class="fas fa-chevron-circle-right text-info"></i>'
                 : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
          },
        ],
      ],
      'count_text' => TEXT_DISPLAY_NUMBER_OF_CUSTOMERS,
      'hooks' => [
        'button' => 'customersListButtons',
      ],
      'page' => $_GET['page'] ?? null,
      'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
      'sql' => $customers_sql,
    ];
    $table_definition['split'] = new Paginator($table_definition);
    $table_definition['function'] = function (&$row) use ($customer_data, &$table_definition) {
      $link = $GLOBALS['Admin']->link('customers.php')->retain_query_except(['action'])->set_parameter('cID', $customer_data->get('id', $row));
      if (!isset($table_definition['info']) && (!isset($_GET['cID']) || ($_GET['cID'] === $customer_data->get('id', $row)))) {
        $reviews_query = $GLOBALS['db']->query("SELECT COUNT(*) AS number_of_reviews FROM reviews WHERE customers_id = " . (int)$customer_data->get('id', $row));
        $reviews = $reviews_query->fetch_assoc();
        $row['number_of_reviews'] = $reviews['number_of_reviews'];
        $customer_data->get([
          'sortable_name',
          'name',
          'email_address',
          'country_name',
          'id',
          'number_of_logons',
          'date_last_logon',
          'date_account_last_modified',
          'date_account_created',
        ], $row);
        $table_definition['info'] = new objectInfo($row);
        $row['info'] = &$table_definition['info'];

        $row['onclick'] = $link->set_parameter('action', 'edit');
        $row['css'] = ' class="table-active"';
      } else {
        $row['onclick'] = $link;
        $row['css'] = '';
      }
    };

    $table_definition['split']->display_table();
  }

  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
