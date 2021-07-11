<?php
if (!$_COOKIE["linksuserid"]) {
    header("Location: index.html");
    exit;
}
error_reporting(E_ALL);
$userid = $_COOKIE["linksuserid"];
require_once('dbconnect.php');
$extraHeadTags="";

if (isset($_POST["newLink"])) {
	$newLink = filter_input( INPUT_POST, 'newLink', FILTER_SANITIZE_ENCODED );

	# find next index for user
	$sql = "select IFNULL(max(indexval),0)+1 as newindex from LinkUser where userid=\"$userid\"";
	$result = mysql_query( $sql, $conn ) or die( mysql_error() );
	$newIndex = mysql_result( $result, 0, "newindex" );

	# add the link
	$linkID = "";
	$sql = "select * from Links where link=\"$newLink\"";
	$result = mysql_query( $sql, $conn ) or die( mysql_error() );
	
	if (mysql_num_rows( $result ) == 0) {
		$sql = "insert into Links (link, added, addedby) values (\"$newLink\", CURRENT_TIMESTAMP, $userid)";
		$result = mysql_query( $sql, $conn ) or die( mysql_error() );
		$sql = "select * from Links where link=\"$newLink\"";
		$result = mysql_query( $sql, $conn ) or die( mysql_error() );
	}
		
	$linkID = mysql_result( $result, 0, "id" );

	$sql = "insert into LinkUser (linkid, userid, indexval, connected, flags) values ($linkID, $userid, $newIndex, CURRENT_TIMESTAMP, 0)";
	$result = mysql_query( $sql, $conn ) or die( mysql_error() );
	
}


$sql = "select name, isadmin from Users where id='$userid'";
$result = mysql_query($sql, $conn) or die(mysql_error());
$name = mysql_result( $result, 0, "name" );
$isAdmin = mysql_result( $result, 0, "isadmin" );

$editLinkFields = array(
	array("indexval", "text", "Index", 1, 1),
	array("link", "text", "Link Text", 1, 1)
);
$editLinkArray = array();

$sql = "select * from Links, LinkUser where linkid = Links.id and userid='$userid' order by indexval";
$result = mysql_query($sql, $conn) or die(mysql_error());

$editLinkArray[]="<table><tr>";
foreach( $editLinkFields as $field ) {
	$editLinkArray[]="<th>$field[2]</th>";
}
$editLinkArray[]="</tr>";
while ($lineArray = mysql_fetch_array($result)) {
	$editLinkArray[]="<tr>";
	foreach( $editLinkFields as $field ) {
		$editLinkArray[]="<td>".urldecode($lineArray[$field[0]])."</td>";
	}
	$editLinkArray[]="</tr>";
}
$editLinkArray[] = '<form name="new" action="main.php" method="post">
<tr><td/><td><input type="text" name="newLink"></td>
<td><input type="submit" name="submit" value="Add Link"></td></tr>
</form>
';
$editLinkArray[]="</table>";


?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Link Share</title>
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/currency.css">
<script src="js/bootstrap.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?=$extraHeadTags?>
</head>
<body>
Hello <?=$name?>.<br/>
<a href="rss/<?=sha1($userid)?>">RSS</a><br/>
<a href="opml/<?=sha1($userid)?>">opml</a><br/>
<a href="xbel/<?=sha1($userid)?>">xbel</a><br/>
<?php
print( implode("\n", $editLinkArray) );
?>

</body>
</html>
