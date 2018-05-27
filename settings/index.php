<?php
include('../include/require_admin.php');
?>
<html>
<head>
<title>Settings</title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<style type="text/css">
select {width:auto;}
td {height:55pt;}
option {text-align:center;}
</style>
</head>
<body>
<?php
//CONNECTS TO MYSQL
include('../include/connect_mysql.php');


//
////Should Process Form Yes/No
//

if(isset($_POST['from_settings_index']))
{
	//Gets Information From Form
	$team_name = $_SESSION['team_name'];
	$sortby1 = $_POST['EventsSortBy1'];
	$sortby2 = $_POST['EventsSortBy2'];
	$sortby3 = $_POST['EventsSortBy3'];
	$malecolor  = $_POST['ColorMale'];
	$femalecolor = $_POST['ColorFemale'];
	$eventrowhigh = mysql_real_escape_string(str_replace(' ', '', $_POST['EventsRowHeight']));
	$namedisplay = $_POST['EventsNameDisplay'];
	$fontsize = mysql_real_escape_string($_POST['EventsFontSize']);
	if(isset($_POST['ClearEntries'])) {$ClearEntries = $_POST['ClearEntries'];} else {$ClearEntries = '0';}
	
	
	//Checks If #1 is Set
	if ($sortby1 != "NONE")
	{
		$sortby = $sortby1;
		if ($sortby2 != "NONE")
		{
			$sortby = $sortby . ',' . $sortby2;
			if ($sortby3 != "NONE")
			{
				$sortby = $sortby . ',' . $sortby3;
			}
		}
	}
	else $sortby = "NONE";
	
	//Check if mySQL Has Same Setting
	$settings_query = mysql_query("SELECT * FROM Settings WHERE Team='$team_name'") or die(mysql_error());
	$check_settings = mysql_fetch_array($settings_query,MYSQL_ASSOC);
	
	//Updates Settings
	if ($sortby != "NONE" and $sortby != NULL and $sortby != $check_settings['EventsSortBy'])
	{
		mysql_query("UPDATE Settings SET EventsSortBy = '$sortby' WHERE Team='$team_name'") or die(mysql_error());
		//echo "Updated: '" . $sortby . "'\n";
	}
	
	if ($malecolor != NULL and $malecolor != $check_settings['ColorMale'])
	{
		mysql_query("UPDATE Settings SET ColorMale = '$malecolor' WHERE Team='$team_name'") or die(mysql_error());
		//echo "Updated: " . $malecolor . "\n";
	}
	
	if ($femalecolor != NULL and $femalecolor != $check_settings['ColorFemale'])
	{
		mysql_query("UPDATE Settings SET ColorFemale = '$femalecolor' WHERE Team='$team_name'") or die(mysql_error());
		//echo "Updated: " . $femalecolor . "\n";
	}
	
	if ($eventrowhigh != "" and $eventrowhigh != NULL and $eventrowhigh != $check_settings['EventsRowHeight'])
	{
		mysql_query("UPDATE Settings SET EventsRowHeight = '$eventrowhigh' WHERE Team='$team_name'") or die(mysql_error());
		//echo "Updated: " . $eventrowhigh . "\n";
	}
	
	if ($namedisplay != NULL and $namedisplay != $check_settings['EventsNameDisplay'])
	{
		mysql_query("UPDATE Settings SET EventsNameDisplay = '$namedisplay' WHERE Team='$team_name'") or die(mysql_error());
		//echo "Updated: " . $namedisplay . "\n";
	}
	
	if ($fontsize != "NONE" and $fontsize != NULL and $fontsize != $check_settings['EventsFontSize'])
	{
		mysql_query("UPDATE Settings SET EventsFontSize = '$fontsize' WHERE Team='$team_name'") or die(mysql_error());
		//echo "Updated: " . $fontsize . "\n";
	}
	
	
	if ($_POST['UpdateAge'] != '+0')
	{
		if ($_POST['UpdateAge'] == '+1') {$age_change = 1;}
		if ($_POST['UpdateAge'] == '+2') {$age_change = 2;}
		if ($_POST['UpdateAge'] == '-1') {$age_change = -1;}
		if ($_POST['UpdateAge'] == '-2') {$age_change = -2;}
		mysql_query("UPDATE Events SET Age = Age + $age_change WHERE Team='$team_name'") or die(mysql_error());
		//echo "Ages updated by ".$_POST['UpdateAge']."!\n";
	}

	if ($ClearEntries == '1')
	{
		mysql_query("UPDATE Events SET ShortFree='0',IM='0',Breast='0',LongFree='0',Back='0',Fly='0' WHERE Team='$team_name'") or die(mysql_error());
		//echo "Cleared event entries!\n";
	}
	echo "<script type='text/javascript'>alert('Settings successfully updated!');</script>";
}


//
////Begin Normal Form
//

//Nav Bar
include('../include/nav_bar.php');

$team_name = $_SESSION['team_name'];
$result = mysql_query("SELECT * FROM Settings WHERE Team='$team_name'") or die(mysql_error());
$query = mysql_fetch_array($result,MYSQL_ASSOC);
$CurrentRowHeight = $query['EventsRowHeight'];
$CurrentFontSize = $query['EventsFontSize'];

//Logic For Sorting
for ($i = 1; $i <= 3; $i++)
{
	${'sort'.$i.'_select_NONE'} = '';
	${'sort'.$i.'_select_Age'} = '';
	${'sort'.$i.'_select_Gender'} = '';
	${'sort'.$i.'_select_FirstName'} = '';
	${'sort'.$i.'_select_LastName'} = '';
}
$selector_picked = FALSE;
$current_sortby_options = array('NONE','Age','Gender','FirstName','LastName');
$current_sortby = explode(',',$query['EventsSortBy']);
for ($i = 1; $i <= 3; $i++)
{
	foreach ($current_sortby_options as $answer)
	{
		if (isset($current_sortby[$i-1]))
		{
			if ($current_sortby[$i-1] == $answer)
			{
				${'sort'.$i.'_select_'.$answer} = "selected='selected'";
				$selector_picked = TRUE;
				break;
			}
		}
	}
	if ($selector_picked == FALSE) {${'sort'.$i.'_select_NONE'} = "selected='selected'";}
}


//Logic For Current Name Display
$CurrentDisplay = $query['EventsNameDisplay'];
$selectFL='';
$selectLF='';
$selectFLA='';
$selectLFA='';
$selectNONE='';
if($CurrentDisplay == 'First,Last') {$selectFL = "selected = 'selected'";}
elseif($CurrentDisplay == 'Last,First') {$selectLF = "selected = 'selected'";}
elseif($CurrentDisplay == 'First,Last (Age)') {$selectFLA = "selected = 'selected'";}
elseif($CurrentDisplay == 'Last,First (Age)') {$selectLFA = "selected = 'selected'";}
else $selectFL = "selected = 'selected'";

//Logic For Current Male Color
$CurrentMaleColor = $query['ColorMale'];
$select_male_FFF='';
$select_male_0FF='';
$select_male_0CF='';
$select_male_09F='';
$select_male_06F='';
$select_male_NONE='';
if($CurrentMaleColor == '#FFFFFF') {$select_male_FFF = "selected = 'selected'";}
elseif($CurrentMaleColor == '#00FFFF') {$select_male_0FF = "selected = 'selected'";}
elseif($CurrentMaleColor == '#00CCFF') {$select_male_0CF = "selected = 'selected'";}
elseif($CurrentMaleColor == '#0099FF') {$select_male_09F = "selected = 'selected'";}
elseif($CurrentMaleColor == '#0066FF') {$select_male_06F = "selected = 'selected'";}
else $select_male_FFF = "selected = 'selected'";

//Logic For Current Female Color
$CurrentFemaleColor = $query['ColorFemale'];
$select_female_FFF='';
$select_female_FCF='';
$select_female_F9F='';
$select_female_F6F='';
$select_female_F3C='';
$select_female_NONE='';
if($CurrentFemaleColor == '#FFFFFF') {$select_female_FFF = "selected = 'selected'";}
elseif($CurrentFemaleColor == '#FFCCFF') {$select_female_FCF = "selected = 'selected'";}
elseif($CurrentFemaleColor == '#FF99FF') {$select_female_F9F = "selected = 'selected'";}
elseif($CurrentFemaleColor == '#FF66FF') {$select_female_F6F = "selected = 'selected'";}
elseif($CurrentFemaleColor == '#FF33CC') {$select_female_F3C = "selected = 'selected'";}
else $select_female_FFF = "selected = 'selected'";
?>

<form action="index.php" method="post">
<table width="100%" border="1" class="InputsBox">
  <tr>
  	<th>Settings</th>
  </tr>
  <tr>
  	<td>
		<table width="100%" height="100%" border="0">
    	<tr>
    		<td align="left" width="40%">Swimmer Sorting</td>
   	    	<td align="right" width="40%"><select name="EventsSortBy1">
            	<option value="NONE" <?php echo $sort1_select_NONE; ?>>Sort 1st</option>
                <option value="Age" <?php echo $sort1_select_Age; ?>>Age</option>
                <option value="Gender" <?php echo $sort1_select_Gender; ?>>Gender</option>
                <option value="FirstName" <?php echo $sort1_select_FirstName; ?>>First Name</option>
                <option value="LastName" <?php echo $sort1_select_LastName; ?>>Last Name</option>
                </select><br/>
                <select name="EventsSortBy2">
            	<option value="NONE"  <?php echo $sort2_select_NONE; ?>>Sort 2nd</option>
                <option value="Age" <?php echo $sort2_select_Age; ?>>Age</option>
                <option value="Gender" <?php echo $sort2_select_Gender; ?>>Gender</option>
                <option value="FirstName" <?php echo $sort2_select_FirstName; ?>>First Name</option>
                <option value="LastName" <?php echo $sort2_select_LastName; ?>>Last Name</option>
                </select><br>
                <select name="EventsSortBy3">
            	<option value="NONE" <?php echo $sort3_select_NONE; ?>>Sort 3rd</option>
                <option value="Age" <?php echo $sort3_select_Age; ?>>Age</option>
                <option value="Gender" <?php echo $sort3_select_Gender; ?>>Gender</option>
                <option value="FirstName" <?php echo $sort3_select_FirstName; ?>>First Name</option>
                <option value="LastName" <?php echo $sort3_select_LastName; ?>>Last Name</option>
                </select>
            </td>
   		</tr>
   		</table>
    </td>
  </tr>
  <tr>
  	<td>
		<table width="100%" height="100%" border="0">
    	<tr>
    		<td align="left" width="60%">Swimmer Row Height</td>
   	    	<td align="right" width="40%"><input type="number" name="EventsRowHeight" value="<?php echo $CurrentRowHeight;?>" style="width:30%;">
            </td>
   		</tr>
   		</table>
    </td>
  </tr>
  <tr>
  	<td>
		<table width="100%" height="100%" border="0">
    	<tr>
    		<td align="left" width="60%">Swimmer Font</td>
   	    	<td align="right" width="40%"><input type="number" name="EventsFontSize" value="<?php echo $CurrentFontSize;?>" style="width:30%;"></td>
   		</tr>
   		</table>
    </td>
  </tr>
  <tr>
  	<td>
		<table width="100%" height="100%" border="0">
    	<tr>
    		<td align="left" width="60%">Event Name Display</td>
   	    	<td align="right" width="40%"><select name="EventsNameDisplay">
                <option value="First,Last" <?php echo $selectFL;?>>First,Last</option>
                <option value="First,Last (Age)" <?php echo $selectFLA;?>>First,Last (Age)</option>
                <option value="Last,First" <?php echo $selectLF;?>>Last,First</option>
                <option value="Last,First (Age)" <?php echo $selectLFA;?>>Last,First (Age)</option>
                </select></td>
   		</tr>
   		</table>
    </td>
  </tr>
  <tr>
    <td>
    <table width="100%" height="100%" border="0">
    <tr>
    	<td align="left" width="60%">Male Coloring</td>
        <td align="right" width="40%"><select name="ColorMale">
                <option value="#FFFFFF" style="background-color:#FFFFFF;" <?php echo $select_male_FFF;?>>White</option>
                <option value="#00FFFF" style="background-color:#00FFFF;" <?php echo $select_male_0FF;?>>Lighter Blue</option>
                <option value="#00CCFF" style="background-color:#00CCFF;" <?php echo $select_male_0CF;?>>Light Blue</option>
                <option value="#0099FF" style="background-color:#0099FF;" <?php echo $select_male_09F;?>>Blue</option>
                <option value="#0066FF" style="background-color:#0066FF;" <?php echo $select_male_06F;?>>Dark Blue</option>
                </select></td>
    </tr>
    </table>
    </td>
  </tr>
  <tr>
  	<td>
    <table width="100%" height="100%" border="0">
    <tr>
    	<td align="left" width="60%">Female Coloring</td>
        <td align="right" width="40%"><select name="ColorFemale">
                <option value="#FFFFFF" style="background-color:#FFFFFF;" <?php echo $select_female_FFF;?>>White</option>
                <option value="#FFCCFF" style="background-color:#FFCCFF;" <?php echo $select_female_FCF;?>>Lighter Pink</option>
                <option value="#FF99FF" style="background-color:#FF99FF;" <?php echo $select_female_F9F;?>>Light Pink</option>
                <option value="#FF66FF" style="background-color:#FF66FF;" <?php echo $select_female_F6F;?>>Pink</option>
                <option value="#FF33CC" style="background-color:#FF33CC;" <?php echo $select_female_F3C;?>>Dark Pink</option>
                </select></td>
    </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td>      
        <table width="100%" height="100%" border="0">
    	<tr>
    		<td align="left" width="60%">Update Ages</td>
   	    	<td align="right" width="40%"><select name="UpdateAge" width="30%">
            	<option value="+2">+2</option>
                <option value="+1">+1</option>
                <option value="+0" selected="selected">+0</option>
                <option value="-1">-1</option>
                <option value="-2">-2</option>
            </select></td>
   		</tr>
   		</table>
    </td>
  </tr>
  <tr>
  	<td>
		<table width="100%" height="100%" border="0">
    	<tr>
    		<td align="left" width="60%">Parent Access</td>
   	    	<td align="right" width="40%"><a href="seal_settings.php"><input type="button" value="View" style="height:45pt;width:auto;min-height:auto;font-size:28pt;"></a></td>
   		</tr>
        </table>
   </td>
  </tr>
  <tr>
    <td>      
        <table width="100%" height="100%" border="0">
    	<tr>
    		<td align="left" width="60%">Clear All Entries</td>
   	    	<td align="right" width="40%"><input type="checkbox" name="ClearEntries" value="1" style="height:50px;width:30%;"></td>
   		</tr>
   		</table>
    </td>
  </tr>
  <tr>
  		<td><input type="submit" value="Update" style="height:100%;width:100%;font-size:35pt;" name="from_settings_index"></td>
  </tr>
</table>
</form>
</body>
</html>