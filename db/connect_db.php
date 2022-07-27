<?php

try{
    $pdo = new PDO('mysql:host=localhost;dbname=u271679860_bhshop','u271679860_bhshop','k55#0264075770K');
    //echo 'Connection Successfull';
}catch(PDOException $error){
    echo $error->getmessage();
}


?>