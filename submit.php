<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = htmlspecialchars(trim($_POST["name"] ?? ''));
    $email   = htmlspecialchars(trim($_POST["email"] ?? ''));
    $phone   = htmlspecialchars(trim($_POST["phone"] ?? '—'));
    $date    = htmlspecialchars(trim($_POST["date"] ?? '—'));
    $message = htmlspecialchars(trim($_POST["message"] ?? '—'));

    if (empty($name) || empty($email) || empty($date)) {
        echo "<h2 style='color:red;'>Ошибка: Пожалуйста, заполните все обязательные поля.</h2>";
        exit;
    }

    $timestamp = date("Y-m-d H:i:s");
    $data = "[$timestamp] Имя: $name | Email: $email | Телефон: $phone | Дата церемонии: $date | Сообщение: $message\n";

    $result = @file_put_contents("submissions.txt", $data, FILE_APPEND | LOCK_EX);
    if ($result === false) {
        echo "<h2 style='color:red;'>❌ Ошибка: Не удалось записать в submissions.txt</h2>";

        if (!file_exists("submissions.txt")) {
            echo "<p>📁 Файл вообще не найден!</p>";
        } elseif (!is_writable("submissions.txt")) {
            echo "<p>🔒 Файл существует, но нет прав на запись. Дай chmod 666 или 644.</p>";
        } else {
            echo "<p>⚠️ Неизвестная ошибка при записи в файл.</p>";
        }

        exit;
    }

    echo "<h1 style='color:green;'>Спасибо за запись на чайную церемонию, $name!</h1>";
    echo "<p>Мы свяжемся с вами по адресу <strong>$email</strong> или телефону <strong>$phone</strong>.</p>";
} else {
    echo "<h2 style='color:red;'>Ошибка: форма не отправлена корректно.</h2>";
}
?>
