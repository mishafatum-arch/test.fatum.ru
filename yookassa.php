
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Оплата услуг — Фатум</title>
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" href="styls.css?v=9">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
<style>
/* ===== Стили страницы оплаты ===== */
.page-payment { background: #f4f8fb; min-height: 100vh; display: flex; flex-direction: column; }
.payment-page { flex: 1; width: 100%; max-width: 620px; margin: 0 auto; padding: 48px 20px 64px; }
.payment-page__title { font-size: 32px; color: #1a1a1a; margin-bottom: 8px; }
.payment-page__lead { font-size: 16px; color: #4a5560; margin-bottom: 6px; line-height: 1.5; }
.payment-page__secure { font-size: 14px; color: #087BAE; font-weight: 600; margin-bottom: 28px; }
.payment-page__back { margin-top: 28px; text-align: center; font-size: 14px; }
.payment-page__back a { color: #087BAE; text-decoration: none; font-weight: 600; }
.payment-page__back a:hover { text-decoration: underline; }

.page-payment .header { background: #fff; box-shadow: 0 1px 0 rgba(0,0,0,.06); }
.page-payment .fatum-payment { margin: 0; max-width: none; }

@media (max-width: 700px) {
  .payment-page { padding: 28px 16px 40px; }
  .payment-page__title { font-size: 24px; }
}
</style>
</head>
<body class="page-payment">

<header class="header">
  <a href="index.html" class="logo">
    <span class="logo-text">Фатум</span>
  </a>
  <nav class="nav">
    <a href="index.html#services">Услуги</a>
    <a href="index.html#story">О нас</a>
    <a href="index.html#contacts">Контакты</a>
  </nav>
  <div class="header-buttons">
    <a href="index.html" class="btn-pay">← На главную</a>
  </div>
</header>

<main class="payment-page">
  <h1 class="payment-page__title">Оплата услуг</h1>
  <p class="payment-page__lead">Оплатите интернет, резервный канал или другие услуги «Фатум» банковской картой онлайн.</p>
  <p class="payment-page__secure">Безопасная оплата через ЮKassa</p>

  <div class="fatum-payment">
    <link rel="stylesheet" href="https://yookassa.ru/integration/simplepay/css/yookassa_construct_form.css?v=1.15.0">

    <div class="fatum-payment__title">Оплата услуг связи</div>
    <div class="fatum-payment__subtitle">Заполните данные — и вы перейдёте на защищённую страницу оплаты.</div>

    <form class="yoomoney-payment-form" action="https://yookassa.ru/integration/simplepay/payment" method="post" accept-charset="utf-8">

      <div class="ym-products ym-display-none">
        <div class="ym-block-title ym-products-title">Товары</div>
        <div class="ym-product">
          <div class="ym-product-line">
            <span class="ym-product-description"><span class="ym-product-count">1×</span>Услуги связи</span>
            <span class="ym-product-price" data-price="0.0" data-id="954" data-count="1">0,00 ₽</span>
          </div>
          <input disabled="" type="hidden" name="text" value="Услуги связи">
          <input disabled="" type="hidden" name="price" value="0.0">
          <input disabled="" type="hidden" name="quantity" value="1">
          <input disabled="" type="hidden" name="paymentSubjectType" value="service">
          <input disabled="" type="hidden" name="paymentMethodType" value="full_payment">
          <input disabled="" type="hidden" name="tax" value="1">
        </div>
      </div>

      <input value="" type="hidden" name="ym_merchant_receipt">

      <div class="ym-customer-info">
        <div class="ym-block-title">О покупателе</div>
        <input name="cps_email" class="ym-input fatum-input" placeholder="Email" type="text" value="">
        <input name="cps_phone" class="ym-input fatum-input" placeholder="Телефон" type="text" value="">
        <input name="custName"  class="ym-input fatum-input" placeholder="ФИО" type="text" value="">
        <textarea class="ym-textarea fatum-textarea" name="orderDetails" placeholder="Номер договора"></textarea>
      </div>

      <div class="ym-hidden-inputs"></div>
      <input name="sumEqualPrice" type="hidden" value="true">

      <div class="ym-payment-btn-block fatum-payment__row">
        <div class="ym-input-icon-rub fatum-sum">
          <input name="sum" placeholder="Сумма, ₽" class="ym-input ym-sum-input ym-required-input fatum-input" type="number" step="any">
        </div>
        <button data-text="Заплатить" class="ym-btn-pay ym-result-price fatum-btn">
          <span class="ym-text-crop">Заплатить</span>
          <span class="ym-price-output"></span>
        </button>
        <img src="https://yookassa.ru/integration/simplepay/img/iokassa-gray.svg?v=1.15.0"
             class="ym-logo" width="114" height="27" alt="ЮKassa">
      </div>

      <input name="shopId" type="hidden" value="404367">
    </form>

    <script src="https://yookassa.ru/integration/simplepay/js/yookassa_construct_form.js?v=1.15.0"></script>
  </div>

  <p class="payment-page__back"><a href="index.html">← Вернуться на главную</a></p>
</main>

<footer class="footer">
  <div class="footer-inner">
    <div class="footer-left">
      © 1998 – 2026, «Фатум» |
      <a href="index.html" style="color:#fff; text-decoration:underline;">Главная</a>
    </div>
    <div class="footer-right">420141, Казань, ул. Кул Гали, 24</div>
  </div>
</footer>

<style>
/* ===== Финальные оверрайды поверх CSS ЮKassa ===== */
.page-payment .fatum-payment .ym-input,
.page-payment .fatum-payment .ym-textarea {
  display: block !important;
  width: 100% !important;
  box-sizing: border-box !important;
  padding: 12px 14px !important;
  margin-bottom: 12px !important;
  border: 1px solid #d7dee6 !important;
  border-radius: 8px !important;
  font-size: 15px !important;
  font-family: inherit !important;
  color: #222 !important;
  background: #fafbfc !important;
  box-shadow: none !important;
  outline: none !important;
}
.page-payment .fatum-payment .ym-input:focus,
.page-payment .fatum-payment .ym-textarea:focus {
  border-color: #087BAE !important;
  background: #fff !important;
  box-shadow: 0 0 0 3px rgba(8,123,174,.15) !important;
}
.page-payment .fatum-payment .ym-textarea { min-height: 84px; resize: vertical; }
.page-payment .fatum-payment .ym-btn-pay,
.page-payment .fatum-payment .fatum-btn {
  background: #087BAE !important;
  color: #fff !important;
  border: none !important;
  border-radius: 10px !important;
  padding: 12px 24px !important;
  font-size: 15px !important;
  font-weight: 600 !important;
  font-family: inherit !important;
  cursor: pointer !important;
  transition: transform .2s, background .2s !important;
  white-space: nowrap !important;
  box-shadow: none !important;
}
.page-payment .fatum-payment .ym-btn-pay:hover,
.page-payment .fatum-payment .fatum-btn:hover {
  background: #05628c !important;
  transform: scale(1.03) !important;
}
.page-payment .fatum-payment .ym-block-title {
  font-size: 15px;
  font-weight: 600;
  color: #087BAE;
  margin: 18px 0 10px;
}
.page-payment .fatum-payment .ym-logo { margin-left: auto; opacity: .7; }
</style>

</body>
</html>