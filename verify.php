<?php

mysql_connect("localhost", DATABASE, "PASSWORD") or die(mysql_error());

mysql_select_db("USER_TABLENAME") or die(mysql_error());

if ($_POST['form_submitted'] == '1') {
##User is registering, insert data until we can activate it

$activationKey =  mt_rand() . mt_rand() . mt_rand() . mt_rand() . mt_rand();
$username = mysql_real_escape_string($_POST[username]);
$password = mysql_real_escape_string($_POST[password]);

$email = mysql_real_escape_string($_POST[email]);

$sql="INSERT INTO users (username, password, email, activationkey, status) VALUES ('$username', '$password', '$email', '$activationKey', 'verify')";

if (!mysql_query($sql))

  {

  die('Error: ' . mysql_error());

  }

echo "An email has been sent to $_POST[email] with an activation key. Please check your mail to complete registration.";

##Send activation Email

$to      = $_POST[email];

$subject = " YOURWEBSITE.com Registration";

$message = "Welcome to our website!\r\rYou, or someone using your email address, has completed registration at YOURWEBSITE.com. You can complete registration by clicking the following link:\rhttp://www.YOURWEBSITE.com/verify.php?$activationKey\r\rIf this is an error, ignore this email and you will be removed from our mailing list.\r\rRegards,\ YOURWEBSITE.com Team";

$headers = 'From: noreply@ YOURWEBSITE.com' . "\r\n" .

    'Reply-To: noreply@ YOURWEBSITE.com' . "\r\n" .

    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);

} else {

##User isn't registering, check verify code and change activation code to null, status to activated on success

$queryString = $_SERVER['QUERY_STRING'];

$query = "SELECT * FROM users"; 

$result = mysql_query($query) or die(mysql_error());

  while($row = mysql_fetch_array($result)){

    if ($queryString == $row["activationkey"]){

       echo "Congratulations!" . $row["username"] . " is now the proud new owner of an YOURWEBSITE.com account.";

       $sql="UPDATE users SET activationkey = '', status='activated' WHERE (id = $row[id])";

       if (!mysql_query($sql))

  {

        die('Error: ' . mysql_error());

  }

    }

  }

}

?>