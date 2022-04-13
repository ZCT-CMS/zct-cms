<?php
session_start();

// Registration check
if (isset($_SESSION['logged_in']) || isset($_SESSION['admin'])) {
  if (isset($_POST['submit'])) {
    $upload_dir = '../../images/blog' . DIRECTORY_SEPARATOR;
    $allowed_types = array('jpg', 'png', 'jpeg', 'webp', 'svg');

    $maxsize = 3 * 1024 * 1024;
    // Preparing and uploading images
    if (!empty(array_filter($_FILES['images']['name']))) {
      foreach ($_FILES['images']['tmp_name'] as $key => $value) {
        $file_tmpname = $_FILES['images']['tmp_name'][$key];
        $file_name = $_FILES['images']['name'][$key];
        $file_size = $_FILES['images']['size'][$key];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

        $filepath = $upload_dir . $file_name;
        if (in_array(strtolower($file_ext), $allowed_types)) {
          if (file_exists($filepath)) {
            $filepath = $upload_dir . time() . $file_name;
          }
          if ($file_size > $maxsize) {
            echo "Error: File size is larger than the allowed limit.";
          } else if (!move_uploaded_file($file_tmpname, $filepath)) {
            echo "Error uploading {$file_name} <br />";
          }
        }
      }
    } else {
      echo "No files selected.";
    }
  }
?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" type="text/css" href="../assets/style.css" />
    <script src="https://kit.fontawesome.com/8d6b8d0e75.js" crossorigin="anonymous"></script>
    <title>Upload images</title>
  </head>

  <body>
    <div class="container">
      <div class="cms_header">
        <a href="index.php" id="logo">CMS</a>
        <br />
        <h4>Upload images</h4>
      </div>
      <?php if (isset($error)) { ?>
        <small style="color:#aa0000;"><?php echo $error; ?></small>
        <br /><br />
      <?php } ?>
      <!-- Image upload form -->
      <form action="upload-img.php" method="post" autocomplete="off" enctype="multipart/form-data">
        <label for="image">Click To Add Image File</label>
        <input type="file" name="images[]" id="image" style="display:none;" multiple required>
        <!-- List of uploaded images -->
        <div id="listOfElmnts"></div>
        <input type="submit" name="submit" value="Upload images">
      </form>
    </div>
    <script>
      // Loading/deleting images in/from a form
      let input = document.getElementById('image');
      let list = document.getElementById('listOfElmnts');
      input.addEventListener('change', updateImgList);

      function updateImgList() {
        list.innerHTML = '<ul>'
        for (let i = 0; i < input.files.length; i++) {
          list.innerHTML += '<li onclick="deleteImage(this.childNodes[0].innerHTML)"><span>' + input.files.item(i).name + '</span><i class="fas fa-trash-alt"></i></li>';
          console.log(input.files.item(i).name);
        }
        list.innerHTML += '</ul>';
      }

      function deleteImage(imgName) {
        console.log("you want to delete:" + imgName);
        for (let i = 0; i < input.files.length; i++) {
          if (input.files.item(i).name === imgName) {
            let imgList = Array.from(input.files);
            imgList.splice(i, 1);
            console.log(imgList);
            const dataTransfer = new DataTransfer();
            for (let j = 0; j < imgList.length; j++) {
              dataTransfer.items.add(imgList[j]);
            }
            input.files = dataTransfer.files;
            console.log(input.files);
            updateImgList();
          }
        }
      }
    </script>
  </body>

  </html>

<?php } else {
  header('Location: index.php');
}
?>