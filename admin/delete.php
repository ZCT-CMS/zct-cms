<?php

session_start();

include_once('../connections/connection.php');
include_once('../connections/article.php');
$article = new Article;

// Registration check
if (isset($_SESSION['logged_in']) || isset($_SESSION['admin'])) {
  if (isset($_GET['articles'])) {
    $articles = $_GET['articles'];
    for ($i = 0; $i < count($articles); $i++) {
      $id = $articles[$i];
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
    $topics = $_GET['topic'];
    $sorted = array();
    for ($i = 0; $i < count($topics); $i++) {
      if ($topics[$i] != 'all') {
        $result = $article->fetch_sorted($topics[$i]);
        $sorted = array_merge($sorted, $result);
      } else {
        unset($sorted);
        break;
      }
    }
  }
  $articles = $article->fetch_all();

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
              <input type="checkbox" id="research" name="topic[]" value="research">
              <label for="research"> research</label>
            </li>
            <li>
              <input type="checkbox" id="sales" name="topic[]" value="sales"> <label for="sales"> sales</label>
            </li>
            <li>
              <input type="checkbox" id="hr" name="topic[]" value="hr">
              <label for="hr"> hr</label>
            </li>
            <li>
              <input type="checkbox" id="data" name="topic[]" value="data">
              <label for="data"> data</label>
            </li>
            <li>
              <input type="checkbox" id="tools" name="topic[]" value="tools">
              <label for="tools"> tools</label>
            </li>
            <li>
              <input type="checkbox" id="automation" name="topic[]" value="automation">
              <label for="automation"> automation</label>
            </li>
            <li><input type="submit" value="Sort"></li>
          </ul>
        </div>
      </form>
      <form action="delete.php" method="get">
        <ol>
          <?php
          // Remove duplicate articles
          if (isset($sorted)) {
            $articles = $sorted;
            $last_id = -1;
            for ($i = 0; $i < count($articles); $i++) {
              for ($j = 0; $j < count($articles); $j++) {
                if ($articles[$j][0] == $articles[$i][0] && $i != $j) {
                  array_splice($articles, $i, $i);
                }
                $last_id = $articles[$i][0];
              }
            }
          }
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