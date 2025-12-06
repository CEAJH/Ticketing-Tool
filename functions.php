<?php
require('values.php');
function html_head($title) {
  echo '<html lang="en">';
  echo '<head>';
  echo '<meta charset="utf-8">';
  echo "<title>$title</title>";
  echo '<link rel="stylesheet" href="project.css">';
  echo '</head>';
  echo '<body>';
}

function try_again($str){
  echo $str;
  echo "<br/>";
  echo '<a href="#" onclick="history.back(); return false;">Try Again</a>';
  require('footer.php');
  exit;
}

function MyCheckDate ( $postedDate ) {
  if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $postedDate, $datebit)){
    return checkdate($datebit[2], $datebit[3], $datebit[1]);;
  } else {
      return false;
  }
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
