<?php
  $accepted_origins = array("http://localhost", "http://192.168.1.1", "https://hard-panda-100.loca.lt");

  $imageFolder = "../images/blog/";

  reset ($_FILES);
  $temp = current($_FILES);
  if (is_uploaded_file($temp['tmp_name'])){

    if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png", "jpeg", "webp"))) {
        header("HTTP/1.1 400 Invalid extension.");
        return;
    }

    $filetowrite = $imageFolder . $temp['name'];
    move_uploaded_file($temp['tmp_name'], $filetowrite);

    echo json_encode(array('location' => $filetowrite));
  } else {
    header("HTTP/1.1 500 Server Error");
  }
