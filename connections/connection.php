<?php
try{
  // $pdo = new PDO('mysql:host=localhost; dbname=zct', 'admin', 'password');
  $pdo = new PDO('mysql:host=cms-zct.c0pa526mfcj4.eu-central-1.rds.amazonaws.com; port=3306; dbname=zct', 'admin', 'password');
} catch(PDOException $e){
  exit('Database error' . $e);
}

?>