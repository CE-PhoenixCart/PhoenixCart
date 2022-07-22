<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  function phoenix_opendir($path) {
    $path = rtrim($path, '/\\') . '/';
    $handle = opendir($path);
    if (!$handle) {
      return;
    }

    while ($filename = readdir($handle)) {
      if (pathinfo($filename, PATHINFO_EXTENSION) === 'php') {
        $filepath = "$path$filename";

        yield [
          'name' => $filepath,
          'is_dir' => is_dir($filepath),
          'writable' => File::is_writable($filepath),
          'size' => filesize($filepath),
          'last_modified' => strftime(DATE_TIME_FORMAT, filemtime($filepath)),
        ];
      } elseif (('.' !== $filename[0]) && is_dir("$path$filename")) {
        yield from phoenix_opendir("$path$filename");
      }
    }

    closedir($handle);
  }

  if (!isset($_GET['lngdir'])) {
    $_GET['lngdir'] = $_SESSION['language'];
  }

  $languages = [];
  $language_exists = false;
  foreach (array_values(language::load_all()) as $l) {
    if ($l['directory'] === $_GET['lngdir']) {
      $language_exists = true;
    }

    $languages[] = ['id' => $l['directory'], 'text' => $l['name']];
  }

  if (!$language_exists) {
    $_GET['lngdir'] = $_SESSION['language'];
  }
  $link = $Admin->link()->retain_query_except(['action', 'filename']);

  const DIR_FS_CATALOG_LANGUAGES = DIR_FS_CATALOG . 'includes/languages/';

  if (isset($_GET['filename'])) {
    $file_edit = Path::normalize(DIR_FS_CATALOG_LANGUAGES . $_GET['filename']);

    if (!Text::is_prefixed_by($file_edit, DIR_FS_CATALOG_LANGUAGES)) {
      Href::redirect($link);
    }
  }

  require 'includes/segments/process_action.php';

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE; ?></h1>
    </div>
    <div class="col-sm-4 text-right align-self-center">
      <?=
        (new Form('lng', $Admin->link(), 'get'))->hide_session_id(),
        (new Select('lngdir', $languages, ['onchange' => 'this.form.submit();']))->set_selection($_GET['lngdir']),
        '</form>'
      ?>
    </div>
  </div>

<?php
  if (isset($_GET['lngdir'], $_GET['filename'])) {
    $file = DIR_FS_CATALOG_LANGUAGES . $_GET['filename'];

    if (file_exists($file)) {
      $textarea = new Textarea('file_contents', ['cols' => '80', 'rows' => '25', 'id' => 'dlFile']);
      $textarea->set_text(file_get_contents($file));

      $tickable = new Tickable('download', ['value' => '1'], 'checkbox');

      if (!File::is_writable($file)) {
        $tickable->set('readonly')->tick();

        $messageStack->reset();
        $messageStack->add(sprintf(ERROR_FILE_NOT_WRITEABLE, $file), 'warning');
        echo $messageStack->output();
      }

      echo new Form('language',
        (clone $link)->set_parameter('filename', Text::input($_GET['filename']))
                     ->set_parameter('action', 'save'));
?>

    <div class="alert alert-info mb-3">
      <p class="lead mb-0"><?= $_GET['filename']; ?></p>
    </div>

    <div class="form-group row" id="zFile">
      <div class="col">
        <?= $textarea ?>
      </div>
    </div>

    <?=
      $tickable, TEXT_INFO_DOWNLOAD_ONLY,
      new Button(IMAGE_SAVE, 'fas fa-pen-alt', 'btn-success btn-lg btn-block mr-2'),
      $Admin->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light mt-2', $link)
    ?>

  </form>

  <div class="alert alert-info mt-3">
    <?= TEXT_EDIT_NOTE; ?>
  </div>

<?php
    } else {
?>
  <div class="alert alert-warning text-center">
    <?= TEXT_FILE_DOES_NOT_EXIST; ?>
  </div>

<?php
      echo $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-warning btn-block btn-lg', $link);
    }
  } else {
    $filename = $_GET['lngdir'] . '.php';
    $link->set_parameter('filename', Text::input($filename));
?>

  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead class="thead-dark">
        <tr>
          <th><?= TABLE_HEADING_FILES ?></th>
          <th class="text-center"><?= TABLE_HEADING_WRITABLE ?></th>
          <th class="text-right"><?= TABLE_HEADING_LAST_MODIFIED ?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><a href="<?= $link ?>"><?= $filename ?></a></td>
          <td class="text-center"><?= File::is_writable(DIR_FS_CATALOG_LANGUAGES . $filename) ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>' ?></td>
          <td class="text-right"><?= strftime(DATE_TIME_FORMAT, filemtime(DIR_FS_CATALOG_LANGUAGES . $filename)) ?></td>
        </tr>
<?php
    foreach (phoenix_opendir(DIR_FS_CATALOG_LANGUAGES . $_GET['lngdir']) as $file) {
      if (pathinfo($file['name'], PATHINFO_EXTENSION) === 'php') {
        $filename = Text::ltrim_once($file['name'], DIR_FS_CATALOG_LANGUAGES);
        $link->set_parameter('filename', $filename);

        echo '<tr>';
          echo '<td><a href="' . $link . '">' . substr($filename, strlen($_GET['lngdir'] . '/')) . '</a></td>';
          echo '<td class="text-center">' . ($file['writable'] ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>') . '</td>';
          echo '<td class="text-right">' . $file['last_modified'] . '</td>';
        echo '</tr>';
      }
    }
?>
        </tr>
      </tbody>
    </table>
  </div>
<?php
  }

  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
