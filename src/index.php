<?php
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])) {
    $message = $_POST['query'];
}

$apiKey = $_ENV['GROQ_API_KEY'] ? $_ENV['GROQ_API_KEY'] : getenv('GROQ_API_KEY');

$url = "https://api.groq.com/openai/v1/chat/completions";

$data = [
    "model" => "llama-3.3-70b-versatile",
    "messages" => [
        [
            "role" => "system",
            "content" => "Sei un assistente utile e conciso che rispone in italiano."
        ],
        [
            "role" => "user",   
            "content" => "$message"
        ],
    ],
    "temperature" => 0

];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . $apiKey
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "Errore cURL: " . curl_error($ch);
    exit;
}

curl_close($ch);

// Decodifica JSON
$result = json_decode($response, true);





?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Groq</title>
</head>
<body>
    <h1>Welcome to Groq!</h1>
    <p>Chiedi pure quello che vuoi!!</p>

    <form action="" method="post">
        <input type="text" name="query" placeholder="Inserisci la tua domanda...">
        <button type="submit">Invia</button>
    </form>

    <h2>Risposta:</h2>
    <p>
        <?php
        if(isset($_POST['query'])) {
            echo "Hai chiesto: " . htmlspecialchars($_POST['query']) . "<br><br>    ";
        }else {
            echo "Inserisci una domanda per ricevere una risposta.<br>";
        }   

        if (isset($result['choices'][0]['message']['content'])) {
            echo nl2br(htmlspecialchars($result['choices'][0]['message']['content']));
        } else {
            echo "Nessuna risposta ricevuta.";
        }
        ?>
    </p>
    
    
</body>
</html>