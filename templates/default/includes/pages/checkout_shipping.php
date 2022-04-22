<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $hooks->register_pipeline('progress');

  $breadcrumb->add(NAVBAR_TITLE_1, $Linker->build('checkout_shipping.php'));
  $breadcrumb->add(NAVBAR_TITLE_2, $Linker->build('checkout_shipping.php'));

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4"><?= HEADING_TITLE ?></h1>

<?= (new Form('checkout_address', $Linker->build('checkout_shipping.php')))->hide('action', 'process') ?>

  <div class="row">
    <div class="col-sm-7">
      <h5 class="mb-1"><?= TABLE_HEADING_SHIPPING_METHOD ?></h5>
      <div>
        <?php
  if ($module_count > 0) {
    if ($free_shipping) {
?>
        <div class="alert alert-info mb-0" role="alert">
          <p class="lead"><b><?= FREE_SHIPPING_TITLE ?></b></p>
          <p class="lead"><?= sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)), new Input('shipping', ['value' => 'free_free'], 'hidden') ?></p>
        </div>
            <?php
    } else {
            ?>
        <table class="table border-right border-left border-bottom table-hover m-0">
          <?php
      $method_input = new Tickable('shipping', [
        'class' => 'custom-control-input',
      ], 'radio');
      $method_input->require();

      $n = count($quotes);
      foreach ($quotes as $quote) {
        $n2 = count($quote['methods']);
        foreach (($quote['methods'] ?? []) as $method) {
?>
          <tr class="table-selection">
            <td>
                  <?php
          echo $quote['module'];

          if (!Text::is_empty($quote['icon'] ?? '')) {
            echo '&nbsp;' . $quote['icon'];
          }

          if (isset($quote['error'])) {
            echo '<div class="form-text">' . $quote['error'] . '</div>';
          }

          if (!Text::is_empty($method['title'])) {
            echo '<div class="form-text">' . $method['title'] . '</div>';
          }
?>
            </td>
            <?php
          $method_value = "{$quote['id']}_{$method['id']}";
          $method_price = $currencies->format(Tax::price($method['cost'], $quote['tax'] ?? 0));
          if ( ($n > 1) || ($n2 > 1) ) {
?>
            <td class="text-right">
              <?php
            if (isset($quote['error'])) {
              echo '<div class="alert alert-error">' . $quote['error'] . '</div>';
            } else {
              $label_for = "d_{$method['id']}";
              echo '<div class="custom-control custom-radio custom-control-inline">';

              if (isset($_SESSION['shipping']['id'])) {
                $method_input->tick($method_value === $_SESSION['shipping']['id']);
              }
              echo $method_input
                     ->set('value', $method_value)
                     ->set('id', $label_for)
                     ->set('aria-describedby', $label_for);

              echo '<label class="custom-control-label" for="' . $label_for . '">' . $method_price . '</label>';
              echo '</div>';
            }
?>
            </td>
              <?php
          } else {
            $method_input = new Input('shipping', ['value' => $method_value], 'hidden');
?>
            <td class="text-right"><?= $method_price, $method_input ?></td>
              <?php
          }
?>
          </tr>
          <?php
        }
      }
    }
?>
        </table>
        <?php
    if ( !$free_shipping && (1 === $module_count) ) {
?>
        <p class="m-2 font-weight-lighter"><?= TEXT_ENTER_SHIPPING_INFORMATION ?></p>
          <?php
    }
  }
  $comments_textarea = new Textarea('comments', [
    'cols' => '60',
    'rows' => '5',
    'id' => 'inputComments',
    'placeholder' => ENTRY_COMMENTS_PLACEHOLDER,
  ]);

  if (isset($_SESSION['comments'])) {
    $comments_textarea->set_text($_SESSION['comments']);
  }
?>
      </div>
    </div>

    <div class="col-sm-5">
      <h5 class="mb-1">
        <?=
        TABLE_HEADING_SHIPPING_ADDRESS,
        sprintf(LINK_TEXT_EDIT, 'font-weight-lighter ml-3', $Linker->build('checkout_shipping_address.php'))
?>
      </h5>
      <div class="border">
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><?= SHIPPING_FA_ICON . $customer->make_address_label($_SESSION['sendto'], true, ' ', '<br>') ?></li>
        </ul>
      </div>
    </div>
  </div>

  <hr>

  <div class="form-group row">
    <label for="inputComments" class="col-form-label col-sm-4 text-left text-sm-right"><?= ENTRY_COMMENTS ?></label>
    <div class="col-sm-8">
      <?= $comments_textarea ?>
    </div>
  </div>

  <?= $hooks->cat('injectFormDisplay') ?>

  <div class="buttonSet">
    <div class="text-right"><?= new Button(BUTTON_CONTINUE_CHECKOUT_PROCEDURE, 'fas fa-angle-right', 'btn-success btn-lg btn-block') ?></div>
  </div>

  <?php
  $parameters = ['style' => 'progress-bar progress-bar-striped progress-bar-animated bg-info', 'markers' => ['position' => 1, 'min' => 0, 'max' => 100, 'now' => 33]];
  echo $hooks->cat('progressBar', $parameters);
?>

</form>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
