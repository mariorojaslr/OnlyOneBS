<?php
$token = "8275647710:AAEo2GysmcMvPOmh9zDioOeh3mde6FrcJvE";
$chatId = "488424438";
$message = "Test de conexión OnlyOneBS";

$url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chatId&text=" . urlencode($message);

echo "Probando conexión con Telegram...\n";
$response = @file_get_contents($url);

if ($response === false) {
    $error = error_get_last();
    echo "ERROR CRÍTICO: No se pudo contactar con la API de Telegram. Detalles: " . $error['message'] . "\n";
} else {
    echo "RESPUESTA DE TELEGRAM: " . $response . "\n";
}
