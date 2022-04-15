<?php

session_start();

include_once('../connections/connection.php');

// Registration check
if (isset($_SESSION['logged_in']) || isset($_SESSION['admin'])) {
  //Main page
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
        <a href="../index.php" id="logo">CMS</a>
      </div>
      <ol class="main_menu">
        <li><a href="add.php">Add Article</a></li>
        <li><a href="edit-list.php">Edit Article</a></li>
        <li><a href="delete.php">Delete Article</a></li>
        <?php if (isset($_SESSION['admin'])) { ?>
          <li><a href="edit-users.php">Edit user list</a></li>
        <?php } ?>
        <li><a href="logout.php">Logout</a></li>
      </ol>

    </div>
  </body>

  </html>
<?php
} else {
  if (isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    if ($_POST['password'] != "") {
      $password = md5($_POST['password'] . $_POST['username']);
    }
    if (empty($username) or empty($password)) {
      $error = 'All fields are required!';
    } else {
      $query = $pdo->prepare("SELECT * FROM Users WHERE user_name = ? AND user_password = ?");
      $query->bindValue(1, $username);
      $query->bindValue(2, $password);

      $query->execute();
      $num = $query->rowCount();
      $user = $query->fetch();

      if ($num == 1 && !$user['edit_users_list']) {
        $_SESSION['admin'] = true;
        header('location: index.php');
      } else if ($num == 1) {
        $_SESSION['logged_in'] = true;
        header('Location: index.php');
        exit();
      } else {
        $error = 'Incorrect details!';
      }
    }
  } ?>
  <!-- Registration form -->
  <html>

  <head>
    <title>CMS</title>
    <link rel="stylesheet" href="../assets/style.css" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <script src="https://kit.fontawesome.com/8d6b8d0e75.js" crossorigin="anonymous"></script>
  </head>

  <body>
    <div class="container">
      <div class="cms_header">
        <a href="../index.php" id="logo">CMS</a>
      </div>
      <?php if (isset($error)) { ?>
        <small style="color:#aa0000;"> <?php echo $error; ?> </small>
        <br>
      <?php } ?>
      <form action="index.php" method="post" autocomplete="off">
        <input type="text" name="username" placeholder="Username"><br>
        <input type="password" name="password" id="password" placeholder="Password">
        <i class="far fa-eye" id="togglePassword"></i><br>
        <input type="submit" value="Login">
      </form>
    </div>
    <script src="../../js/signIn.js"></script>
    <script>
      const togglePassword = document.getElementById('togglePassword');
      const password = document.getElementById('password');
      togglePassword.addEventListener('click', function(e) {
        if (password.type === "password") {
          password.type = "text";
        } else {
          password.type = "password";
        }
        this.classList.toggle('fa-eye-slash');
      });
    </script>
  </body>

  </html>

<?php
}
?>