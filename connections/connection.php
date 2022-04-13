<?php

try{
  $pdo = new PDO('mysql:host=localhost; dbname=zct', 'admin', 'password');
} catch(PDOException $e){
  exit('Database error' . $e);
}

?>