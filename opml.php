<?php
require_once('dbconnect.php');
header("Content-type: application/xml");

print <<<END
<?xml version="1.0" encoding="UTF-8"?>
<!-- OPML generated by Links-->
<opml version="1.1">
<head>
<title>My Links</title>
<!--
<dateCreated/>
<dateModified/>
<ownerName/>
<ownerEmail/>
<ownerId/>
-->
</head>
<body>

END;

$feedid = filter_var( $_GET['feedid'], FILTER_SANITIZE_EMAIL );

$sql="select link, DATE_FORMAT(added,'%a %d %b %Y %T') as added from Links, LinkUser, Users where linkid = Links.id and Users.id = LinkUser.userid and usersha1='$feedid' order by indexval";
$result = mysql_query($sql, $conn) or die(mysql_error());

$itemData = array();
while ($lineArray = mysql_fetch_array($result)) {
	$item = array();
	$item["text"] = urldecode($lineArray['link']);
	$item["title"] = urldecode($lineArray['link']);
	$item["description"] = "";
	$item["type"] = "rss";
	$item["version"] = "RSS";
	$item["htmlUrl"] = urldecode($lineArray['link']);
	$item["xmlUrl"] = urldecode($lineArray['link']);

	$itemData[] = $item;
}

foreach( $itemData as $item ){
	print("<outline ");
	foreach( $item as $key=>$value) {
		print("$key=\"$value\" ");
	}
	print("/>\n");
}
?>
</body>
</opml>
