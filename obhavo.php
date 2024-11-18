<?php
session_start();

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang = $_SESSION['lang'] ?? 'uz';

$texts = [
    'uz' => [
        'title' => "OB HAVO MA'LUMOTLARI",
        'location_label' => "Shahar",
        'placeholder' => "SHAXARNI TANLANG",
        'button' => "KO'RSATISH",
        'location' => "Joylashuv",
        'temperature' => "Harorat",
        'weather' => "Ob-havo",
        'humidity' => "Namlik",
        'wind_speed' => "Shamol tezligi",
        'pressure' => "Bosim",
        'history' => "Qidiruv tarixi",
        'clear_history' => "Qidiruvni tozalash",
        'error' => "Ob-havo ma’lumotlari mavjud emas. Iltimos, boshqa shahar kiriting."
    ],
    'ru' => [
        'title' => "ПОГОДА",
        'location_label' => "Город",
        'placeholder' => "ВВЕДИТЕ ГОРОД",
        'button' => "ПОКАЗАТЬ",
        'location' => "Местоположение",
        'temperature' => "Температура",
        'weather' => "Погода",
        'humidity' => "Влажность",
        'wind_speed' => "Скорость ветра",
        'pressure' => "Давление",
        'history' => "История поиска",
        'clear_history' => "Очистить историю",
        'error' => "Погода недоступна. Пожалуйста, введите другой город."
    ],
    'en' => [
        'title' => "WEATHER INFORMATION",
        'location_label' => "City",
        'placeholder' => "ENTER CITY",
        'button' => "SHOW",
        'location' => "Location",
        'temperature' => "Temperature",
        'weather' => "Weather",
        'humidity' => "Humidity",
        'wind_speed' => "Wind Speed",
        'pressure' => "Pressure",
        'history' => "Search History",
        'clear_history' => "Clear Search History",
        'error' => "Weather data unavailable. Please enter a different city."
    ]
];

if (isset($_POST['clear_history'])) {
    unset($_SESSION['history']);
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texts[$lang]['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .weather-details { font-size: 1.2em; }
        .weather-card {
            background: rgba(0, 0, 0, 0.7);
            color: #ffffff; 
            max-width: 500px;
            margin: 30px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        body {
            background-image: url(https://anhor.uz/wp-content/uploads/2024/06/d37bf17478c9f43fa4095ea3b344b734.jpeg); 
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed; 
            color: #ffffff; 
            transition: background 0.5s ease-in-out;
        }
        .language-buttons {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }
        .language-buttons a {
            margin-left: 10px;
            font-weight: bold;
            color: #000;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="language-buttons">
        <a href="?lang=uz" class="btn btn-outline-primary <?php echo $lang == 'uz' ? 'active' : ''; ?>">UZ</a>
        <a href="?lang=ru" class="btn btn-outline-primary <?php echo $lang == 'ru' ? 'active' : ''; ?>">RU</a>
        <a href="?lang=en" class="btn btn-outline-primary <?php echo $lang == 'en' ? 'active' : ''; ?>">ENG</a>
    </div>

    <h1 class="text-center mt-4" style="color: black;"><?php echo $texts[$lang]['title']; ?></h1>
    <div class="weather-card">
        <form method="POST">
            <div class="mb-3">
                <label for="location" class="form-label"><?php echo $texts[$lang]['location_label']; ?></label>
                <input type="text" id="location" name="location" class="form-control" placeholder="<?php echo $texts[$lang]['placeholder']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary w-100"><?php echo $texts[$lang]['button']; ?></button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['location'])) {
            $location = htmlspecialchars($_POST['location']);
            $api_url = "https://api.openweathermap.org/data/2.5/weather?q={$location}&appid=570d6bcba80a484fabbf080f31f3f185&units=metric";
            $weather_data = json_decode(file_get_contents($api_url), true);

            if ($weather_data && isset($weather_data['main'])) {
                $temp = $weather_data['main']['temp'];
                $description = ucfirst($weather_data['weather'][0]['description']);
                $humidity = $weather_data['main']['humidity'];
                $wind_speed = $weather_data['wind']['speed'];
                $pressure = $weather_data['main']['pressure'];
                $icon = $weather_data['weather'][0]['icon'];

                echo "<div class='weather-details mt-4'>";
                echo "<p><i class='fas fa-map-marker-alt'></i> <strong>{$texts[$lang]['location']}:</strong> $location</p>";
                echo "<p><img src='https://openweathermap.org/img/wn/{$icon}.png' alt='{$description}' /> <strong>{$texts[$lang]['weather']}:</strong> {$description}</p>";
                echo "<p><i class='fas fa-thermometer-half'></i> <strong>{$texts[$lang]['temperature']}:</strong> {$temp}°C</p>";
                echo "<p><i class='fas fa-tint'></i> <strong>{$texts[$lang]['humidity']}:</strong> {$humidity}%</p>";
                echo "<p><i class='fas fa-wind'></i> <strong>{$texts[$lang]['wind_speed']}:</strong> {$wind_speed} m/s</p>";
                echo "<p><i class='fas fa-tachometer-alt'></i> <strong>{$texts[$lang]['pressure']}:</strong> {$pressure} hPa</p>";
                echo "</div>";

                $_SESSION['history'][] = ['location' => $location, 'temp' => $temp, 'description' => $description];
            } else {
                echo "<p class='mt-4 text-danger'>{$texts[$lang]['error']}</p>";
            }
        }

        if (isset($_SESSION['history']) && count($_SESSION['history']) > 0) {
            echo "<div class='history mt-5'>";
            echo "<h5>{$texts[$lang]['history']}</h5><ul>";
            foreach (array_reverse($_SESSION['history']) as $item) {
                echo "<li>{$item['location']}: {$item['temp']}°C, {$item['description']}</li>";
            }
            echo "</ul>";
            echo "<form method='POST'>";
            echo "<button type='submit' name='clear_history' class='btn btn-secondary mt-2'>{$texts[$lang]['clear_history']}</button>";
            echo "</form></div>";
        }
        ?>
    </div>
</div>
</body>
</html>
