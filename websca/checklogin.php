 <?php
session_start();
#if User needs to login
if(!isset($_SESSION['luser']))
{
#    echo "<script>alert('please login')</script>";
    echo '<meta http-equiv="refresh" content="0; URL=login.php">';
    exit();
}
else
{
    #if users session has expired
    if(time() > $_SESSION['expire'])
    {
        session_destroy();
#        echo "<script>alert('Session Expired')</script>";
        echo '<meta http-equiv="refresh" content="0; URL=login.php">';
        exit();
    }
}
#End of login check. Place code under here:
?>
