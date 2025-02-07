<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $form_link = (clone $link)->set_parameter('action', 'insert');
  $form = new Form('newsletter', $form_link);

  $parameters = ['title' => '', 'content' => '', 'module' => ''];

  $nInfo = new objectInfo($parameters);

  if (isset($_GET['nID'])) {
    $form->hide('newsletter_id', (int)$newsletter_id);
    $form_link->set_parameter('action', 'update')->set_parameter('nID', (int)$newsletter_id);

    $newsletter = $db->query("SELECT title, content, module FROM newsletters WHERE newsletters_id = " . (int)$newsletter_id)->fetch_assoc();

    $nInfo->objectInfo($newsletter);
  } elseif ($_POST) {
    $nInfo->objectInfo($_POST);
  }

  $classes = [];
  if ($dir = dir('includes/modules/newsletters/')) {
    while ($file = $dir->read()) {
      if (!is_dir('includes/modules/newsletters/' . $file)) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
          $classes[] = pathinfo($file, PATHINFO_FILENAME);
        }
      }
    }

    sort($classes);
    $dir->close();
  }

  $modules = [];
  foreach ($classes as $class) {
    $modules[] = ['id' => $class, 'text' => $class];
  }

  echo $form;
?>

    <div class="row mb-2" id="zModule">
      <label for="Module" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_NEWSLETTER_MODULE ?></label>
      <div class="col-sm-9">
        <?= (new Select('module', $modules, ['class' => 'form-select', 'id' => 'Module']))->require()->set_selection($nInfo->module) ?>
      </div>
    </div>

    <div class="row mb-2" id="zTitle">
      <label for="Title" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_NEWSLETTER_TITLE ?></label>
      <div class="col-sm-9">
        <?= (new Input('title', ['value' => $nInfo->title, 'id' => 'Title']))->require() ?>
      </div>
    </div>

    <div class="row mb-2" id="zContent">
      <label for="Content" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_NEWSLETTER_CONTENT ?></label>
      <div class="col-sm-9">
        <?= (new Textarea('content', ['cols' => '60', 'rows' => '15', 'id' => 'Content']))->require()->set_text($nInfo->content) ?>
      </div>
    </div>

    <?= $admin_hooks->cat('newForm') ?>
    
    <div class="d-grid mt-2">
      <?= new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success') ?>
    </div>
    
    <?= $Admin->button(IMAGE_CANCEL, 'fas fa-angle-left', 'btn-light mt-1', $link) ?>

  </form>
