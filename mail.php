<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['name']) && !empty($_POST['phone'])) {

    // --- ПРОВЕРКА СОГЛАСИЯ НА ОБРАБОТКУ ПД ---
    if (empty($_POST['consent'])) {
        echo '<script type="text/javascript">
            alert("Необходимо согласие на обработку персональных данных.");
            window.history.back();
        </script>';
        exit;
    }

    // --- ЗАЩИТА ОТ БОТОВ (ANTISPAM) ---
    if (!empty($_POST['email_homepage'])) {
        echo '<script type="text/javascript">window.location.href = "http://fatum.ru";</script>';
        exit;
    }

    // Очищаем данные
    $name  = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars(trim($_POST['phone']), ENT_QUOTES, 'UTF-8');

    $type  = isset($_POST['type'])  ? htmlspecialchars(trim($_POST['type']),  ENT_QUOTES, 'UTF-8') : 'не указан';
    $price = isset($_POST['price']) ? htmlspecialchars(trim($_POST['price']), ENT_QUOTES, 'UTF-8') : 'договорная';
    $speed = isset($_POST['speed']) ? htmlspecialchars(trim($_POST['speed']), ENT_QUOTES, 'UTF-8') : '';
    $count = isset($_POST['count']) ? htmlspecialchars(trim($_POST['count']), ENT_QUOTES, 'UTF-8') : '';

    $to      = 'misha@fatum.ru, oleg@fatum.ru';
    $subject = 'Заявка с сайта Fatum';

    $txt  = "Добрый день!\n\n";
    $txt .= "Поступила новая заявка на подключение: test.fatum.ru\n";
    $txt .= "Имя клиента: " . $name . "\n";
    $txt .= "Телефон: " . $phone . "\n";
    $txt .= "Согласие на обработку ПД: получено\n";
    $txt .= "Тариф: \"" . $type . "\"\n";

    if ($type == "безлимитный интернет" && !empty($speed)) {
        $txt .= "Скорость: " . $speed . "\n";
    } elseif (!empty($count)) {
        $txt .= "Трафик/Количество: " . $count . "\n";
    }

    $txt .= "Цена: " . $price . "\n\n";
    $txt .= "Не забудьте позвонить клиенту!";

    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/plain; charset=utf-8\r\n";
    $headers .= "From: Fatum Site <site@fatum.ru>\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    if (mail($to, $subject, $txt, $headers)) {
        echo '<script type="text/javascript">
            alert("Ваше сообщение успешно отправлено, ждите звонка!");
            window.location.href = "http://fatum.ru";
        </script>';
        exit;
    } else {
        echo '<script type="text/javascript">
            alert("Ошибка при отправке почты на сервере. Пожалуйста, свяжитесь с нами по телефону.");
            window.location.href = "http://fatum.ru";
        </script>';
        exit;
    }
}

header("Location: http://fatum.ru");
exit;
?>