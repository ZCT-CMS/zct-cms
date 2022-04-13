<?php
session_start();
include_once('../connections/connection.php');
include_once('../connections/user.php');
$user = new User;

if(isset($_SESSION['admin'])){
  if(isset($_GET['id'])){
    $id = $_GET['id'];
    $permission = $user->fetch_permission($id);
    if($permission[0] == 0){
      $permission = 1;
    } else{
      $permission = 0;
    }
    $query = $pdo->prepare('UPDATE Users SET edit_users_list=? WHERE user_id=?');
    $query->bindValue(1, $permission);
    $query->bindValue(2, $id);
    $query->execute();
  }
  header('Location: edit-users.php');
}
?>