<?php
session_start();
include('../include/charset.php');
?>
<?php
include('../../../config.inc');
include('../include/basefunction.php');
if(file_exists('../config/config.php'))
{
	include('../config/config.php');
}else
{
	echo "window.alert('请先进行系统设置!')";
	echo" <script>setTimeout('document.location.href=\"../setup/setup.php\"',0)</script>";  	
	exit;
}
include('../include/dbconnect.php');
$usr= mysql_real_escape_string($_POST['username'],$mlink);
$passwd= $_POST['pswd'];
if($usr =="")
{
	echo " <script>window.alert(\"用户名不能为空，请输入!\")</script>";
  echo " <a href='javascript:history.back()'>点击这里返回</a>";
  echo "<script>history.go(-1);</script>";
  exit;
}

$user_id=0;
//SQL查询语句;
mysql_query("SET NAMES utf8");

//查看错误次数
$query="select wrongs,lastErrorTime from svnauth_user where user_name='$usr'";
$result =mysql_query($query);
if($result)$totalnum=mysql_num_rows($result);
while (($result)and($row= mysql_fetch_array($result, MYSQL_BOTH))) {
	$wrongs=$row['wrongs'];
	$lasterror=strftime("%Y%m%d%H%M%S",strtotime($row['lastErrorTime']));
	$now=strftime("%Y%m%d%H%M%S");
	if($wrongs > 3)
	{
		$k=$wrongs * 5;
		$should=$lasterror + $k;
		if($now < $should)
		{
			echo "密码输错了 $wrongs 次，请等待 $k 秒后再试";
			exit;
		}
	}
}
		


//查询

$query = "SELECT supervisor,user_id,password FROM svnauth_user WHERE user_name ='$usr';"; 

// 执行查询
$result =mysql_query($query);
if($result)$totalnum=mysql_num_rows($result);
if($totalnum>0){
	while (($result)and($row= mysql_fetch_array($result, MYSQL_BOTH))) {
	  $fpasswd=$row['password'];
	  if(verifyPasswd($passwd,$fpasswd))
	  {	  
     	  	$_SESSION['username'] =$usr;
	  	$token=trim($row['supervisor']);
	  	$_SESSION['role']=(empty($token))?'user':'admin';
	  	$user_id=$row['user_id'];
	  	$_SESSION['uid']=$row['user_id'];
		  $query2="update svnauth_user set wrongs=0,lastErrorTime=NOW() where user_name='$usr';";
		  mysql_query($query2);
	 	echo "欢迎您回来！点击返回<a href='../default.htm'>Maia SVN用户管理首页</a>";
	  }else
	  {
		  $wrongs++;
		  $query2="update svnauth_user set wrongs=$wrongs,lastErrorTime=NOW() where user_name='$usr';";
		  mysql_query($query2);
		echo "<script>window.alert(\"密码错误！！\")</script>"; 
		echo "<script>window.history.back();</script>";
		exit;
	  }
  }

}else{
//用户名、密码错误
  echo "<script>window.alert(\"用户不存在！！\")</script>"; 
  echo "<script>window.history.back();</script>";
  exit;
}		
//用户是否目录管理员
$query="select repository,path from svnauth_dir_admin where svnauth_dir_admin.user_id='$user_id'";
$result =mysql_query($query);
$num=mysql_num_rows($result);
if(($num > 0)and($_SESSION['role']=='user'))
{
  $_SESSION['role']='diradmin';
  while (($result)and($row= mysql_fetch_array($result, MYSQL_BOTH))) {
	$repos=$row['repository'];
	$path=$row['path'];
	$_SESSION['s_admindir'][]=$repos.$path;
 } 
//  var_dump($_SESSION);
}
echo "<script>history.go(-2);</script>";


?>
