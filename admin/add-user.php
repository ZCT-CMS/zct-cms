<?php

session_start();
include_once('../connections/connection.php');
include_once('../connections/user.php');
$user = new User;
$users = $user->fetch_all();

if(isset($_SESSION['admin'])){
  if(isset($_POST['username'])){
    $username = $_POST['username'];
    if($_POST['password'] != ""){
      $password = md5($_POST['password'] . $_POST['username']);
    } else{
      $password = "";
    }
    if($_POST['edit'] == 'allow'){
      $edit = 0;
    } else{
      $edit = 1;
    }
    $flag = 0;
    foreach ($users as $user) {
      if ($user['user_name'] == $username) {
        $query = $pdo->prepare('UPDATE Users SET user_password=?, edit_users_list=? WHERE user_name=?');
        $query->bindValue(1, $password);
        $query->bindValue(2, $edit);
        $query->bindValue(3, $username);
        $query->execute();
        $flag = 1;
        break;
      }
    }
    if($flag === 0){
      $query = $pdo->prepare('INSERT INTO Users (user_name, user_password, edit_users_list) VALUES (?, ?, ?)');
      $query->bindValue(1, $username);
      $query->bindValue(2, $password);
      $query->bindValue(3, $edit);
      $query->execute();
    }
  }
  header('Location: edit-users.php');
} else{
  header('Location: index.php');
}

?>