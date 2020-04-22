<?php
session_start();
error_reporting(0);

include("includes/config.php");

if(isset($_POST['submit']))
{
    $username=$_POST['username'];
    $password=md5($_POST['password']);
	
	
	
	$sql=mysqli_query($con,"SELECT * FROM admin where logon =1 and username='$username' and password='$password'");
	$num1=mysqli_fetch_array($sql);
	if($num1>0)
	{
	
  $_SESSION['errmsg']="This account is already used ";
	$extra="index.php";
	$host  = $_SERVER['HTTP_HOST'];
	$uri  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
	header("location:http://$host$uri/$extra");
	exit();
	
  }
  
	
	//SQL INJECTION PREVENTION
	$stmt = $con->prepare("SELECT * FROM admin WHERE username=? and password=?");
	$stmt->bind_param("ss", $username, $password);
	$stmt->execute();	
	$stmt->store_result();
	$stmt->bind_result($name, $password);
	
	/*//SQL INJECTION VULNERABLE
	$stmt = $con->query("SELECT * FROM admin WHERE username='$username' and password='$password'");*/
	
	$query=mysqli_query($con,"SELECT * FROM admin WHERE username='$username' and password='$password'");
	$num=mysqli_fetch_array($query);
if($stmt->num_rows>0)
{
	if(empty($_SESSION['captcha_code'] ) || strcasecmp($_SESSION['captcha_code'], $_POST['captcha_code']) != 0)
	{  
		$_SESSION['errmsg']="The Validati code does not match!";
		$extra="index.php";
		$host  = $_SERVER['HTTP_HOST'];
		$uri  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
		header("location:http://$host$uri/$extra");
		exit();
	}
$extra="change-password.php";//
$_SESSION['alogin']=$_POST['username'];
$_SESSION['id']=$num['id'];
$host=$_SERVER['HTTP_HOST'];
$ret = mysqli_query($con,"update admin set logon=1");
$uri=rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
header("location:http://$host$uri/$extra");
exit();
}
else
{
$_SESSION['errmsg']="Invalid username or password";
$extra="index.php";
$host  = $_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
header("location:http://$host$uri/$extra");
exit();
}
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Admin Login</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
	
<script type='text/javascript'>
function refreshCaptcha(){
	var img = document.images['captchaimg'];
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}
</script>
</head>
<body>
    <?php include('includes/header.php');?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Please Login To Enter </h4>

                </div>

            </div>
             <span style="color:red;" ><?php echo htmlentities($_SESSION['errmsg']); ?><?php echo htmlentities($_SESSION['errmsg']="");?></span>
            <form name="admin" method="post">
            <div class="row">
                <div class="col-md-6">
                     <label>Enter Username : </label>
                        <input type="text" name="username" class="form-control" required />
                        <label>Enter Password :  </label>
                        <input type="password" name="password" class="form-control" required />
                        <hr />
						
				<table width="400" border="0" align="center" cellpadding="5" cellspacing="1" class="table">
				<?php if(isset($msg)){?>
				<tr>
				  <td colspan="2" align="center" valign="top"><?php echo $msg;?></td>
				</tr>
				<?php } ?>
				<tr>
				  <td align="right" valign="top"> Captcha code:</td>
				  <td><img src="captcha.php?rand=<?php echo rand();?>" id='captchaimg'><br>
					<label for='message'>Enter the code above here :</label>
					<br>
					<input id="captcha_code" name="captcha_code" type="text">
					<br>
					Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh.</td>
				</tr>
				<tr>
				  <td>&nbsp;</td>
				  <td><button type="submit" name="submit" class="btn btn-info"><span class="glyphicon glyphicon-user"></span> &nbsp;Log Me In </button>&nbsp;</td>
				</tr>
				</table>
				</div>
                </form>
				
				

            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.11.1.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
</body>
</html>
