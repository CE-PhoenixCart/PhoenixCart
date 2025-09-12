(() => {
  // Get the current <script> element loading this code
  const currentScript = document.currentScript || (() => {
    const scripts = document.getElementsByTagName('script');
    return scripts[scripts.length - 1];
  })();

  // Derive base URL by stripping 'pulse.js' from script src
  const baseUrl = currentScript.src.replace(/pulse\.js$/, '');

  // Endpoint to POST collected events
  const endpoint = baseUrl + 'collect.php';

  // Create a queue for events
  window.pulse = window.pulse || [];

  // Function to send event data to server
  const sendEvent = (eventData) => {
    fetch(endpoint, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(eventData),
      credentials: 'same-origin', // send cookies if needed
    }).catch(() => {
      // Optionally handle errors here or retry later
      console.warn('Pulse event failed to send', eventData);
    });
  };

  // Process existing queued events (if any)
  window.pulse.forEach(sendEvent);

  // Override push to send new events immediately
  window.pulse.push = function(eventData) {
    sendEvent(eventData);
  };
})();
