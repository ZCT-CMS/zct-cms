<?php

try{
  $pdo = new PDO('mysql:host=localhost; dbname=zct', 'admin', 'password');
  // $pdo = new PDO('mysql:host=localhost; dbname=zct', 'admin', 'passwordzct');
  //port 3306
} catch(PDOException $e){
  exit('Database error' . $e);
}

?>