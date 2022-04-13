<?php

session_start();

include_once('../connections/connection.php');
include_once('../connections/article.php');
$article = new Article;
$image = new Article;

// Registration check
if (isset($_SESSION['logged_in']) || isset($_SESSION['admin'])) {
  $articles = $article->fetch_all();
  if (isset($_GET['articles'])) {
    $articles = $_GET['articles'];
    for ($i = 0; $i < count($articles); $i++) {
      $id = $articles[$i];

      $data = $article->fetch_data($id);
      $images = $image->fetch_image($id);

      $link = "../images/blog/" . $images['filename'];
      //Deleting an old thumbnail
      if (file_exists($link)) {
        unlink($link);
      }

      // Delete an article and all data associated with it from the database
      $query = $pdo->prepare('DELETE FROM Images WHERE image_id = ?');
      $query->bindValue(1, $id);
      $query->execute();

      $query = $pdo->prepare('DELETE FROM Articles_tags WHERE article = ?');
      $query->bindValue(1, $id);
      $query->execute();

      $query = $pdo->prepare('DELETE FROM Articles WHERE article_id = ?');
      $query->bindValue(1, $id);
      $query->execute();
    }

    header('Location: delete.php');
  } else if (isset($_GET['topic'])) {
    // Article filter
    if (isset($_GET['topic'])) {
      $topics = $_GET['topic'];
      $all = 0;
      foreach ($topics as $topic) {
        if ($topic == 'all') {
          $articles = $article->fetch_all();
          $topics = [];
          $all = 1;
        }
      }
      if (
        $all == 0
      ) {
        $articles = $article->fetch_sorted($topics);
      }
    }
  }

?>

  <html>

  <head>
    <title>CMS</title>
    <link rel="stylesheet" href="../assets/style.css" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
  </head>

  <body>
    <div class="container">
      <div class="cms_header">
        <a href="index.php" id="logo">Delete article</a>
        <br />
        <h4>Select an Article to Delete:</h4>
      </div>
      <!-- Article filter -->
      <form action="delete.php" method="get">
        <div id="list" class="dropdown-check-list" tabindex="100">
          <span class="anchor">Select Topics</span>
          <ul class="items">
            <li>
              <input type="checkbox" id="all" name="topic[]" value="all">
              <label for="all"> all</label>
            </li>
            <li>
              <input type="checkbox" id="kancelaria" name="topic[]" value="kancelaria">
              <label for="kancelaria"> Kancelaria</label>
            </li>
            <li>
              <input type="checkbox" id="novaciky" name="topic[]" value="novaciky">
              <label for="novaciky"> Novaciky</label>
            </li>
            <li>
              <input type="checkbox" id="baby" name="topic[]" value="baby">
              <label for="baby"> Baby</label>
            </li>
            <li>
              <input type="checkbox" id="vzacne" name="topic[]" value="vzacne">
              <label for="vzacne"> Vzacne</label>
            </li>
            <li><input type="submit" value="Sort"></li>
          </ul>
        </div>
      </form>
      <form action="delete.php" method="get">
        <ol>
          <?php
          // Display Articles
          foreach ($articles as $article) { ?>
            <li>
              <?php echo $article['article_title']; ?>
              <input type="checkbox" name="articles[]" value="<?php echo $article['article_id'] ?>">
            </li>
          <?php } ?>
        </ol>
        <input type="submit" value="Delete article" onclick="clicked(event)">
      </form>
    </div>
    <script>
      // Popup when deleting an article
      function clicked(event) {
        if (!confirm('This article is about to be deleted! Are you sure?')) {
          event.preventDefault();
        }
      }
      // Open/close article filter
      let checkList = document.getElementById('list');
      checkList.getElementsByClassName('anchor')[0].onclick = function(e) {
        if (checkList.classList.contains('visible'))
          checkList.classList.remove('visible');
        else
          checkList.classList.add('visible');
      }
    </script>
  </body>

  </html>

<?php
} else {
  header('Location: index.php');
}
?>