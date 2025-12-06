<!doctype html>
<?php
require('values.php');
require('functions.php');
html_head("New Customer");
require('header.php');
require('sidebar.php');

# Code for your web page follows.
if (!isset($_POST['submit']))
{
?>
<h2>New Customer</h2>
<p>Please fill in the fields to complete registration</p>
<form action="register.php" method="post">
  <table border="0">
    <tr bgcolor="#cccccc">
      
    </tr>
    <tr>
      <td>Name: </td>
      <td align="left"><input type="text" name="name" placeholder="Steve Jobs" size="50" maxlength="50"></td>
    </tr>
    <tr>
      <td>Company</td>
      <td align="left"><input type="text" name="company"  placeholder="Apple" size="50" maxlength="50"></td>
    </tr>
    <tr>
      <td>Phone</td>
      <td align="left"><input type="text" name="phone"  placeholder="916-276-5030" size="50" maxlength="50"></td>
    </tr>
    <tr>
      <td>Email</td>
      <td align="left"><input type="text" name="email"  placeholder="stevejobs@apple.com" size="50" maxlength="50"></td>
    </tr>
    <tr>
      <td>Contact Time</td>
      <td align="left"><input type="text" name="contact" placeholder="9am to 5pm M-F" size="50" maxlength="50"></td>
    </tr>
    <tr>
      <td colspan="2" align="right"><input type="submit" name="submit" value="Submit"></td>
    </tr>
  </table>
</form>
<br> <br>
<?php
} else {
  $name = $_POST['name'];
  $company = $_POST['company'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $contact = $_POST['contact'];
  
  try
  {
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $name = test_input($name);
    if ( empty($name)) {
      try_again("Name cannot be empty.");
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
        try_again("Only letters and white space allowed for name");
      }
    $company = test_input($company);
    if ( empty($company)) {
      try_again("Company cannot be empty.");
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/",$company)) {
        try_again("Only letters and white space allowed for company");
      }
    $phone = trim($phone);
    if ( empty($phone)) {
      try_again("Phone cannot be empty.");
    }  elseif (!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $phone)) {
        try_again("Invalid number format");
      }
    $email = test_input($email);
    if ( empty($email)) {
      try_again("Email cannot be empty.");
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try_again("Invalid email format");
      }
    $contact = test_input($contact);
    if ( empty($contact)) {
      try_again("Contact Time cannot be empty.");
    }

    $sql = "select count(*) from customers where email = '$email'";
    $result = $db->query($sql)->fetch();
    if ( $result[0] > 0) {
      try_again("$email is not unique. Email must be unique.");
    }

    $db->exec("INSERT INTO customers (name, company, phone, email, contact) VALUES ('$name', '$company', '$phone', '$email', '$contact');");   

    $last_id = $db->lastInsertId();

    print "<h2>Customer Added</h2>";
    print "<table border=1>";
    print "<tr>";
    print "<td>Customer Id</td><td>Name</td><td>Company</td><td>Phone</td><td>Email</td><td>Contact Time</td>";
    print "</tr>";
    $row = $db->query("SELECT * FROM customers WHERE id = '$last_id'")->fetch(PDO::FETCH_ASSOC);
    print "<tr>";
    print "<td>".$row['id']."</td>";
    print "<td>".$row['name']."</td>";
    print "<td>".$row['company']."</td>";
    print "<td>".$row['phone']."</td>";
    print "<td>".$row['email']."</td>";
    print "<td>".$row['contact']."</td>";
    print "</tr>";
    print "</table>";

    $db = NULL;
  }
  
  catch(PDOEXception $e){
    echo 'Exception : '.$e->getMessage().'<br/>';
    $db = NULL;
  }
}
require('footer.php');
?>
