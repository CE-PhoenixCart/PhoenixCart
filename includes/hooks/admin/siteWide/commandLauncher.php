<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2026 Phoenix Cart

  Released under the GNU General Public License
*/


class hook_admin_siteWide_commandLauncher {

  public function listen_injectSiteEnd() {

    $url = json_encode($GLOBALS['Admin']->link('command_runner.php'));

    return <<<HTML

<script>
document.addEventListener('DOMContentLoaded', function () {

  const input = document.querySelector('[data-command-launcher]');
  if (!input) return;

  const baseUrl = $url;

  const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

  let recognition = null;

  if (SpeechRecognition) {
    recognition = new SpeechRecognition();

    recognition.lang = 'en-GB';
    recognition.interimResults = true;
    recognition.maxAlternatives = 1;

    recognition.onresult = function (event) {

      let transcript = '';

      for (let i = event.resultIndex; i < event.results.length; i++) {
        transcript += event.results[i][0].transcript;
      }

      input.value = transcript;
      input.dispatchEvent(new Event('input'));

    };

    recognition.onstart = function () {
      input.classList.add('border-danger');
      input.placeholder = 'Listening...';
    };

    recognition.onend = function () {
      input.classList.remove('border-danger');
      input.placeholder = 'Command...';
    };
  }

  document.addEventListener('keydown', function (e) {
    if (!(e.ctrlKey || e.metaKey) || e.key.toLowerCase() !== 'k') return;

    e.preventDefault();
    input.focus();
    input.select();
  });

  document.addEventListener('keydown', function (e) {
    if (!(e.ctrlKey || e.metaKey) || !e.shiftKey || e.key.toLowerCase() !== 'k') return;

    if (!recognition) return;

    e.preventDefault();
    input.focus();
    recognition.start();
  });

  input.addEventListener('dblclick', function () {
    if (recognition) recognition.start();
  });

  input.addEventListener('keydown', function (e) {
    if (e.key !== 'Enter') return;

    const cmd = input.value.trim();
    if (!cmd) return;

    const sep = baseUrl.includes('?') ? '&' : '?';
    const url = baseUrl + sep + 'cmd=' + encodeURIComponent(cmd);

    // =========================
    // HELP HANDLING (NO REDIRECT)
    // =========================
    if (cmd.startsWith('help')) {
      fetch(url)
        .then(r => r.text())
        .then(html => {

          let modal = document.getElementById('helpModal');

          if (!modal) {

            modal = document.createElement('div');
            modal.id = 'helpModal';
            modal.className = 'modal fade';

            modal.innerHTML = `
<div class="modal-dialog modal-dialog-scrollable">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title"><i class="fa-solid fa-circle-info"></i></h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body"></div>
  </div>
</div>`;

            document.body.appendChild(modal);
          }

          modal.querySelector('.modal-body').innerHTML = html;

          new bootstrap.Modal(modal).show();
        });

      return;
    }

    // =========================
    // NORMAL COMMAND FLOW
    // =========================
    window.location.href = url;
  });
});
</script>

HTML;

  }

}
