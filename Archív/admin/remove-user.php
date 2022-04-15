<?php
  session_start();
  include_once('../connections/connection.php');
  
  if(isset($_SESSION['admin'])){
    if(isset($_GET['id'])){
      $id = $_GET['id'];
      $query = $pdo->prepare('DELETE FROM Users WHERE user_id = ?');
      $query->bindValue(1, $id);
      $query->execute();
      header('Location: edit-users.php');
    }
  }
?>