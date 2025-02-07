<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $cID = (int)$_GET['cID'];
  
  $hooks =& $admin_hooks;
  $Template = new Template('default');
    
  echo (new Form('customers', $Admin->link('customers.php')->retain_query_except()->set_parameter('action', 'update'), 'post'))
      ->hide('default_address_id', $customer_data->get('default_address_id', $customer_details));

  $cwd = getcwd();
  chdir(DIR_FS_CATALOG);

  $page_fields = $customer_data->get_fields_for_page('customers');
  $grouped_modules = $customer_data->get_grouped_modules();
  $customer_data_group_query = $db->query(sprintf(<<<'EOSQL'
SELECT *
 FROM customer_data_groups
 WHERE language_id = %d
 ORDER BY cdg_vertical_sort_order
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
  ?>
  
  <div class="d-grid mt-2">
    <?= new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success btn-lg') ?>
  </div>
  
  </form>
