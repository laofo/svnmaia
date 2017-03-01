<?php
include_once('../../../config.inc');
include_once('../include/dbconnect.php');

	//migrate info table
	$query="
CREATE TABLE IF NOT EXISTS `dbmigrate_info` (
  `taskid` int(11) UNIQUE,
  PRIMARY KEY  (`taskid`)
) ENGINE=MyISAM $encode ;";
	mysql_query($query);
$mydir=getcwd() . "/migrate";
$dh = opendir( $mydir );
$file_arr=array();
if( $dh ) 
{
	while(false != ($file = readdir( $dh ) ) )
	{
		if(is_file($mydir .'/'. $file))
		{
			$file_arr[]=$file;			
		}
	}
}
sort($file_arr);
foreach($file_arr as $file)
{
	list($taskid,$ot)=explode('_',$file,2);
	if(! is_numeric($taskid))
	{
		echo "warning: $file is note a task!<br>";
		continue;
	}
	if($_GET['ver'] != $taskid)
	{
		$query="select taskid from dbmigrate_info where taskid=$taskid";	
		$result=mysql_query($query);
		if($result and($row= mysql_fetch_array($result, MYSQL_BOTH))) {
			if(!empty($row['taskid'])){
			 	echo "$file skiped! It exists in executed record.<br>";
				continue; //skip the task executed!
			}
		}
	}
	include("./migrate/$file");
	if(empty($mysql_err))
	{
		$sql="insert into dbmigrate_info set taskid=$taskid";
		mysql_query($sql);
	}
}
