<?php
// TELEGRAM
// Only process POST requests.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the form fields and remove whitespace.
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

//    // Check that data was sent to the bot.
//    if (empty($email)  || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
//        // Set a 400 (bad request) response code and exit.
//        http_response_code(400);
//        echo "Please complete the form and try again.";
//        exit;
//    }

//    $recaptchaSecretKey = '6Lf8EVsoAAAAAEaieB9J0bGvDiEVAXPw0Wlv37m0';
//    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
//
//    if (empty($recaptchaResponse)) {
//        http_response_code(400);
//        echo "Please complete the reCAPTCHA and try again.";
//        exit;
//    }
//
//    $recaptchaVerifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
//    $recaptchaData = [
//        'secret' => $recaptchaSecretKey,
//        'response' => $recaptchaResponse,
//        'remoteip' => $_SERVER['REMOTE_ADDR'],
//    ];
//
//    $ch = curl_init($recaptchaVerifyUrl);
//    curl_setopt($ch, CURLOPT_POST, 1);
//    curl_setopt($ch, CURLOPT_POSTFIELDS, $recaptchaData);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    $recaptchaResult = curl_exec($ch);
//    curl_close($ch);
//
//    if (!$recaptchaResult) {
//        http_response_code(500);
//        echo "Oops! Something went wrong while verifying reCAPTCHA.";
//        exit;
//    }
//
//    $recaptchaResult = json_decode($recaptchaResult);
//
//    if (!$recaptchaResult->success) {
//        http_response_code(400);
//        echo "reCAPTCHA verification failed. Please try again.";
//        exit;
//    }

    // Replace with your Telegram Bot API token and chat ID.
    $botToken = '6377242619:AAHXehb42p2beh9_Wke2j8PvJYCF1iKy0qI';
    $chatId = '-1001900073508';

    // Compose the message for the Telegram bot.
    $telegramMessage = "Name: $name\n";
    $telegramMessage .= "Email: $email\n";
    $telegramMessage .= "Message: $message";

    // Create the URL for sending the message to the Telegram bot.
    $telegramApiUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";
    $telegramParameters = [
        'chat_id' => $chatId,
        'text' => $telegramMessage,
    ];

    // Send the message to the Telegram bot using cURL.
    $ch = curl_init($telegramApiUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $telegramParameters);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $telegramResponse = curl_exec($ch);
    curl_close($ch);

    // Check if the message was sent successfully to the Telegram bot.
    if ($telegramResponse && json_decode($telegramResponse)->ok) {
        // Set a 200 (okay) response code.
        http_response_code(200);
        echo "Thank you! Your message has been sent successfully. Our managers will contact you.";
    } else {
        // Set a 500 (internal server error) response code.
        http_response_code(500);
        echo "Oops! Something went wrong, and we couldn't send your message to the Telegram bot.";
    }
} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}
?>