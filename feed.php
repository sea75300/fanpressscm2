<?php
    /**
     * RSS Feed
     * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
     * @copyright 2011-2014
     *
     */
    require_once __DIR__.'/inc/init.php';
    require_once __DIR__.'/inc/central.php';

    $fpNewsObj    = new fpNewsPost($fpDBcon);

    header("Content-type: text/xml");

    print "<?xml version=\"1.0\" encoding=\"iso-8859-1\" ?>\n";
    print "<rss version=\"2.0\">\n";
    print "	<channel>\n";
    print "		<title>FanPress CM RSS-Feed</title>\n";
    print "		<description>FanPressCM</description>\n";
    print "		<link>http://".$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]."</link>\n";
    print "		<lastBuildDate>".date(DATE_RSS,time())."</lastBuildDate>\n";
    print "		<generator>FanPressCM News System</generator>\n";

    $dataArray = array(
        'previews'  => 0,
        'startfrom' => 0,
        'showlimit' => fpConfig::get('news_show_limit')
    );           

    $rows  = $fpNewsObj->getNews("rss",$dataArray);
    foreach($rows AS $row) {
            print "		<item>\n";
                    print "			<title><![CDATA[".utf8_decode(fpSecurity::Filter7($row->titel))."]]></title>\n";
                    print "			<link>".fpConfig::get('system_url')."?fn=cmt&amp;nid=".$row->id."</link>\n";

                    $newstext = fpSecurity::Filter3($row->content);
                    $test_br1 = strpos($newstext,"<br>");
                    $test_br2 = strpos($newstext,"<br />");	
                    if ($test_br1 === false || $test_br2 === false) {	  
                        $newstext = nl2br($newstext);
                    }
                    $newstext = $fpNewsObj->cleanPost($newstext);			
                    print "			<description><![CDATA[".$newstext."]]></description>\n";
                    print "			<pubDate>".(string) date(DATE_RSS,$row->writtentime)."</pubDate>\n";
                    print "			<author>".strtolower($row->name)."</author>\n";
            print "		</item>\n";
    }
    print "</channel>\n";
    print "</rss>";

    unset($fpDBcon,$fpNewsObj);
?>