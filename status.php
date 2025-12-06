<!doctype html>
<?php
require('functions.php');
require('values.php');
html_head("ticket status");
require('header.php');
require('sidebar.php');

# Code for your web page follows.
if (!isset($_POST['submit']))
{
?>
<h2>Ticket Status</h2>
<form action="status.php" method="post">
  <table border="0">
    <tr bgcolor="#cccccc">
    </tr>
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
      <td colspan="2" align="right"><input type="submit" name="submit" value="Submit"></td>
    </tr>
  </table>
</form>
<br> <br>
<?php
} else {
  $type = $_POST['type'];
  $ticket = $_POST['ticket'];
  try
  {
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $ticket=test_input($ticket);    
    if ( empty($ticket) ) 
    {
      try_again("Ticket number cannot be empty.");
    }  elseif (!is_numeric($ticket))
      {
        try_again("Only numbers allowed for ticket");
      }

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
   
 //   echo $tickets;
 
    print "<h2>Ticket Status</h2>"; 
    print "<table border=1>";
    print "<tr>";
    print "<td>Customer</td><td>Title</td><td>Type</td><td>Details</td><td>Priority</td><td>Date</td>";
    print "</tr>";
    $row = $db->query("SELECT * FROM $tickets WHERE id = $ticket")->fetch(PDO::FETCH_ASSOC);
    print "<tr>";
   // print "<td>".$row['customers_id']."</td>";
    $user_id = $row['customers_id'];
    if ($user_id>0)
    {
       $result = $db->query("SELECT * FROM customers WHERE id = $user_id")->fetch();
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
    $db = NULL;
  }

}

require('footer.php');
?>
