<?php
session_start();

include_once('../connections/connection.php');
include_once('../connections/user.php');
$user = new User;
$users = $user->fetch_all();

if (isset($_SESSION['admin'])) {
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../assets/style.css" />
    <script src="https://kit.fontawesome.com/8d6b8d0e75.js" crossorigin="anonymous"></script>
    <title>Users</title>
  </head>

  <body>
    <div class="container">
      <div class="cms_header">
        <a href="index.php" id="logo">CMS</a>
        <br />
        <h4>List of users who have access to the admin panel:</h4>
      </div>
      <ol id="listOfElmnts">
        <?php foreach ($users as $user) { ?>
          <li>
            <span><?php echo $user['user_name'] ?></span>
            <div class="listIcons">
              <?php
              if ($user['edit_users_list'] == 0) {
                $toggle = 'times';
              } else {
                $toggle = 'plus';
              } ?>
              <a class="user-links" href="change-permission.php?id=<?php echo $user['user_id'] ?>" onclick="changePermission(event, '<?php echo $toggle ?>')">
                <i class="fa fa-user-<?php echo $toggle ?>"></i>
              </a>
              <a class="user-links" href="remove-user.php?id=<?php echo $user['user_id'] ?>" onclick="deleteUser(event)">
                <i class="fas fa-trash-alt"></i>
              </a>
              <div>
          </li>
        <?php } ?>
      </ol>
      <form action="add-user.php" method="post" autocomplete="off">
        <i class="far fa-eye" id="togglePassword" style="bottom: -5.75rem;"></i>
        <input type="text" name="username" placeholder="Username" id="username" required />
        <input type="password" id="password" name="password" placeholder="Password" />
        <label>Allow this user to edit the list of administrators:</label>
        <input type="checkbox" name="edit" value="allow">
        <br>
        <input type="submit" value="Add new user" onclick="addUser(event)" ; />
      </form>
    </div>
    <script>
      const togglePassword = document.getElementById('togglePassword');
      const password = document.getElementById('password');
      const username = document.getElementById('username');

      togglePassword.addEventListener('click', function(e) {
        if (password.type === "password") {
          password.type = "text";
        } else {
          password.type = "password";
        }
        this.classList.toggle('fa-eye-slash');
      });

      function deleteUser(event) {
        if (!confirm('This user is about to be deleted! Are you sure?')) {
          event.preventDefault();
        }
      }

      function changePermission(event, toggle) {
        if (toggle === 'plus') {
          if (!confirm('Are you sure you want to allow this user to edit the list of admins?')) {
            event.preventDefault();
          }
        } else {
          if (!confirm('Are you sure you want to prevent this user from editing the list of admins?')) {
            event.preventDefault();
          }
        }

      }

      function addUser(event) {
        console.log("test");
        const mailformat = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!username.value.match(mailformat) && password.value === "") {
          console.log("test enter");
          event.preventDefault();
          alert('You can add a user by entering only his email, or email and login!');
        }
      }
    </script>
  </body>

  </html>
<?php } else {
  header('Location: index.php');
} ?>