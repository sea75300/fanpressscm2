<?php
    /**
     * FanPress CM Toolbox
     * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
     * @copyright 2011-2014
     */
    
    require_once __DIR__.'/inc/init.php';	
    require_once __DIR__.'/inc/central.php';

    if(!defined("FPCACHEFOLDER")) define("FPCACHEFOLDER", $fpcachefolder); 
    if(!defined("FPCACHEEXPIRE")) define("FPCACHEEXPIRE", fpConfig::get('cache_timeout'));
    if(!defined("FPUPLOADFOLDERURL"))   define('FPUPLOADFOLDERURL', FP_ROOT_DIR.ltrim($fpuploadfolder, '/'));
    
    function NewsTitle($divider = "&bull;") {
        if(!isset($GLOBALS["fpDBcon"]) || !is_object($GLOBALS["fpDBcon"])) {
            $GLOBALS["fpDBcon"] = new fpDB();
        }        
        
        $fpNewsPost = new fpNewsPost($GLOBALS["fpDBcon"]);

        require_once (__DIR__."/lang/".fpConfig::get('system_lang')."/commons.php");
        require_once (__DIR__."/lang/".fpConfig::get('system_lang')."/public.php");

        if(fpConfig::get('usemode') == "phpinc") {
            if(isset($_GET["nid"])) {			
                $newsid = (int) $_GET["nid"];
                if($fpNewsPost->existNews($newsid)) {				
                    $article = new fpArticle((int) $newsid);                    
                    print $divider." ".htmlspecialchars_decode($article->getTitel());
                }
            }
        }

        unset($divider, $fpNewsPost, $newsid);
    }

    function PageNumber($divider = "&bull; Page") {     
        if(!isset($GLOBALS["fpDBcon"]) || !is_object($GLOBALS["fpDBcon"])) {
            $GLOBALS["fpDBcon"] = new fpDB();
        }        
        
        $fpNewsPost = new fpNewsPost($GLOBALS["fpDBcon"]);

        require_once (__DIR__."/lang/".fpConfig::get('system_lang')."/commons.php");
        require_once (__DIR__."/lang/".fpConfig::get('system_lang')."/public.php");

        if(fpConfig::get('usemode') == "phpinc") {
            if($fpNewsPost->countNewsPosts() > 0) {		
                if(isset($_GET["npid"]) || isset($_GET["apid"])) {
                    $news_show_limit = fpConfig::get('news_show_limit');
                    if(isset($_GET["apid"])) {
                        $news_page_id    = (int) $_GET["apid"];
                        $divider = "&bull; FanPress CM ".fpLanguage::returnLanguageConstant(LANG_ARCHIVE_LINK_DESCRIPTION)." ".$divider;
                    } else {
                        $news_page_id    = (int) $_GET["npid"];
                    }

                    if($news_page_id > 0) {
                        $curpage = ceil($news_page_id / $news_show_limit);
                        $curpage++;

                        print $divider." ".$curpage;
                    }
                }
            }
        }

        unset($divider, $fpNewsPost, $news_page_id, $news_show_limit, $curpage);
    }	

    function LastestNews($news_show_limit = 0) {        
        if(!isset($GLOBALS["fpDBcon"]) || !is_object($GLOBALS["fpDBcon"])) {
            $GLOBALS["fpDBcon"] = new fpDB();
        }        
        
        require_once (__DIR__."/lang/".fpConfig::get('system_lang')."/commons.php");
        require_once (__DIR__."/lang/".fpConfig::get('system_lang')."/public.php");
        
        $fpNewsPost = new fpNewsPost($GLOBALS["fpDBcon"]);          

        if(fpConfig::get('usemode') == "phpinc") {
            $nptemplate = file_get_contents(__DIR__ ."/styles/latest.html", false, NULL, 0,filesize(__DIR__ ."/styles/latest.html"));

            if($news_show_limit == 0) { $news_show_limit = fpConfig::get('news_show_limit'); }

            $fpCache   = new fpCache('fplastestnews');
            $tmpCache  = '';

            if($fpCache->isExpired()) {            
                $dataArray = array(                    
                    'previews'  => 0,
                    'startfrom' => 0,
                    'showlimit' => $news_show_limit

                );                
                $newsRows = $fpNewsPost->getNews("all",$dataArray);

                foreach ($newsRows AS $row) {
                    $tmpCache .= $fpNewsPost->parseNews($row,$nptemplate,fpConfig::get('timedate_mask'),false);
                }

                $fpCache->write($tmpCache,FPCACHEEXPIRE);

                print $tmpCache;
            } else {
                print $fpCache->read();   
            }			
        }

        unset($news_show_limit, $fpCache, $fpNewsPost, $nptemplate, $tmpCache, $dataArray, $newsRows);
    }   
?>