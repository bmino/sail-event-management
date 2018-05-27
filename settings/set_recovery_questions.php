<?php
include('../include/require_login.php');
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$team_name = $_SESSION['team_name'];
$account_ID = $_SESSION['ID']; 
?>
<html>
<head>
<title>Set Recovery Questions</title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<style type="text/css">
input[type='text'] {width:100%;}
</style>
</head>
<body>
<?php
//CONNECTS TO MYSQL
include('../include/connect_mysql.php');

//
////Processes Submitted Form
//

if(isset($_POST['submitted_set_recovery']))
{
	//Gets Form Data
	$Q1 = mysql_real_escape_string($_POST['recovery_question_1']);
	$Q2 = mysql_real_escape_string($_POST['recovery_question_2']);
	$A1 = mysql_real_escape_string($_POST['recovery_answer_1']);
	$A2 = mysql_real_escape_string($_POST['recovery_answer_2']);
	
	//Gets Current Recovery Question/Answers
	$previous_query = mysql_query("SELECT * FROM Accounts WHERE ID='$account_ID' and Team='$team_name'") or die(mysql_error());
	$previous_result = mysql_fetch_array($previous_query,MYSQL_ASSOC);
	$previous_Q1 = mysql_real_escape_string($previous_result['RecoveryQ1']);
	$previous_Q2 = mysql_real_escape_string($previous_result['RecoveryQ2']);
	$previous_A1 = mysql_real_escape_string($previous_result['RecoveryA1']);
	$previous_A2 = mysql_real_escape_string($previous_result['RecoveryA2']);
	
	//Updates If Recovery Q/A Has Changed
	if($previous_Q1 != $Q1 and $Q1 != '' and $Q1 != NULL)
	mysql_query("UPDATE Accounts SET RecoveryQ1='$Q1' WHERE ID='$account_ID' AND Team='$team_name'") or die(mysql_error());
	if($previous_Q2 != $Q2 and $Q2 != '' and $Q2 != NULL)
	mysql_query("UPDATE Accounts SET RecoveryQ2='$Q2' WHERE ID='$account_ID' AND Team='$team_name'") or die(mysql_error());
	if($previous_A1 != $A1 and $A1 != '' and $A1 != NULL)
	mysql_query("UPDATE Accounts SET RecoveryA1='$A1' WHERE ID='$account_ID' AND Team='$team_name'") or die(mysql_error());
	if($previous_A2 != $A2 and $A2 != '' and $A2 != NULL)
	mysql_query("UPDATE Accounts SET RecoveryA2='$A2' WHERE ID='$account_ID' AND Team='$team_name'") or die(mysql_error());
}


//
////Begin Form
//


//Include Nav Bar If From Menu
if($_SESSION['admin'] == 1)
{
	//Nav Bar
	include('../include/nav_bar.php');
}

$questions_query = mysql_query("SELECT * FROM Accounts WHERE ID='$account_ID' AND Team='$team_name'") or die(mysql_error());
$questions_result = mysql_fetch_array($questions_query,MYSQL_ASSOC);
$set_question1 = $questions_result['RecoveryQ1'];
$set_question2 = $questions_result['RecoveryQ2'];
$set_answer1 = $questions_result['RecoveryA1'];
$set_answer2 = $questions_result['RecoveryA2'];
?>

<form name="Set_Recovery_Questions" action="set_recovery_questions.php" method="post">
<table width="100%" align="center" border="1" class="InputsBox">
<tr>
    <th>Recovery Questions</th>
</tr>
<tr>
    <td>
    <table width="100%" height="100%" border="0">
  	<tr>
    	<td>Question 1:</td>
    	<td><input type="text" name="recovery_question_1" value="<?php echo $set_question1;?>" autofocus></td>
  	</tr>
  	<tr>
    	<td>Answer:</td>
    	<td><input type="text" name="recovery_answer_1" value="<?php echo $set_answer1;?>"></td>
  	</tr>
    </table>
    </td>
</tr>
<tr>
    <td>
    <table width="100%" height="100%" border="0">
  	<tr>
    	<td>Question 2:</td>
    	<td><input type="text" name="recovery_question_2" value="<?php echo $set_question2;?>"></td>
 	</tr>
  	<tr>
    	<td>Answer:</td>
    	<td><input type="text" name="recovery_answer_2" value="<?php echo $set_answer2;?>"></td>
 	</tr>
	</table>
	</td>
</tr>
<tr height="60pt">
	<td>
		<a href="account_settings.php"><input type="button" value="Return To Settings" style="width:50%;height:60pt;font-size:30pt;"></a><input type="submit" value="Update" style="width:50%;height:60pt;font-size:30pt;" name="submitted_set_recovery">
    </td>
</tr>
</table>
</form>
</body>
</html>