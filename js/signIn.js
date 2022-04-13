function onSignIn(googleUser) {
  var id_token = googleUser.getAuthResponse().id_token;
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'https://localhost/blog/admin/index.php');
  xhr.setRequestHeader('Content-Type', 'aplication/x-www-form-urlencoded');
  xhr.onload = function(){
    console.log('Signed in as: ' + xhr.responseText);
  }
  xhr.send('idtoken=' + id_token);
}

