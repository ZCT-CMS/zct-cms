<?php

class Article {
  public function fetch_all(){
    global $pdo;

    $query = $pdo->prepare("SELECT * FROM Articles");
    $query->execute();

    return $query->fetchAll();
  }

  public function fetch_data($article_id){
    global $pdo;

    $query = $pdo->prepare("SELECT * FROM Articles WHERE article_id = ?");
    $query->bindValue(1, $article_id);
    $query->execute();

    return $query->fetch();
  } 
  public function fetch_sorted($atributes){
    global $pdo;

    $atributes_query = implode(',', array_fill(0, count($atributes), '?'));
    $query = $pdo->prepare("SELECT * FROM Articles
                            INNER JOIN Articles_Tags ON Articles.article_id =       Articles_Tags.article
                            INNER JOIN Tags ON Articles_Tags.tag = Tags.tag_id
                            WHERE Tags.tag_name IN ($atributes_query)");
    for ($i = 0; $i < count($atributes); $i++) {
      $query->bindValue(($i + 1), $atributes[$i]);
    }
    $query->execute();

    return $query->fetchAll();
  }

  public function fetch_image($article_id){
    global $pdo;
    $query = $pdo->prepare("SELECT filename FROM Images
                            WHERE Images.image_id = ?");
    $query->bindValue(1, $article_id);
    $query->execute();

    return $query->fetch();
  }

}

?>