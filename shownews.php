<?php
    /**
     * News ausgeben
     * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
     * @copyright 2011-2014
     *
     */
    header("Content-Type: text/html; charset=iso-8859-1");

    require_once __DIR__.'/inc/init.php';	
    require_once __DIR__.'/inc/central.php';
    if(file_exists(__DIR__.'/inc/central.custom.php')) { require_once __DIR__.'/inc/central.custom.php'; }

    define('FEMODE','FEMODE');

    $fpSystem   = new fpSystem($fpDBcon);
    
    // System Config & current user
    fpSecurity::init($fpDBcon);

    if(!defined("FPCACHEFOLDER"))       define('FPCACHEFOLDER', $fpcachefolder);
    if(!defined("FPCACHEEXPIRE"))       define("FPCACHEEXPIRE", fpConfig::get('cache_timeout'));
    if(!defined("FPUPLOADFOLDERURL"))   define('FPUPLOADFOLDERURL', '//'.$_SERVER['HTTP_HOST'].FP_ROOT_DIR.ltrim($fpuploadfolder, '/'));
    
    if(fpConfig::currentUser()) { define('FPCM_LOGGED_IN', true); } else  { define('FPCM_LOGGED_IN', false); }
    
    $fpLanguage = new fpLanguage(fpConfig::get('system_lang'));
    $fpLanguage->loadLanguageFiles();
    
    // Zeitzone setzen
    date_default_timezone_set(fpConfig::get('time_zone'));

    fpModules::includeAllFEModules();   

    include "err.php";

    if(fpConfig::get('usemode') == "iframe") :
?>
<!DOCTYPE HTML>  
<html>
    <head>
        <title>FanPress CM News</title>
        <meta http-equiv="content-type" content= "text/html; charset=iso-8859-1">
        <link rel="stylesheet" type="text/css" href="<?php print fpConfig::get('useiframecss'); ?>">
        <?php fpModuleEventsFE::runFEAddCss(); ?>
        <script type="text/javascript" src="<?php print FP_ROOT_DIR ?>js/jquery/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="<?php print FP_ROOT_DIR ?>js/fanpress.js"></script>        
        <?php fpModuleEventsFE::runFEAddJs(); ?>
    </head>

    <body>
<?php  
    else :            
        if(fpConfig::get('sys_jquery') == 1) { print "<script type=\"text/javascript\" src=\"".FP_ROOT_DIR."js/jquery/jquery-1.11.1.min.js\"></script>\n"; }
        fpModuleEventsFE::runFEAddCss();
        print "<script type=\"text/javascript\" src=\"".FP_ROOT_DIR."js/fanpress.js\"></script>\n";
        fpModuleEventsFE::runFEAddJs();
    endif;

    if(FPCM_LOGGED_IN) :
        $fpcmPath = "http://".$_SERVER["HTTP_HOST"].FP_ROOT_DIR;
        $acpPath  = $fpcmPath."acp/";
?>
        <div class="fp-acp-toolbar">
            <?php if(fpSecurity::checkPermissions ("addnews")) : ?>
            <a href="<?php print $acpPath."addnews.php"; ?>" target="_blank"><?php fpLanguage::printLanguageConstant(LANG_ACP_TOOLBAR_WRITENEW); ?></a> &bull;
            <?php endif; ?>
            <?php if(fpSecurity::checkPermissions ("editnews")) : ?>         
            
                <?php if(isset($_GET["fn"]) && $_GET["fn"] == "archive") : ?>
                    <a href="<?php print $acpPath."editnews.php?fn=editarchive"; ?>" target="_blank"><?php fpLanguage::printLanguageConstant(LANG_ACP_TOOLBAR_EDITACTIVE); ?></a> &bull;
                <?php else : ?>
                    <a href="<?php print $acpPath."editnews.php?fn=editactive"; ?>" target="_blank"><?php fpLanguage::printLanguageConstant(LANG_ACP_TOOLBAR_EDITACTIVE); ?></a> &bull;
                <?php endif; ?>
            <?php endif; ?>            
            <a href="<?php print $acpPath; ?>index.php" target="_blank"><?php fpLanguage::printLanguageConstant(LANG_ACP_TOOLBAR_OPENACP); ?></a> &bull;
            <a href="<?php print $fpcmPath; ?>logout.php?redirect=<?php print urlencode(fpConfig::get('system_url')); ?>"><?php fpLanguage::printLanguageConstant(LANG_LOGOUT); ?></a>
        </div>   
        
<?php        
    endif;
    
    $fpNewsObj     = new fpNewsPost($fpDBcon);
    $fpCommentObj  = new fpComments($fpDBcon);

    $mailname    = '';
    $mailaddress = '';
    $hpadress    = '';
    $mailctext   = '';
    
    if(FPCM_LOGGED_IN) {
        $mailname    = fpConfig::currentUser('name');
        $mailaddress = fpConfig::currentUser('email');
        $hpadress    = $_SERVER["HTTP_HOST"];
    }    
    
    // Kommentar in Datenbank hinzufügen
    if (isset($_POST["sbmtcomment"]) && isset($_GET["nid"])) {
        $newsis_c    = (int) $_GET["nid"];
        $mailname    = (string) fpSecurity::Filter1($_POST["cname"]);

        $filter_ma = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
        if($filter_ma != false) { $mailaddress = (string) fpSecurity::Filter1($_POST["email"]); } else { $mailaddress = ""; }

        $filter_url = filter_var($_POST["url"], FILTER_VALIDATE_URL);				
        if($filter_url != false) { $hpadress = (string) fpSecurity::Filter1($_POST["url"]); } else { $hpadress = ""; }

        $allowed_tags = "<a><b><i><sub><em><strong><u><blockquote><p>";

        $mailctext = htmlspecialchars(strip_tags($_POST["cmttext"],$allowed_tags), ENT_COMPAT | ENT_HTML401, FPSPECIALCHARSET);

        if(isset($_POST["isprvt"])) {
            $isprvt = (int) $_POST["isprvt"];
        } elseif(fpConfig::get('confirm_comments') == 1) {
            $isprvt = 2;
        } else {
            $isprvt = 0;
        }

        if(isset($_POST["antispam"]) && $fpCommentObj->isAntiSpamCorrect(fpSecurity::Filter5($_POST["antispam"]))) {
            if (!$fpCommentObj->isFormEmpty($_POST["cname"],$_POST["email"],$_POST["cmttext"])) {
                if (!$fpCommentObj->checkForBannedIP ($_SERVER["REMOTE_ADDR"])) {
                    if ($fpCommentObj->checkForCommentFlooding ($_SERVER["REMOTE_ADDR"],time(),fpConfig::get('comment_flood'))) {

                        $comment = new fpComment();
                        $comment->setNewsid($newsis_c);
                        $comment->setAuthorName($mailname);
                        $comment->setAuthorEmail($mailaddress);
                        $comment->setAuthorUrl($hpadress);
                        $comment->setCommentText($mailctext);
                        $comment->setStatus($isprvt);
                        $comment->setIp($_SERVER["REMOTE_ADDR"]);
                        $comment->setCommentTime(time());
                        $comment->save();

                        if(!FPCM_LOGGED_IN) {
                            $commenttxt  = $mailname." (E-Mail: ".$mailaddress.") ".fpLanguage::returnLanguageConstant(LANG_COMMENT_MAIL_TEXT)."\n----------------------------------------\n";
                            $commenttxt  .= fpSecurity::Filter3($mailctext);

                            if(isset($_POST["isprvt"])) {
                                $commenttxt .= "\n----------------------------------------\n".fpLanguage::returnLanguageConstant(LANG_COMMENT_MAIL_PRIVATE);
                            } else {
                                if(fpConfig::get('confirm_comments') == 1) {
                                    $commenttxt .= "\n----------------------------------------\n".fpLanguage::returnLanguageConstant(LANG_COMMENT_MAIL_APPROVALREQU);
                                    fpMessages::showSysNotice(LANG_NOTICE_COMMENT_APPROVAL_REQUIRED);
                                }
                            }
                            $commenttxt .= "\n\n".fpLanguage::returnLanguageConstant(LANG_NEWS_URL).": ".fpConfig::get('system_url')."?fn=cmt&nid=".$newsis_c;
                            fpMessages::sendEMail(array(
                                "mailSubject" => LANG_COMMENT_MAIL_SUBJECT,
                                "mailText"    => $commenttxt
                            ));                                
                        }                            
                    } else {
                        fpMessages::showErrorText(LANG_ERROR_FLOODPROTECTION." (min. ".fpConfig::get('comment_flood')."sec)");
                    }
                } else {
                    fpMessages::showErrorText(LANG_ERROR_IPISBANNED);
                }
            } else {
                fpMessages::showErrorText(LANG_ERROR_COMMENT_EMPTY);
            }
        } else {
            fpMessages::showErrorText(LANG_ERROR_SPAM_FALSE);
        }
    }

    // News, die erst später freigeschlaten werden sollen freischlaten...
    $fpNewsObj->checkPostponedNews();	

    // News-Template-Datei öffnen
    $nptemplate = fpTemplates::openTemplate(__DIR__ ."/styles/".fpConfig::get('active_news_template').".html");

    // Ende News-Template-Datei öffnen

    // wenn Kommentaren angezeigt werden sollen, entsprechende News holen und Kommentare ausgeben
    if (isset($_GET["fn"]) && isset($_GET["nid"]) && ($_GET["fn"] == "cmt" || $_GET["fn"] == "showprev") && $_GET["nid"] != "" && $_GET["nid"] != null) {   
        $newsid = (int) $_GET["nid"];

        if($fpNewsObj->existNews($newsid)) {
            fpModuleEventsFE::runOnNewsSingleLoad($newsid);

            $fpCache  = new fpCache('fpnewsdetail'.$newsid);

            if($_GET["fn"] == "showprev") {
                $tmpCache = "<div class=\"fp-preview-notice-box\">".fpLanguage::returnLanguageConstant(LANG_NEWS_PREVIEWVIEW)."</div>";
            } else {
                $tmpCache = "";
            }

            if($fpCache->isExpired() || FPCM_LOGGED_IN) {    
                $dataArray = array(  
                    'newsid'    => $newsid,
                    'startfrom' => 1,
                    'showlimit' => 0
                );

                $newsRow   = $fpNewsObj->getNews("single",$dataArray);
                $tmpCache .= $fpNewsObj->parseNews($newsRow,$nptemplate,$fpCommentObj->countCommentsOfNews((int) $newsRow->id));

                $commentRows = $fpCommentObj->getAllComments(0,$newsid);
                $comi = 1;

                if(count($commentRows) > 0) {		
                    $cmttemplate = fpTemplates::openTemplate(__DIR__ ."/styles/".fpConfig::get('active_comment_template').".html");
                    foreach($commentRows AS $commentRow) {
                        $tmpCache .= $fpCommentObj->parseComment($commentRow,$cmttemplate,$comi);
                        $comi++;
                    }                        
                }
                
                if(!FPCM_LOGGED_IN) $fpCache->write($tmpCache,FPCACHEEXPIRE);

                print $tmpCache;
            } else {
                print $fpCache->read(); 
            } 

            if(fpConfig::get('comments_enabled_global') && $fpNewsObj->commentsEnabled($newsid)) {                
                include __DIR__."/inc/comments.php";                
            } else {
                fpMessages::showSysNotice(LANG_EDIT_COMMENTS_DISABLED_MSG);
            }
        } else {
            fpMessages::showErrorText(LANG_NEWS_NOTFOUND);	
        }
    } elseif (isset($_GET["fn"]) == "archive") { 
        // an sonsten alle News ohne Kommentare in Archive ausgeben
        if(isset($_GET["apid"]) == "") { $limit = 0; } else { $limit = (int) $_GET["apid"]; }

        $fpCache   = new fpCache('fpnewsarchive'.$limit);
        $tmpCache   = '';

        if($fpCache->isExpired() || FPCM_LOGGED_IN) {
            $dataArray = array(  
                'previews'  => 0,
                'startfrom' => $limit,
                'showlimit' => fpConfig::get('news_show_limit')
            );             

            $newsArchiveRows = $fpNewsObj->getNews("archive", $dataArray);

            foreach($newsArchiveRows AS $newsRow) {
                $tmpCache .= $fpNewsObj->parseNews($newsRow,$nptemplate,$fpCommentObj->countCommentsOfNews($newsRow->id));
            }   

            $tmpCache .= $fpSystem->createPageNavigation(true);
            if(!FPCM_LOGGED_IN) $fpCache->write($tmpCache,FPCACHEEXPIRE);

            print $tmpCache;
        } else {
            print $fpCache->read();   
        }                          
    } else {
        // an sonsten alle News ohne Kommentare ausgeben
        if(!isset($_GET["npid"])) { $limit = 0; } else { $limit = (int) $_GET["npid"]; }

        $fpCache   = new fpCache('fpnewsactive'.$limit);
        $tmpCache   = '';

        if($fpCache->isExpired() || FPCM_LOGGED_IN) {  
            // zuerst gepinnte News ausgeben                
            $dataArray = array(                    
                'previews'  => 0,
                'startfrom' => $limit,
                'showlimit' => fpConfig::get('news_show_limit')
            );                
            $newsRowsPinned = $fpNewsObj->getNews("pinnedonly",$dataArray);

            foreach($newsRowsPinned AS $newsRow) {
                $tmpCache .= $fpNewsObj->parseNews($newsRow,$nptemplate,$fpCommentObj->countCommentsOfNews($newsRow->id), true, true);
            }                

            $dataArray = array(                    
                'previews'  => 0,
                'startfrom' => $limit,
                'showlimit' => fpConfig::get('news_show_limit')

            );                
            $newsRowsActive = $fpNewsObj->getNews("all",$dataArray);

            foreach($newsRowsActive AS $newsRow) {
                $tmpCache .= $fpNewsObj->parseNews($newsRow,$nptemplate,$fpCommentObj->countCommentsOfNews($newsRow->id));
            }

            $tmpCache .= $fpSystem->createPageNavigation();
            if(!FPCM_LOGGED_IN) $fpCache->write($tmpCache,FPCACHEEXPIRE);

            print $tmpCache;
        } else {
            print $fpCache->read();   
        }  
    }

    print "\n\n<!-- Powered by FanPress CM ".fpConfig::get("system_version")." - http://nobody-knows.org/download/fanpress-cm/ -->";
    
    fpDebugOutput();
    
    if(fpConfig::get('usemode') == "iframe") : print "\n\n";
?>
    </body>
</html>  
<?php
    endif;
    
    unset($acpPath, $fpCache, $fpCommentObj, $fpDBcon, $fpNewsObj, $fpSystem, $tmpCache, $isprvt, $commenttxt, $limit, $cmttemplate, $nptemplate, $dataArray, $comi, $newsid, $newsRowsPinned, $newsRowsActive);

?>