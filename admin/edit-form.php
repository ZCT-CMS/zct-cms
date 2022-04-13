<?php

session_start();
include_once('../connections/connection.php');
include_once('../connections/article.php');
$article = new Article;
$image = new Article;

// Registration check
if (isset($_SESSION['logged_in']) || isset($_SESSION['admin'])) {
  // Processing data
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $data = $article->fetch_data($id);
    $images = $image->fetch_image($id);
  } else if (isset($_POST['title'], $_POST['content'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $topics = explode(" ", $_POST['topics']);
    $preview = $_POST['preview'];
    $content = $_POST['content'];

    $tags = array();
    $card_tags = array();
    for ($i = 0; $i < count($topics); $i++) {
      switch ($topics[$i]) {
        case "research":
          array_push($tags, 1);
          array_push($card_tags, "research");
          break;
        case "sales":
          array_push($tags, 2);
          array_push($card_tags, "sales");
          break;
        case "hr":
          array_push($tags, 3);
          array_push($card_tags, "hr");
          break;
        case "data":
          array_push($tags, 4);
          array_push($card_tags, "data");
          break;
        case "tools":
          array_push($tags, 5);
          array_push($card_tags, "tools");
          break;
        case "automation":
          array_push($tags, 6);
          array_push($card_tags, "automation");
          break;
      }
    }

    $card_tags = join(" ", $card_tags);

    $file_name = $_FILES["uploadfile"]["name"];
    $temp_name = $_FILES["uploadfile"]["tmp_name"];
    $folder = "../images/blog/" . $file_name;
    $allowed_types = array("jpg", "jpeg", "png", "webp", "svg");
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    echo "FILENAME: {$file_name}";
    echo "FILE EXT: {$file_ext}";
    echo "ALLOWED: {$allowed_types}";
    if (in_array(strtolower($file_ext), $allowed_types)) {
      copy($temp_name, $folder);
    }

    if (empty($title) or empty($content)) {
      $error = 'All fields are required!';
    } else {
      // Loading data into the database
      $query = $pdo->prepare('UPDATE Articles SET article_title=?, article_author=?, article_tags=?, article_preview=?, article_content=?, article_timestamp=? WHERE article_id=?');
      $query->bindValue(1, $title, PDO::PARAM_STR);
      $query->bindValue(2, $author, PDO::PARAM_STR);
      $query->bindValue(3, $card_tags, PDO::PARAM_STR);
      $query->bindValue(4, $preview, PDO::PARAM_STR);
      $query->bindValue(5, $content, PDO::PARAM_STR);
      $query->bindValue(6, time());
      $query->bindValue(7, $id, PDO::PARAM_INT);
      $query->execute();

      $query = $pdo->prepare('DELETE FROM Articles_tags WHERE article = ?');
      $query->bindValue(1, $id);
      $query->execute();
      for ($i = 0; $i < count($tags); $i++) {
        $query = $pdo->prepare('INSERT INTO Articles_tags (article, tag) VALUES (?, ?)');
        $query->bindValue(1, $id);
        $query->bindValue(2, $tags[$i]);
        $query->execute();
      }

      if (!empty($file_name) && !empty($temp_name)) {
        $query = $pdo->prepare('DELETE FROM Images WHERE image_id = ?');
        $query->bindValue(1, $id);
        $query->execute();
        $query = $pdo->prepare('INSERT INTO Images (image_id, filename) VALUES (?, ?)');
        $query->bindValue(1, $id);
        $query->bindValue(2, $file_name);
        $query->execute();
      }
      // Back to the main pageм
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
        <h4>Edit Article</h4>
      </div>
      <?php if (isset($error)) { ?>
        <!-- Error when trying to submit an empty form -->
        <small style="color:#aa0000;"><?php echo $error; ?></small>
        <br /><br />
      <?php } ?>
      <!-- Form for editing an article -->
      <form action="edit-form.php" method="post" autocomplete="off" enctype="multipart/form-data">
        <input type="text" name="id" value="<?php echo $data['article_id'] ?>" style="display:none;">
        <input type="text" name="title" placeholder="Title" value="<?php echo $data['article_title'] ?>" required />
        <br />
        <input type="text" name="author" placeholder="Author" value="<?php echo $data['article_author'] ?>" required />
        <br />
        <input type="text" name="topics" placeholder="Topics: hr data..." value="<?php echo $data['article_tags'] ?>" required>
        <br />
        <input type="text" name="preview" placeholder="Preview text" value="<?php echo $data['article_preview'] ?>" required />
        <br />
        <textarea id="mytextarea" rows="15" cols="40" placeholder="Content" name="content"><?php echo $data['article_content'] ?></textarea>
        <br />
        <label for="image">Click To Change Image File</label>
        <input type="file" name="uploadfile" id="image" style="display:none;">
        <span id="old-image"><?php echo $images['filename'] ?></span>
        <br>
        <input type="submit" value="Update article">
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