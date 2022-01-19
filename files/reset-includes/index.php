<?php session_start();

function get_image_thumb($thumb, $query){
$domain = 'http://localhost/satish/reset_demo/';
$timthumb = 'thumbgen.php';
if(file_exists('uploads/thumbs/'.$query.'___'.$thumb)){
return str_replace('&', '&amp;', $domain.'uploads/thumbs/'.$query.'___'.$thumb);
} else {
$get_file1 = file_get_contents($domain.''.$timthumb.'?src='.$domain.'uploads/images/'.$thumb.'&'.$query);
$new_file1 = fopen('uploads/thumbs/'.$query.'___'.$thumb, "w");
fwrite($new_file1, $get_file1);
fclose($new_file1);
return str_replace('&', '&amp;', $domain.'uploads/thumbs/'.$query.'___'.$thumb);
}

}

if($_POST['uname']!=''){

$_SESSION['testuser'] = htmlentities($_POST['uname']);

}
 ?><!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-language" content="EN">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css" type="text/css" />
<link rel="stylesheet" href="reset-includes/style.css" type="text/css" />
<script type="text/javascript" src="reset-includes/reset-demo.js"></script>
<title>Reset the demo - Live demonstration script</title>
</head>


<body>
<div class="ctr">

<?php
$reset_db_name = "reset";
$reset_db_user = "root";
$reset_db_password = "";
$reset_db_host = "localhost";

$con = new mysqli($reset_db_host, $reset_db_user, $reset_db_password, $reset_db_name);

if($_FILES['photo']['name']!=''){
$path_parts = pathinfo($_FILES['photo']['name']);
$ext = strtolower($path_parts['extension']);
$allowed = array("jpg","png","gif");
	if($_FILES['photo']['size']>2097152){
		$message = "The file ".$filename." is bigger than the allowed limit of 2 MB.";
	} elseif(!in_array( $ext, $allowed)){
		$message = "Only jpg, png and gif files are allowed.";
	} else {
		$target_path = "uploads/images/";
		$filename = time().'-'.basename( $_FILES['photo']['name']);
		$target_path = $target_path .''.$filename; 
		
			if(move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
				$title = htmlentities($_POST['title'], ENT_QUOTES, "UTF-8");
				$desc = htmlentities($_POST['desc'], ENT_QUOTES, "UTF-8");
				mysqli_query($con, "INSERT INTO `photos` (`id`, `url`, `title`, `desc`, `time`, `user`) VALUES (NULL, '$filename', '$title', '$desc', '".time()."', '".$_SESSION['testuser']."')");
			$message = "The file ".$filename." has been uploaded";
			} else {
			$message = "There was an error uploading the file, please try again!";
			}
	}
}

if($message!=''){echo'<div class="error"><b>Notice :</b> '.$message.'</div>';}

?>

<h2 class="title">Information - Demo Reset</h2>
<p>You are watching the demo of "demo reset" script. This scripts resets the whole demo after a certain period of time. All files are deleted and copies back from original backup source from a different folder. MySQL database is made empty and all data is re-created from a phpmyadmin sql dump file defined in settings. All defined cookies and sessions are deleted. Hence demo wil be exactly as it was before.</p>
<p>This particular demo page is created using PHP and MySQL, for simulation of real script. MySQL keeps record of all photos. User can upload photos only after entering his own name which is kept in session. After some time, as shown in the timer at the right bottom of your screen, this demo will reset as it was, no matter what else you upload.</p>
<p>Now try uploading any no of photos, they will get deleted and everything will become as it was in starting of demo. You can hover on photos to see title, description, time and username of who uploaded that particular photo.</p>
<br /><br />
<h2 class="title">Uploaded Photos</h2>
<?php
if($login_message!=''){echo'<div class="error"><b>Notice :</b> '.$login_message.'</div>';}
echo'<div class="clear"></div>';
$data = mysqli_query($con, "SELECT * FROM `photos` ORDER BY `time` DESC");
while($row = mysqli_fetch_assoc($data)){
//print_r($row);
$pic = get_image_thumb($row['url'], 'w=320&h=240');
echo'<div class="myphoto">
<img src="'.$pic.'">
<div class="mytext">
<h3>&nbsp;'.$row['title'].'</h3>
<p>'.$row['desc'].'</p>
<hr />
<i>Uploaded on '.date("l, d-m-Y, h:i:s a", $row['time']).'<br />-by <b>'.$row['user'].'</b></i>
</div>
</div>';
}

?>
<div class="clear"></div>
<br /><br />
<?php

if($_SESSION['testuser']!=''){

?>

<h2 class="title">Welcome <b style="color:magenta;"><?php echo $_SESSION['testuser']; ?></b>, you can upload photo below</h2>
<div class="uploadform">

<form name="xform" enctype="multipart/form-data" action="" method="post">

<label for="photo">Select Photo</label><input type="file" name="photo" />
<p class="help">Must be jpg, gif or png file and must be smaller than 2 MB.</p>

<label for="title">Title</label><input type="text" name="title" />
<p class="help">Can be empty. The title for the photo you selected above.</p>

<label for="desc">Description</label><textarea rows="6" name="desc"></textarea>
<p class="help">Can be empty. The description for the photo you selected above.</p>

<input type="submit" value="Upload Files" />
</form>
</div>

<?php } else { ?>

<h2 class="title">Login to upload photos</h2>
<div class="uploadform">

<form name="xform" enctype="multipart/form-data" action="" method="post">

<label for="uname">Your Name</label><input type="text" name="uname" />
<p class="help">Just enter your name.</p>

<input type="submit" value="Login Now" />
</form>
</div>

<?php } ?>
</div>
</body>
</html>