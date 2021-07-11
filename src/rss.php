<?php
require_once('dbconnect.php');
header("Content-type: application/xml");

print <<<END
<?xml version='1.0' encoding='UTF-8'?>
<?xml-stylesheet title='XSL_formatting' type='text/xsl' href='/include/xsl/rss.xsl'?>
<rss version="2.0">
<channel>
<title>Links Feed</title>
<link>http://www.zz9-za.com/~opus/links</link>
<description>Links</description>
<generator>php</generator>
<ttl>30</ttl>
END;

$feedid = filter_var( $_GET['feedid'], FILTER_SANITIZE_EMAIL );

$sql="select link, DATE_FORMAT(added,'%a %d %b %Y %T') as added from Links, LinkUser, Users where linkid = Links.id and Users.id = LinkUser.userid and usersha1='$feedid' order by indexval";
$result = mysql_query($sql, $conn) or die(mysql_error());

$itemData = array();
while ($lineArray = mysql_fetch_array($result)) {
	$item = array();
	$item["title"] = urldecode($lineArray['link']);
	$item["link"] = urldecode($lineArray['link']);
	$item["pubDate"] = $lineArray['added'];
	$itemData[] = $item;
}

foreach( $itemData as $item ){
	print("<item>\n");
	foreach( $item as $key=>$value) {
		print("\t<$key>$value</$key>\n");
	}
	print("</item>\n");
}
?>
</channel>
</rss>
