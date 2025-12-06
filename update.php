<!doctype html>
<?php
require('functions.php');
require('values.php');
html_head("Update Ticket");
require('header.php');
require('sidebar.php');

# Code for your web page follows.
if (!isset($_POST['submit']))
{
?>
<h2>Ticket Update</h2>
<p>Please fill in the fields to update a ticket</p>
<form action="update.php" method="post">
  <table border="0">
    <tr>
      <td>Ticket Number</td>
      <td align="left"><input type="text" name="ticket" placeholder="Ticket #" size="50" maxlength="50"></td>
    </tr>
    <tr>
      <td>Type</td>
      <td>
      <select name="type">
       <option value="Request">Request</option>
       <option value="Incident">Incident</option>  
      </select>
    </td>
    </tr>
    <tr>
      <td>Details</td>
      <td align="left"><textarea name="details" placeholder="Write Details About the Ticket" rows="5" cols="45"></textarea></td>
    </tr>
    <tr>
      <td>Priority</td>
      <td>
      <select name="priority">
       <option value="Low">Low</option>
       <option value="Medium">Medium</option>    
       <option value="High">High</option>  
      </select>
    </td>
    </tr>
    <tr>
      <td>Date</td>
      <td align="left"><input type="text" name="date" placeholder="Valid Date Format YYYY-MM-DD" size="50" maxlength="50"></td>
    </tr>
    <tr>
      <td colspan="2" align="right"><input type="submit" name="submit" value="Submit"></td>
    </tr>
  </table>
</form>
<br> <br>
<?php
} else {
  $ticket = $_POST['ticket'];
  $type = $_POST['type'];
  $details = $_POST['details'];
  $priority = $_POST['priority'];
  $date = $_POST['date'];
 // $time= date('Y-m-d H:i:s', strtotime($date));
 
 try
  {
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $result = $db->query("SELECT CURDATE()")->fetch();
  $today = $result[0];

  $ticket=test_input($ticket);
  if ( empty($ticket) )
  {
     try_again("Ticket Number cannot be empty.");
  } elseif (!is_numeric($ticket)) 
    {
     try_again("Only numbers allowed for ticket");
    } 

  $details=test_input($details);
  if ( empty($details) )
  {
     try_again("Details cannot be empty.");
  }
  $date=test_input($date);
  if ( empty($date) )
  {
     try_again("Date cannot be empty.");
  }
  if ( !MyCheckDate($date) )
  {
     try_again("You have entered an invalid date. Format is yyyy-mm-dd.");
  } 
  if ( $date != $today )
  {
     try_again("The date is different than today: ".$today);
  }
  $time= date('Y-m-d H:i:s', strtotime($date));
  $type=trim($type);
  if ($type == "Incident")
  {
     $tickets = "incidents";
  } elseif ($type == "Request") 
    {
     $tickets = "requests";
    }

  $sql = "SELECT COUNT(*) from $tickets WHERE id = '$ticket'";
  $result = $db->query($sql)->fetch();
  if ( $result[0] == 0 )
  {
     try_again("Invalid Ticket Number.");
  }

 // echo $ticket;
   
 $db->exec("UPDATE $tickets SET details = '$details', priority = '$priority', date = '$time' WHERE id = $ticket");
   
//  $last_id = $db->lastInsertId();

  print "<h2>Ticket Updated</h2>";
  print "<table border=1>";
  print "<tr>";
  print "<td>Customer</td><td>Title</td><td>Type</td><td>Details</td><td>Priority</td><td>Date</td>";
  print "</tr>";
  $row = $db->query("SELECT * FROM $tickets WHERE id = '$ticket'")->fetch(PDO::FETCH_ASSOC);
  print "<tr>";
//  print "<td>".$row[customers_id]."</td>";
  $user_id = $row['customers_id'];
  if ($user_id>0)
  {
    $result = $db->query("SELECT * FROM customers WHERE id = $user_id ")->fetch();
    $user_name = $result['name']; 
  }
  print "<td>".$user_name."</td>";
  print "<td>".$row['title']."</td>";
  print "<td>".$row['type']."</td>";
  print "<td>".$row['details']."</td>";
  print "<td>".$row['priority']."</td>";
  print "<td>".$row['date']."</td>";
  print "</tr>";
  print "</table>";

  $db = NULL;
  }

  catch(PDOException $e)
  {
    echo 'Exception : '.$e->getMessage();
    echo "<br/>";
    $db =  NULL;
  }
}
require('footer.php');
?>
