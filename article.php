<?php

include_once('connections/connection.php');
include_once('connections/article.php');

$article = new Article;
$image = new Article;

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $data = $article->fetch_data($id);
  $images = $image->fetch_image($id);
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <?php
    include('includes/includes_head.php');
    ?>
    <!-- Primary Meta Tags -->
    <title><?php echo $data['article_title']; ?></title>
  </head>

  <body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">
    <div class="site-wrap">

      <!-- Article header -->

      <section class="header" style="background: linear-gradient(120deg, var(--color-accent-transparent) 0%, var(--color-primary-dark-transparent) 35%, var(--color-primary-transparent) 70%, var(--color-light-transparent) 100%), url('/images/blog/<?php echo $images['filename']; ?>');">
        <div class="container">
          <div class="row align-items-left">
            <div class="subpage_head col-lg-8 text-left">
              <h1><?php echo $data['article_title']; ?></h1>
              <h6 class="text-white"><?php echo $data['article_author'] ?>
                <b><?php echo date('l jS Y', $data['article_timestamp']) ?></b>
              </h6>
            </div>
          </div>
        </div>
      </section>

      <!-- Article content -->

      <section class="site-section section-2">
        <div class="container article-container">
          <p>
            <?php echo $data['article_content']; ?>
          </p>
          <div class="mt-4">
            <a href="/" class="btn btn-primary"><i class="fas fa-chevron-left mr-2"></i>BACK</a>
          </div>
        </div>
      </section>

      <!-- FOOTER -->

      <div class="nav-div" id="kontakt"></div>
      <?php
      include('includes/footer.php');
      ?>

    </div>
    <!-- .site-wrap -->
    <script src="/js/scroll_progress.js"></script>

  </body>

  </html>

<?php
} else {
  header('Location: index.php');
  exit();
}

?>