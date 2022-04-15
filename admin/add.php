<?php

session_start();
include_once('../connections/connection.php');
include_once('../connections/article.php');
$article = new Article;

// Registration check
if (isset($_SESSION['logged_in']) || isset($_SESSION['admin'])) {
  // Processing data
  if (isset($_POST['title'], $_POST['content'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $topics = explode(" ", $_POST['topics']);
    $preview = $_POST['preview'];
    $content = $_POST['content'];

    $tags = array();
    $card_tags = array();
    for ($i = 0; $i < count($topics); $i++) {
      switch ($topics[$i]) {
        case "kancelaria":
          array_push($tags, 1);
          array_push($card_tags, "kancelaria");
          break;
        case "novaciky":
          array_push($tags, 2);
          array_push($card_tags, "novaciky");
          break;
        case "baby":
          array_push($tags, 3);
          array_push($card_tags, "baby");
          break;
        case "vzacne":
          array_push($tags, 4);
          array_push($card_tags, "vzacne");
          break;
      }
    }
    $card_tags = join(" ", $card_tags);

    $thumbnail_name = $_FILES["thumbnail"]["name"];
    $temp_name = $_FILES["thumbnail"]["tmp_name"];
    $folder = "../images/blog/" . $thumbnail_name;

    $num = 1;
    while (file_exists($folder)) {
      $file_name = pathinfo($thumbnail_name, PATHINFO_FILENAME);
      $extension = pathinfo($thumbnail_name, PATHINFO_EXTENSION);
      $file_name = (string)$file_name . $num;
      $num++;
      $thumbnail_name = $file_name . "." . $extension;
      $folder = "../images/blog/" . $thumbnail_name;
    }
    $allowed_types = array("jpg", "jpeg", "png", "webp", "svg");
    $thumbnail_ext = pathinfo($thumbnail_name, PATHINFO_EXTENSION);

    if (in_array(strtolower($thumbnail_ext), $allowed_types)) {
      copy($temp_name, $folder);
    }

    if (empty($title) or empty($content)) {
      $error = 'All fields are required!';
    } else {
      // Loading data into the database
      $query = $pdo->prepare('INSERT INTO Articles (article_title, article_author, article_tags, article_preview, article_content, article_timestamp) VALUES (?, ?, ?, ?, ?, ?)');
      $query->bindValue(1, $title);
      $query->bindValue(2, $author);
      $query->bindValue(3, $card_tags);
      $query->bindValue(4, $preview);
      $query->bindValue(5, $content);
      $query->bindValue(6, time());
      $query->execute();

      $index = $pdo->lastInsertId();
      for ($i = 0; $i < count($tags); $i++) {
        $query = $pdo->prepare('INSERT INTO Articles_Tags (article, tag) VALUES (?, ?)');
        $query->bindValue(1, $index);
        $query->bindValue(2, $tags[$i]);
        $query->execute();
      }

      $query = $pdo->prepare('INSERT INTO Images (image_id, filename) VALUES (?, ?)');
      $query->bindValue(1, $index);
      $query->bindValue(2, $thumbnail_name);
      $query->execute();
      // Back to the main page
      header('Location: index.php');
    }
  }
?>
  <!DOCTYPE html>
  <html>

  <head>
    <title>Add Article</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" type="text/css" href="../assets/style.css" />
    <script src="https://cdn.tiny.cloud/1/tsaczjslj59s59qaw8ciwbd0iwuqwzgnm7unxyyz6xhrhfir/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: '#mytextarea',
        plugins: 'fullscreen a11ychecker advcode casechange export formatpainter linkchecker autolink lists link checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker preview image code',
        toolbar: 'fullscreen preview undo redo | formatselect | ' +
          'bold italic forecolor | alignleft aligncenter ' +
          'alignright alignjustify | bullist numlist outdent indent | ' +
          'link | table',
        toolbar_mode: 'floating',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        apply_source_formatting: false,
        images_upload_url: 'postAcceptor.php',
        image_title: true,
        automatic_uploads: true,
        file_picker_types: 'image'
      });
    </script>
  </head>

  <body>
    <div class="container">
      <div class="cms_header">
        <a href="index.php" id="logo">CMS</a>
        <br />
        <h4>Add Article</h4>
      </div>
      <?php if (isset($error)) { ?>
        <!-- Error when trying to submit an empty form -->
        <small style="color:#aa0000;"><?php echo $error; ?></small>
        <br /><br />
      <?php } ?>
      <!-- Form for adding an article -->
      <form action="add.php" method="post" autocomplete="off" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Title" required />
        <br />
        <input type="text" name="author" placeholder="Author" required />
        <br />
        <input type="text" name="topics" placeholder="Topics: kancelaria novaciky..." required>
        <br />
        <input type="text" name="preview" placeholder="Preview text" required />
        <br />
        <textarea id="mytextarea" rows="15" cols="40" placeholder="Content" name="content"></textarea>
        <br />
        <label for="image">Click To Add Image File</label>
        <input type="file" name="thumbnail" id="image" style="display:none;" required>
        <span id="old-image"><?php echo $images['filename'] ?></span>
        <br>
        <input type="submit" value="Add article">
      </form>
    </div>
    <script>
      // Show filename when uploading a photo
      let input = document.getElementById('image');
      input.addEventListener('input', function(e) {
        let newImage = input.value;
        fullPath = newImage.split('\\');
        newImage = fullPath[fullPath.length - 1];
        document.getElementById('old-image').innerText = newImage;
      });
    </script>
  </body>

  </html>
<?php
} else {
  header('Location: index.php');
}

?>