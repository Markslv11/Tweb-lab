<?php
header("Content-Type: text/html; charset=utf-8");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);

    if (!$data) {
        echo "<h2 style='color:red;'>Ошибка: Данные не получены или повреждены.</h2>";
        exit;
    }

    $name    = htmlspecialchars(trim($data["name"] ?? ''));
    $email   = htmlspecialchars(trim($data["email"] ?? ''));
    $phone   = htmlspecialchars(trim($data["phone"] ?? '—'));
    $date    = htmlspecialchars(trim($data["date"] ?? '—'));
    $message = htmlspecialchars(trim($data["message"] ?? '—'));

    if (empty($name) || empty($email) || empty($date)) {
        echo "<h2 style='color:red;'>Ошибка: Пожалуйста, заполните все обязательные поля.</h2>";
        exit;
    }

    $timestamp = date("Y-m-d H:i:s");
    $line = "[$timestamp] Имя: $name | Email: $email | Телефон: $phone | Дата церемонии: $date | Сообщение: $message\n";

    $result = @file_put_contents("submissions.txt", $line, FILE_APPEND | LOCK_EX);
    if ($result === false) {
        echo "<h2 style='color:red;'>❌ Ошибка: Не удалось записать в submissions.txt</h2>";

        if (!file_exists("submissions.txt")) {
            echo "<p>📁 Файл не найден!</p>";
        } elseif (!is_writable("submissions.txt")) {
            echo "<p>🔒 Нет прав на запись. Выстави chmod 666 или 644.</p>";
        } else {
            echo "<p>⚠️ Неизвестная ошибка при записи.</p>";
        }

        exit;
    }

    echo "<h1 style='color:green;'>Спасибо за запись на чайную церемонию, $name!</h1>";
    echo "<p style='color:#4a3f35;'>Мы свяжемся с вами по адресу <strong style='color:#4a3f35;'>$email</strong> или телефону <strong style='color:#4a3f35;'>$phone</strong>.</p>";
} else {
    echo "<h2 style='color:red;'>Ошибка: неправильный метод запроса.</h2>";
}
?>
