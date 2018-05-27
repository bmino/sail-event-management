<?php
include('include/require_admin.php');
?>
<html>
<head>
<title>Edit Swimmer</title>
<link rel="stylesheet" type="text/css" href="settings/stylesheet.css">
<style type="text/css">
input [type='text'] {width:95%;}
</style>
</head>
<body>
<?php
//CONNECTS TO MYSQL
include('include/connect_mysql.php');

//
////Should Process Form Yes/No
//

if(isset($_POST['from_edit_swimmer']))
{
	//Gets Data From Form
	$FirstName = mysql_real_escape_string($_POST['FirstName']);
	$LastName = mysql_real_escape_string($_POST['LastName']);
	$Gender = mysql_real_escape_string($_POST['Gender']);
	$Age = mysql_real_escape_string($_POST['Age']);
	$ID = mysql_real_escape_string($_POST['ID']);
	
	mysql_query("UPDATE Events SET FirstName='$FirstName', LastName='$LastName', Age='$Age', Gender='$Gender' WHERE ID='$ID'") or die(mysql_error());
	?>
	
	<table border='1' width='700pt' height='350pt' style='top:50%;left:50%;margin-left:-350;margin-top:-175;position:absolute;' class="NotificationBox">
		<tr>
			<th>Swimmer Updated!</th>
		</tr>
		<tr>
			<td>
				First: <?php echo $FirstName;?><br />
                Last: <?php echo $LastName;?><br />
				Age: <?php echo $Age;?><br />
				Gender: <?php echo $Gender;?><br />
            </td>
        <tr height="50pt">
        	<td><form action="masterlist.php" method="post"><input type='submit' value='Return to Events' style='width:100%;height:50pt;font-size:22pt;'></form>
			</td>
		</tr>
	</table>
	<?php
	exit;
}

//Nav Bar
include('include/nav_bar.php');

//
////Begins Normal Form
//

$ID = $_POST['edit_request'];

$query = mysql_query("SELECT * FROM Events WHERE ID='$ID'") or die(mysql_error());
$swimmer_info = mysql_fetch_array($query,MYSQL_ASSOC);
$FirstName = $swimmer_info['FirstName'];
$LastName = $swimmer_info['LastName'];
if($swimmer_info['Gender'] == 'M') $gender_male = "checked='checked'";
elseif($swimmer_info['Gender'] == 'F') $gender_female = "checked='checked'";
else {$gender_male = ''; $gender_female = '';}
$Age = $swimmer_info['Age'];
?>

<form action="editswimmer.php" method="post">
<table width="800pt" height="380pt" align='center' border="1" class="InputsBox">
<tr>
    <th>Edit Swimmer</th>
</tr>
<tr>
    <td>
    <table width="100%" height="100%" border="0">
  	<tr height='60pt'>
    	<td width='400pt'>First Name:</td>
    	<td><input type="text" name="FirstName" value="<?php echo $FirstName;?>"></td>
  	</tr>
  	<tr height='60pt'>
    	<td width='400pt'>Last Name:</td>
    	<td><input type="text" name="LastName" value="<?php echo $LastName;?>"></td>
  	</tr>
  	<tr height='60pt'>
    	<td width='400pt'>Gender:</td>
    	<td>
          <label><input type="radio" name="Gender" value="M" width="50pt" style="vertical-align:middle" <?php echo $gender_male; ?>>Male</label>
          <label><input type="radio" name="Gender" value="F" width="50pt" style="vertical-align:middle" <?php echo $gender_female; ?>>Female</label></td>
		</td>
  	</tr>
  	<tr height='60pt'>
    	<td width='400pt'>Age:</td>
    	<td><input type="number" name="Age" value="<?php echo $Age;?>"></td>
  	</tr>
	</table>
	</td>
</tr>
<tr height="62pt">
	<td>
    <input type="hidden" value="<?php echo $ID; ?>" name="ID">
    <input type="submit" value="Update" style="width:100%;height:60pt;font-size:30pt;" name="from_edit_swimmer"></td>
</tr>
</table>
</form>
</body>
</html>