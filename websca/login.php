 <?php
#Setup Password
$username = "scdiag";
$password = "linux";

#number of minutes in a session
$sessionLength = 30;


#Code based on Rafee's post on http://stackoverflow.com/questions/520237/how-do-i-expire-a-php-session-after-30-minutes
#start new session
session_start();
?>

<html>
  <body bgcolor="#999999"> 
    <div align="center">
    <H1 ALIGN="center">Supportconfig Analysis Appliance <br> Please login</H1>
    <P ALIGN="center"><A HREF="docs.html" TARGET="docs">Documentation</A></P>
      <form name="login" method="post" style="margin: 0 auto;">
	<table>
	  <tr><td>Username </td><td><input type="text" name="text1" value="<?php echo $username; ?>"></td></tr>
	  <tr><td>Password</td><td><input type="password" name="pwd"></td></tr>
	  <tr><td><input type="submit" value="SignIn" name="submit1"> </td></tr>
	</table>
      </form>
    </div>
  </body>
</html>

<?php
if($_POST['submit1'])
{
  $user = $_POST['text1'];
  $pass = $_POST['pwd'];
  if($username == $user && $password == $pass)
    {
      $_SESSION['luser'] = $user;
      $_SESSION['start'] = time(); // taking now logged in time
      $_SESSION['expire'] = $_SESSION['start'] + ($sessionLength * 60) ; // ending a session in 30     minutes from the starting time
      echo "logged in";
      echo '<meta http-equiv="refresh" content="0; URL=index.php">';
    }
else
  {
    echo $error;
    echo "<br>";
    echo "Please enter Username or Password again !";
  }
}
?>
