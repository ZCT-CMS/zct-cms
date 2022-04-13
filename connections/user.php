<?php

class User{
  public function fetch_all(){
    global $pdo;

    $query = $pdo->prepare("SELECT * FROM Users");
    $query->execute();

    return $query->fetchAll();    
  }

  public function fetch_permission($user_id){
    global $pdo;

    $query = $pdo->prepare("SELECT edit_users_list FROM Users WHERE user_id = ?");
    $query->bindValue(1, $user_id);
    $query->execute();

    return $query->fetch();
  }
}

?>