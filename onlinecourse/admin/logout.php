<?php
session_start();
$_SESSION['alogin']=="";
include("includes/config.php");
$ret = mysqli_query($con,"update admin set logon=0");
session_unset();
//session_destroy();
$_SESSION['errmsg']="You have successfully logout";
?>
<script language="javascript">
document.location="index.php";
</script>
