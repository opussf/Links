<?php
# http://www.xml.com/pub/a/2005/03/02/restful.html
require_once('dbconnect.php');
header("Content-type: application/xml");

print <<<END
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xbel PUBLIC 
       "+//IDN python.org//DTD XML Bookmark Exchange
        Language 1.0//EN//XML" 
       "http://www.python.org/topics/xml/dtds/xbel-1.0.dtd">
<xbel version="1.0">
<folder>
<title>My Links</title>

END;

$feedid = filter_var( $_GET['feedid'], FILTER_SANITIZE_EMAIL );

$sql="select link, DATE_FORMAT(added,'%a %d %b %Y %T') as added from Links, LinkUser, Users where linkid = Links.id and Users.id = LinkUser.userid and usersha1='$feedid' order by indexval";
$result = mysql_query($sql, $conn) or die(mysql_error());

$itemData = array();
while ($lineArray = mysql_fetch_array($result)) {
	$item = array();
	$item["href"] = urldecode($lineArray['link']);
	$item["title"] = urldecode($lineArray['link']);
	$item["desc"] = "";

	$itemData[] = $item;
}

foreach( $itemData as $item ){
	print("<bookmark href=\"".$item["href"]."\">");
	foreach( $item as $key=>$value) {
		if ($key != "href") {
			print("<$key>$value</$key>\n");
		}
	}
	print("</bookmark>\n");
}
?>
</folder>
</xbel>
