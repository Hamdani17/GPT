<?php

// Set up global variables
$botToken = getenv('BOT_TOKEN');
$apiBaseUrl = 'https://api.telegram.org/bot' . $botToken . '/';
$types = [
    'خماسي' => 'aa1a1',
    'سداسي' => 'a11aa1',
    'عشوائي' => 'aa1a111a1a'
];
$currentCheckingType = null;

// Helper function to generate a random username
function generateUsername($pattern) {
    $username = '';
    for ($i = 0; $i < strlen($pattern); $i++) {
        $char = $pattern[$i];
        if ($char === 'a') {
            $username .= chr(rand(97, 122)); // random lowercase letter
        } elseif ($char === '1') {
            $username .= rand(0, 9); // random digit
        }
    }
    return $username;
}

// Function to send a Telegram API request
function sendTelegramRequest($method, $params = []) {
    global $apiBaseUrl;
    $url = $apiBaseUrl . $method;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($params));
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Function to send a Telegram message
function sendTelegramMessage($chatId, $text) {
    $params = [
        'chat_id' => $chatId,
        'text' => $text
    ];
    sendTelegramRequest('sendMessage', $params);
}

// Function to start checking usernames
function startChecking($chatId, $type) {
    global $currentCheckingType;
    $currentCheckingType = $type;

    sendTelegramMessage($chatId, 'START CHECKING...!');
    while ($currentCheckingType === $type) {
        $username = generateUsername($type);
        // Here, you can add the logic to check if the generated username is available on Telegram
        // If the username is available, send it using the sendTelegramMessage function
        // For example:
        // if (isUsernameAvailable($username)) {
        //     sendTelegramMessage($chatId, $username);
        // }
        // You can replace the isUsernameAvailable function with your own logic to check username availability

        // Wait for a while before checking the next username
        sleep(1);
    }
}

// Handle incoming updates
$update = json_decode(file_get_contents('php://input'), true);

if (isset($update['message'])) {
    $message = $update['message'];
    $chatId = $message['chat']['id'];
    $text = $message['text'];

    if ($text === '/start') {
        $typesText = "Types:\n";
        foreach ($types as $typeName => $typePattern) {
            $typesText .= $typeName . ': ' . $typePattern . "\n";
        }
        sendTelegramMessage($chatId, $typesText);
    } elseif (strpos($text, '/check') === 0) {
        $type = trim(substr($text, 7));
        if (array_key_exists($type, $types)) {
            startChecking($chatId, $type);
        } else {
            sendTelegramMessage($chatId, 'Please choose a valid type.');
        }
    } elseif ($text === '/stop') {
        $currentCheckingType = null;
        sendTelegramMessage($chatId, 'Username checking stopped.');
    }
}

?>