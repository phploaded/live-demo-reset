<?php session_start();
error_reporting(0);
/* =========================== SETTINGS START ============================ */

$timeout = 120; /* in seconds */
$backup_folder_name = 'backup'; /* this is the backup of your original files, which gets replaced every time the demo expires */
$reset_db_flag = "no"; /* if your demo does not use mysql db, use "no" instead of "yes" */
$mysql_file = ''; /* applies when $reset_db_flag = "yes"; PHPMYADMIN dump sql file KEPT IN RESET INCLUDES FOLDER to be executed after clearing database */
$delete_cookies = 1; /* deleted all cookies. 1 for yes, 0 for no */
$destroy_session = 1;  /* unsets the $_SESSION variable, killing whole session. 1 for yes, 0 for no */

/* MySQL settings APPLIES ONLY WHEN USING A SQL FILE */
$reset_db_name = "wordpress"; /* MySQL database name */
$reset_db_user = "root"; /* MySQL database user */
$reset_db_password = ""; /* MySQL database password */
$reset_db_host = "localhost"; /* MySQL database host */

/* =========================== SETTINGS END ============================ */


if($reset_db_flag=='yes'){
$con = new mysqli($reset_db_host, $reset_db_user, $reset_db_password, $reset_db_name);

if( !$con ){ 
die("connection object not created: ".mysqli_error($con));
}

if( mysqli_connect_errno()){
die("Connect failed: ".mysqli_connect_errno()." : ". mysqli_connect_error());
}
}

function restore_reset_data($source, $dest){
    if(is_dir($source)) {
        $dir_handle=opendir($source);
        while($file=readdir($dir_handle)){
            if($file!="." && $file!=".."){
                if(is_dir($source."/".$file)){
                    if(!is_dir($dest."/".$file)){
                        mkdir($dest."/".$file);
                    }
                    restore_reset_data($source."/".$file, $dest."/".$file);
                } else {
                    copy($source."/".$file, $dest."/".$file);
                }
            }
        }
        closedir($dir_handle);
    } else {
        copy($source, $dest);
    }
}

function recursiveRemoveDirectory($directory){
	
    foreach(glob("{$directory}/*") as $file)
    {
		if($file!=$directory.'/reset-includes'){
			if(is_dir($file)) { 
				recursiveRemoveDirectory($file);
			} else {
				unlink($file);
			}
		}
    }
	if($directory!=getcwd()){
    rmdir($directory);
	}
}




function create_reset_config_file(){

global $backup_folder_name;
global $mysql_file;
global $reset_db_flag;
global $reset_db_name;
global $reset_db_user;
global $reset_db_password;
global $reset_db_host;
global $delete_cookies;
global $destroy_session;

/* delete the demo files and restore from backup folder */
chdir('../');
$parent_folder = getcwd();
recursiveRemoveDirectory(getcwd());
chdir('reset-includes');
restore_reset_data($backup_folder_name, '../');


if($reset_db_flag=='yes'){
if(file_exists($mysql_file)){
/* Empty the database and restore database from sql file */
$con = new mysqli($reset_db_host, $reset_db_user, $reset_db_password, $reset_db_name);
$sqldata = mysqli_query($con, "SELECT table_name FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".$reset_db_name."'");
while($row = mysqli_fetch_assoc($sqldata)){
mysqli_query($con, "DROP TABLE `".$row['table_name']."`");
}

$commands = file_get_contents($mysql_file);   
mysqli_multi_query($con, $commands);
mysqli_close($con);
} else {
echo "Physical MySQL file(DUMP) was not found inside reset-includes folder.";
}
}


/* Recreate settings.json file with new expiry */
$data['created'] = time();
$newdata = json_encode($data);
file_put_contents('settings.json', $newdata);

/* destroy the PHP sessions */
if($destroy_session==1){
session_destroy();
}

/* Kill all cookies */
	if($delete_cookies==1){
		if (isset($_SERVER['HTTP_COOKIE'])) {
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach($cookies as $cookie) {
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '', time()-1000);
				setcookie($name, '', time()-1000, '/');
			}
		}
	}

}


$method = $_GET['method'];

if($method==''){
echo'0';
}

if($method=='check_time'){

$xdata = json_decode(file_get_contents('settings.json'));
$time_left =$timeout - (time() - $xdata->created); /* timeout - elapsed */
if($time_left<1){
/* delete and recreate the config file */
create_reset_config_file();
$time_left = $timeout;
}


echo $time_left;
}

?>