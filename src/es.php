<?php
$array =[

];
$fp = fopen('cronologia.csv', 'r');
 while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {

    $associativo = [
        "role" => $data[0],
        "content" =>$data[1] 
    ];

    $array[
        
    ]=$associativo;



 }
 echo "<pre>";
 print_r ($array);
 echo "</pre>";
 fclose($fp);
?>
