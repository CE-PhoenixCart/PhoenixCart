<div class="card mb-2 bm-manufacturers">
  <div class="card-header"><?= MODULE_BOXES_MANUFACTURERS_BOX_TITLE ?></div>
<?php
  if ($number_of_rows <= MODULE_BOXES_MANUFACTURERS_MAX_LIST) {
// Display a list
?>
  <div class="list-group list-group-flush">
<?php
    while ($manufacturer = $manufacturers_query->fetch_assoc()) {
      $manufacturers_name = (isset($_GET['manufacturers_id']) && ($_GET['manufacturers_id'] == $manufacturer['id']))
                          ? '<strong>' . $manufacturer['text'] .'</strong>'
                          : $manufacturer['text'];
      echo '<a class="list-group-item list-group-item-action" href="', $GLOBALS['Linker']->build('index.php', ['manufacturers_id' => $manufacturer['id']]), '">', $manufacturers_name, '</a>';
    }
?>
  </div>
<?php
  } else {
// Display a drop-down
    $manufacturers = array_merge(
      [['id' => '', 'text' => PULL_DOWN_DEFAULT]],
      $GLOBALS['db']->fetch_all($manufacturers_query));
    $menu = new Select('manufacturers_id', $manufacturers, ['onchange' => 'this.form.submit();', 'class' => 'custom-form-input w-100']);
    if (isset($_GET['manufacturers_id'])) {
      $menu->set_selection($_GET['manufacturers_id']);
    }
?>
  <ul class="list-group list-group-flush">
    <li class="list-group-item">
      <?= (new Form('manufacturers', $GLOBALS['Linker']->build('index.php', [], false), 'get'))->hide_session_id() ?>
        <?= $menu ?>
      </form>
    </li>
  </ul>
<?php
  }
?>
</div>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>
