<?php
$botToken = "7678622082:AAF6l8QQ7-iEqq33OwCx52AB2YyxXQtmSjc";
$apiUrl = "https://api.telegram.org/bot$botToken/";

$weatherApiToken = "570d6bcba80a484fabbf080f31f3f185";
$cbuCurrencyApiUrl = "https://cbu.uz/uz/arkhiv-kursov-valyut/json/";

$update = json_decode(file_get_contents("php://input"), true);

if (isset($update['message'])) {
    $chatId = $update['message']['chat']['id'];
    $messageText = strtolower(trim($update['message']['text']));

    if ($messageText == "/start") {
        $welcomeMessage = "Assalomu alaykum! 😊\nMen sizga ob-havo va valyuta kurslarini aniqlashda yordam beraman.\n\n⛅ *Ob-havo uchun:* shahar nomini kiriting.\n💵 *Valyuta kurslari uchun:* miqdor va valyuta kodini kiriting (masalan, '100 USD').\n\nMisollar:\n- *Toshkent* (ob-havo uchun)\n- *100 USD* (UZS dan boshqa valyutalarni kiriting)";
        sendMessage($chatId, $welcomeMessage, true);
    } elseif (preg_match("/^([0-9]+)\s*([a-z]{3})$/i", $messageText, $matches)) {
        $amount = $matches[1];
        $currencyCode = strtoupper($matches[2]);
        $response = calculateCurrency($amount, $currencyCode);
        sendMessage($chatId, $response);
    } elseif ($messageText == "valyuta kurslari") {
        $response = getCurrencyRates();
        sendMessage($chatId, $response);
    } else {
        $city = htmlspecialchars($messageText);
        $weatherUrl = "https://api.openweathermap.org/data/2.5/weather?q=$city&appid=$weatherApiToken&units=metric";

        $weatherData = json_decode(file_get_contents($weatherUrl), true);

        if ($weatherData && isset($weatherData['main'])) {
            $temp = $weatherData['main']['temp'];
            $description = ucfirst($weatherData['weather'][0]['description']);
            $humidity = $weatherData['main']['humidity'];
            $windSpeed = $weatherData['wind']['speed'];

            $response = "🌆 Shahar: $city\n";
            $response .= "🌡 Harorat: $temp°C\n";
            $response .= "🌤 Ob-havo: $description\n";
            $response .= "💧 Namlik: $humidity%\n";
            $response .= "🌬 Shamol tezligi: $windSpeed m/s\n";
            sendMessage($chatId, $response);
        } else {
            sendMessage($chatId, "❌ Ob-havo ma'lumotlari topilmadi. Iltimos, boshqa shahar nomini kiriting.");
        }
    }
}

function getCurrencyRates() {
    global $cbuCurrencyApiUrl;
    $currencyData = file_get_contents($cbuCurrencyApiUrl);
    $currency = json_decode($currencyData, true);

    if (!$currency) {
        return "❌ Valyuta kurslarini olishda xato yuz berdi.";
    }

    $message = "Bugungi valyuta kurslari:\n";
    foreach ($currency as $rate) {
        if ($rate['Ccy'] === 'USD' || $rate['Ccy'] === 'EUR') {
            $message .= "1 " . $rate['Ccy'] . " = " . $rate['Rate'] . " UZS\n";
        }
    }
    return $message;
}

function calculateCurrency($amount, $currencyCode) {
    global $cbuCurrencyApiUrl;
    $currencyData = file_get_contents($cbuCurrencyApiUrl);
    $currency = json_decode($currencyData, true);

    if (!$currency) {
        return "❌ Valyuta kurslarini olishda xato yuz berdi.";
    }

    foreach ($currency as $rate) {
        if ($rate['Ccy'] === $currencyCode) {
            $convertedAmount = $amount * $rate['Rate'];
            return "$amount $currencyCode = " . number_format($convertedAmount, 2) . " UZS";
        }
    }
    return "❌ $currencyCode uchun valyuta kursi topilmadi.";
}

function sendMessage($chatId, $message, $isMarkdown = false) {
    global $apiUrl;
    $data = [
        'chat_id' => $chatId,
        'text' => $message
    ];
    if ($isMarkdown) {
        $data['parse_mode'] = 'Markdown';
    }
    file_get_contents($apiUrl . "sendMessage?" . http_build_query($data));
}
?>
