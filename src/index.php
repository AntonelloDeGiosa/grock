<?php
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])) {
    $message = $_POST['query'];


$array =[

];
$fp = fopen('cronologia.csv', 'a');
fputcsv($fp, ["user", $message], ',', '"');


fclose($fp);
$fp = fopen('cronologia.csv', 'r');
$data = fgetcsv($fp, 0, ",");
 while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {

    $associativo = [
        "role" => $data[0],
        "content" =>$data[1] 
    ];

    $array[
        
    ]=$associativo;



 }
fclose($fp);



$apiKey = $_ENV['GROQ_API_KEY'] ? $_ENV['GROQ_API_KEY'] : getenv('GROQ_API_KEY');

$url = "https://api.groq.com/openai/v1/chat/completions";

$data = [
    "model" => "llama-3.3-70b-versatile",
    "messages" => $array,
    "temperature" => 0

];

 echo "<pre>";
 print_r ($data);
 echo "</pre>";

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

}



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
        // var_dump($result);
        if (isset($result['choices'][0]['message']['content'])) {
            $fp = fopen('cronologia.csv', 'a');
            fputcsv($fp, ["assistant", $result['choices'][0]['message']['content']], ',', '"');


            fclose($fp);


            echo nl2br(htmlspecialchars($result['choices'][0]['message']['content']));
        } else {
            echo "Nessuna risposta ricevuta.";
        }
        ?>
    </p>
    
    
</body>
</html>