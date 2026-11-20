<?php
declare(strict_types=1);
mb_internal_encoding('UTF-8');

/* =========================================================
   КОНФИГУРАЦИЯ
   ========================================================= */
const SHOP_ID     = '404367';                                   // shopId из личного кабинета ЮKassa
const SECRET_KEY  = 'ВСТАВЬТЕ_СЕКРЕТНЫЙ_КЛЮЧ';                  // секретный ключ (НЕ хранить в HTML!)
const RETURN_URL  = 'https://ВАШ-ДОМЕН.ru/payment.php?action=success'; // абсолютный HTTPS-адрес
const FORM_PAGE   = 'payment.html';
const CURRENCY    = 'RUB';
const MIN_SUM     = 1.00;
const MAX_SUM     = 150000.00;
const VAT_CODE    = 1;                                          // 1 — без НДС (см. 54-ФЗ)
const API_URL     = 'https://api.yookassa.ru/v3/payments';

/* =========================================================
   УТИЛИТЫ
   ========================================================= */
function e(?string $s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function uuidv4(): string {
    $d = random_bytes(16);
    $d[6] = chr((ord($d[6]) & 0x0f) | 0x40);
    $d[8] = chr((ord($d[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($d), 4));
}

function normalizePhone(string $raw): string {
    $digits = preg_replace('/\D+/', '', $raw);
    if ($digits === '') return '';
    if (strlen($digits) === 11 && $digits[0] === '8') $digits = '7' . substr($digits, 1);
    if (strlen($digits) === 10)                        $digits = '7' . $digits;
    return '+' . $digits;
}

function layout(string $title, string $content): string {
    $t = e($title);
    return <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$t} — Фатум</title>
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" href="styls.css?v=9">
<link rel="stylesheet" href="payment.css?v=1">
</head>
<body class="page-payment">
<header class="header">
  <a href="index.html" class="logo"><span class="logo-text">Фатум</span></a>
  <nav class="nav">
    <a href="index.html#services">Услуги</a>
    <a href="index.html#story">О нас</a>
    <a href="index.html#contacts">Контакты</a>
  </nav>
  <div class="header-buttons"><a href="index.html" class="btn-pay">← На главную</a></div>
</header>
<main class="payment-page">
{$content}
</main>
<footer class="footer">
  <div class="footer-inner">
    <div class="footer-left">© 1998 – 2026, «Фатум» | <a href="index.html" style="color:#fff; text-decoration:underline;">Главная</a></div>
    <div class="footer-right">420141, Казань, ул. Кул Гали, 24</div>
  </div>
</footer>
</body>
</html>
HTML;
}

function fail(string $message, int $code = 400): void {
    http_response_code($code);
    header('Content-Type: text/html; charset=UTF-8');
    echo layout('Ошибка оплаты',
        '<h1 class="payment-page__title">Не удалось создать платёж</h1>' .
        '<p class="payment-page__lead">' . e($message) . '</p>' .
        '<p class="payment-page__back"><a href="' . e(FORM_PAGE) . '">← Попробовать снова</a></p>'
    );
    exit;
}

/* =========================================================
   МАРШРУТИЗАЦИЯ
   ========================================================= */
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'GET') {
    if (($_GET['action'] ?? '') === 'success') {
        echo layout('Оплата принята',
            '<h1 class="payment-page__title">Спасибо!</h1>' .
            '<p class="payment-page__lead">Платёж принят. Статус операции подтверждается банком — обычно это занимает несколько секунд.</p>' .
            '<p class="payment-page__secure">Чек придёт на указанный email после успешной оплаты.</p>' .
            '<p class="payment-page__back"><a href="index.html">← Вернуться на главную</a></p>'
        );
        exit;
    }
    header('Location: ' . FORM_PAGE, true, 302);
    exit;
}

if ($method !== 'POST') {
    http_response_code(405);
    exit;
}

if (!function_exists('curl_init')) {
    fail('На сервере не установлено расширение cURL (php-curl).', 500);
}

/* =========================================================
   ВАЛИДАЦИЯ
   ========================================================= */
$email    = trim((string)($_POST['email']    ?? ''));
$phoneRaw = trim((string)($_POST['phone']    ?? ''));
$name     = trim((string)($_POST['name']     ?? ''));
$contract = trim((string)($_POST['contract'] ?? ''));
$sumRaw   = str_replace([' ', ','], ['', '.'], (string)($_POST['sum'] ?? ''));

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    fail('Укажите корректный email.');
}
$phone = normalizePhone($phoneRaw);

if ($email === '' && $phone === '') {
    fail('Укажите email или телефон для чека.');
}

if (!is_numeric($sumRaw)) {
    fail('Укажите сумму платежа.');
}
$sum = round((float)$sumRaw, 2);

if ($sum < MIN_SUM || $sum > MAX_SUM) {
    fail('Сумма должна быть от ' . MIN_SUM . ' до ' . MAX_SUM . ' ₽.');
}

$amountValue = number_format($sum, 2, '.', '');
$customer    = $email !== '' ? ['email' => $email] : ['phone' => $phone];

$description = 'Оплата услуг связи' . ($contract !== '' ? ', договор ' . $contract : '');
$description = mb_substr($description, 0, 128);

/* =========================================================
   ЗАПРОС В API ЮKASSA
   ========================================================= */
$payload = [
    'amount'       => ['value' => $amountValue, 'currency' => CURRENCY],
    'capture'      => true,
    'confirmation' => [
        'type'       => 'redirect',
        'return_url' => RETURN_URL,
    ],
    'description'  => $description,
    'receipt'      => [
        'customer' => $customer,
        'items'    => [[
            'description'    => 'Услуги связи',
            'quantity'       => '1.00',
            'amount'         => ['value' => $amountValue, 'currency' => CURRENCY],
            'vat_code'       => VAT_CODE,
            'payment_subject'=> 'service',
            'payment_mode'   => 'full_payment',
        ]],
    ],
    'metadata' => array_filter([
        'contract' => mb_substr($contract, 0, 100),
        'name'     => mb_substr($name, 0, 100),
        'phone'    => $phone,
    ], static fn($v) => $v !== ''),
];

$ch = curl_init(API_URL);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Accept: application/json',
        'Idempotence-Key: ' . uuidv4(),
        'Authorization: Basic ' . base64_encode(SHOP_ID . ':' . SECRET_KEY),
    ],
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT        => 30,
]);

$response = curl_exec($ch);
$httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr  = curl_error($ch);
curl_close($ch);

if ($response === false) {
    error_log('[payment] cURL error: ' . $curlErr);
    fail('Платёжный сервис временно недоступен. Попробуйте позже.', 502);
}

$data = json_decode($response, true);

if ($httpCode < 200 || $httpCode >= 300 || !is_array($data)) {
    error_log('[payment] API error HTTP ' . $httpCode . ': ' . $response);
    $msg = $data['description'] ?? 'Платёжный сервис вернул ошибку. Попробуйте позже.';
    fail((string)$msg, 502);
}

$confirmationUrl = $data['confirmation']['confirmation_url'] ?? '';
if ($confirmationUrl === '') {
    error_log('[payment] No confirmation_url in response: ' . $response);
    fail('Не удалось получить ссылку на оплату. Попробуйте позже.', 502);
}

header('Location: ' . $confirmationUrl, true, 303);
exit;