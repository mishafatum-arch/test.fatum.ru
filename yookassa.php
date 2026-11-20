<!-- ===== Форма оплаты ЮKassa (стилизованная под сайт) ===== -->
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
      <input name="cps_email"    class="ym-input fatum-input" placeholder="Email" type="text" value="">
      <input name="cps_phone"    class="ym-input fatum-input" placeholder="Телефон" type="text" value="">
      <input name="custName"     class="ym-input fatum-input" placeholder="ФИО" type="text" value="">
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