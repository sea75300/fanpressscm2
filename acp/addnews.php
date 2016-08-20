<?php 
    /**
     * Neue News schreiben
     * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
     * @copyright 2011-2014
     *
     */  

    require_once dirname(dirname(__FILE__)).'/inc/acpcommon.php';   
    
    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("addnews")) :
        ob_start();                
        print "<div class=\"box box-fixed-margin\" id=\"contentbox\">\n<h1>".fpLanguage::returnLanguageConstant(LANG_WRITE)."</h1>\n";
        
        fpModuleEventsAcp::runOnAddACPMessage();
        
        $newsadded = 0; 
        $frmempty = false;
        
        $fpNewsPost = new fpArticle();
        
        if (isset($_POST["sbmbtnsave"])) {            
            if(!$fpNews->isFormEmpty()) {
                $ispreview      = isset($_POST["sbmnp_prev"]) ? (int) $_POST["sbmnp_prev"] : 0; 
                $np_cat_array   = empty($_POST["cat"]) ? 1 : implode(";",$_POST["cat"]);

                $fpNewsPost->setCategory($np_cat_array);
                $fpNewsPost->setTitel(fpSecurity::fpfilter(array('filterstring' => $_POST["titel"]), array(1,3,2,7)));
                $fpNewsPost->setContent(fpSecurity::fpfilter(array('filterstring' => $_POST["newstext"]), array(7)));
                $fpNewsPost->setAuthor(fpConfig::currentUser('id'));
                $fpNewsPost->setArchived(0);
                $fpNewsPost->setPreview($ispreview);
                
                $comments_active = (isset($_POST['commentenabled'])) ? (int) $_POST['commentenabled'] : 0;                
                $fpNewsPost->setCommentsActive($comments_active);
                
                $is_pinned = (isset($_POST['pinnews'])) ? (int) $_POST['pinnews'] : 0;
                $fpNewsPost->setPinned($is_pinned);                
                
                $writtentime = time();
                if(isset($_POST["postponeto"]['checked'])) {
                    $temp = $_POST["postponeto"];    
                    $dateObj = new DateTime($temp['date']);
                    $dateObj->setTime($temp['hour'], $temp['minute'], 0);                    
                    $writtentime = $dateObj->getTimestamp();
                    $fpNewsPost->setPreview(2);
                }                                
                
                $fpNewsPost->setWrittentime($writtentime);                
                $fpNewsPost->setEditedtime(0);
                
                $nlid = $fpNewsPost->save();
                
                $msgType = ($ispreview == 1) ? 2 : 1;

                $html   = array();
                $html[] = "<script type=\"text/javascript\">";
                $html[] = "jQuery(function() {";
                $html[] = "showLoader(true);";                  
                $html[] = "relocate('editnews.php?fn=edit&nid=$nlid&added=$msgType');";
                $html[] = "});";
                $html[] = "</script>";                
                
                print implode(PHP_EOL, $html);
            } else {
                fpMessages::showErrorText(LANG_WRITE_EMPTY);
                $frmempty = true;
            }
            
            if($frmempty) { $ntitle = htmlspecialchars_decode($_POST["titel"]); }          
        } 
?>        
        <div id="tabsGeneral">
            <ul>
                <li><a href="#tabs-news-edit"><?php fpLanguage::printLanguageConstant(LANG_NEWSEDITOR); ?></a></li>
            </ul>

            <div id="tabs-news-edit">
            <?php if(fpConfig::get("system_editor") == "classic") include "editor/html.php"; else include "editor/tinymce.php"; ?>
            </div>
        </div>

    </div>      
<?php else : ?>
    <?php if(!fpSecurity::checkPermissions("addnews")) { fpMessages::showNoAccess(); } ?>
<?php endif; ?>
        
<?php include FPBASEDIR."/sysstyle/sysfooter.php"; ?>