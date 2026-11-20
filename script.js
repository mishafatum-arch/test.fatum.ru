(function() {
  function bind(openId, modalId, closeId) {
    var modal    = document.getElementById(modalId);
    var openBtn  = document.getElementById(openId);
    var closeBtn = document.getElementById(closeId);
    if (!modal || !openBtn) return;

    function openModal() {
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
    function closeModal() {
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }

    openBtn.addEventListener('click', openModal);
    closeBtn.addEventListener('click', closeModal);

    modal.addEventListener('click', function(e) {
      if (e.target === modal) closeModal();
    });
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') closeModal();
    });
  }

  bind('openCallback',    'callbackModal', 'closeCallback');
  bind('openCallbackTop', 'callbackModal', 'closeCallback');
  bind('openDetails',     'detailsModal',  'closeDetails');
})();

/* ===== COOKIE-БАННЕР ===== */
(function() {
  var banner = document.getElementById('cookieBanner');
  var accept = document.getElementById('cookieAccept');

  if (!banner || !accept) {
    console.warn('Cookie-баннер не найден в HTML. Проверьте id="cookieBanner" и id="cookieAccept".');
    return;
  }

  var COOKIE_NAME = 'fatum_cookie_ok';
  var DAYS = 365;

  function getCookie(name) {
    var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? match[2] : null;
  }
  function setCookie(name, value, days) {
    var d = new Date();
    d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = name + '=' + value + ';expires=' + d.toUTCString() + ';path=/;SameSite=Lax';
  }

  if (!getCookie(COOKIE_NAME)) {
    setTimeout(function() { banner.classList.add('show'); }, 600);
  }

  accept.addEventListener('click', function() {
    setCookie(COOKIE_NAME, '1', DAYS);
    banner.classList.remove('show');
  });
})();