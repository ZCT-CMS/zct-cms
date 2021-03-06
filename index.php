<?php
include_once('connections/connection.php');
include_once('connections/article.php');

$article = new Article;
$image = new Article;
$articles = $article->fetch_all();
$topics = [];

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
  if ($all == 0) {
    $articles = $article->fetch_sorted($topics);
  }
}
?>

<!DOCTYPE html>
<html lang="sk">

<head>
  <?php
  include('includes/includes_head.php');
  ?>

  <title>Váš zelený priateľ</title>

  <meta name="title" content="Zeleny priatel">
  <meta name="description" content="Second zct assignment">

</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">
  <div class="site-wrap">

    <section class="header header-career">
      <div class="container">
        <div class="row align-items-left">
          <div class="subpage_head col-lg-8 text-left">
            <h1>Váš zelený priateľ</h1>
            <h5>Asistent pri hľadaní dokonalej domácej rastliny</h5>
          </div>
        </div>
      </div>
    </section>

    <section class="site-section">
      <div class="container">
        <h2 class="section-title gradient">Články</h2>

        <div id="filterBtnContainer" class="mb-3">
          <!-- Article filter -->
          <form action="index.php" method="get">
            <div id="list" class="dropdown-check-list" tabindex="100">
              <span class="anchor">Vyberte témy</span>
              <ul class="items">
                <li>
                  <input type="checkbox" id="all" name="topic[]" value="all">
                  <label for="all">Všetky</label>
                </li>
                <li>
                  <input type="checkbox" id="kancelaria" name="topic[]" value="kancelaria">
                  <label for="kancelaria"> Kancelária</label>
                </li>
                <li>
                  <input type="checkbox" id="novaciky" name="topic[]" value="novaciky">
                  <label for="novaciky"> Nováčik</label>
                </li>
                <li>
                  <input type="checkbox" id="baby" name="topic[]" value="baby">
                  <label for="baby"> Baby</label>
                </li>
                <li>
                  <input type="checkbox" id="vzacne" name="topic[]" value="vzacne">
                  <label for="vzacne"> Vzácne</label>
                </li>
                <li><input type="submit" value="Sort"></li>
              </ul>
            </div>
          </form>
        </div>
        <!-- Articles -->
        <div class="row mb-0">
          <?php
          foreach ($articles as $article) {
            $images = $image->fetch_image($article['article_id']);
          ?>
            <!-- Article template -->
            <div class="col-md-6 col-xl-3 mb-4 article-card">
              <a href="article.php?id=<?php echo $article['article_id']; ?>" class="custom-card">
                <div class="card main-card card-article h-100">
                  <img src="images/blog/<?php echo $images['filename']; ?>" class="card-img-top card-img-article" />
                  <div class="card-body">
                    <div class="tags">
                      <?php
                      $tags = explode(" ", $article['article_tags']);
                      foreach ($tags as $tag) {
                        $clean_tag = $tag;
                      ?>
                        <span class="tag <?php echo $clean_tag ?>"></span>
                      <?php } ?>
                    </div>
                    <h5 class="text-dark"><?php echo $article['article_title'] ?></h5>
                    <p><?php echo $article['article_preview'] ?></p>
                  </div>
                  <div class="card-footer d-flex justify-content-between">
                    <div class="col-auto"><i class="fas fa-calendar-alt mr-1"></i><?php echo date('l jS Y', $article['article_timestamp']) ?></div>
                    <!-- <div class="col-auto text-right"><i class="fas fa-stopwatch"></i> 3min</div> -->
                  </div>
                </div>
              </a>
            </div>

          <?php } ?>
        </div>
      </div>
    </section>
    <!-- <small><a href="admin">admin</a></small> -->

    <!-- FOOTER -->

    <div class="nav-div" id="kontakt"></div>
    <?php
    include('includes/footer.php');
    ?>



  </div>
  <!-- .site-wrap -->
  <script src="js/filter.js"></script>
  <script>
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