<?php if(!defined('LOGGED_IN_USER')) die(); ?>
<?php
    if(isset($_POST["newsid"]) && isset($_POST["newsactions"]) || isset($_POST["sbmnewsactions"])) {
        $newsItems = array_map('intval', $_POST["newsid"]);

        switch($_POST["newsactions"]) {
            case "pinnews" :
                foreach ($newsItems as $id) {
                    $pinnedTest = $fpNews->pinNews($id);
                }
            break;
            case "edcomments" :
                foreach ($newsItems as $id) {
                    $commentDisablesTest = $fpNews->enableDisableCommentsForNews($id);
                }
            break;
            case "toarchive" :
                foreach ($newsItems as $id) {
                    $fpNews->setNewsToArchive($id);
                    $narch = true;
                }
            break;
            case "tweetnews" :
                if($fpSystem->canConnect() && function_exists('curl_version')) {
                    foreach ($newsItems as $id) {
                        $fpNewsPost = new fpArticle($id);                        
                        $fpNews->createTweet($fpNewsPost->getTitel(), $fpNewsPost->getArticleUrl());
                        $ntweet = true;
                    }                       
                }                    
            break;
            case "deletenews" :
                foreach ($newsItems as $id) {
                    $fpNews->deleteNews($id);
                    $ndel = true;
                }
            break;
        }
        
        $fpCache->cleanup();
    }
    
    if(fpConfig::get('use_trash') && isset($_POST["newsactions"]) || isset($_POST["sbmnewsactions"])) {
        switch($_POST["newsactions"]) {
            case "cleartrash" :
                $fpNews->clearTrash();
                $trashCleared = true;
            break;
            case "restoretrash" :
                
                if(isset($_POST["trashids"])) {
                    $newsItems = array_map('intval', $_POST["trashids"]);                    
                    $fpNews->restoreFromTrash($newsItems);
                    $trashRestored = true;
                }
                
            break;        
        }
    }

    if (isset($trashCleared))  { fpMessages::showSysNotice(LANG_EDIT_TRASH_CLEARED); } 
    if (isset($trashRestored)) { fpMessages::showSysNotice(LANG_EDIT_TRASH_RESTORED); }    
    if (isset($ndel))   { fpMessages::showSysNotice(LANG_EDIT_DELETEDMSG); }      
    if (isset($narch))  { fpMessages::showSysNotice(LANG_EDIT_ARCHIVEDMSG); } 
    if (isset($ntweet)) { fpMessages::showSysNotice(LANG_EDIT_NEWTWEETED); }
    if (isset($pinnedTest)) {
        if($pinnedTest == 1) {
            fpMessages::showSysNotice(LANG_EDIT_PINNEDMSG);
        } elseif($pinnedTest == -1) {
            fpMessages::showSysNotice(LANG_EDIT_NOTPINNEDMSG);
        } else {
            fpMessages::showSysNotice(LANG_EDIT_UNPINNEDMSG);
        }               
    }
    if (isset($commentDisablesTest)) {
        if($commentDisablesTest == 1) {
            fpMessages::showSysNotice(LANG_EDIT_COMMENTS_ENABLED_MSG);
        } elseif($commentDisablesTest == 0) {
            fpMessages::showSysNotice(LANG_EDIT_COMMENTS_DISABLED_MSG);
        } else {
            fpMessages::showSysNotice(LANG_EDIT_COMMENTS_NOTTODO_MSG);
        }               
    } 

    $searchAll = 0;

    if(isset($_GET['fn'])) {
        switch($_GET["fn"]) {
            case "editactive" :
                $searchAll = 1;
            break;                
            case "editarchive" :
                $searchAll = 2;
            break;
        }
    }
?>

<?php include FPBASEDIR.'/acp/newslist/searchform.php'; ?>

<form id="newseditform" method="post" action="editnews.php<?php if(!empty($_SERVER["QUERY_STRING"])) { print "?".$_SERVER["QUERY_STRING"]; } ?>">
    <div id="news-list-box">
        <?php include FPBASEDIR.'/acp/newslist/newslist.php'; ?>
    </div>
    <div class="tdtcontent buttons button_edit_row fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
        <button id="news-open-search" class="btnloader button_edit_area fp-ui-button"><?php fpLanguage::printLanguageConstant(LANG_EDIT_SEARCH); ?></button>

        <div class="fp-ui-spacer">&nbsp;</div>

        <select class="chosen-select-bottom chosen-select-newsactions" id="newsactions" name="newsactions" style="width:250px;">
            <option value=""><?php fpLanguage::printLanguageConstant(LANG_EDIT_ACTION); ?></option>
            <?php $fn = isset($_GET['fn']) ? fpSecurity::Filter2($_GET['fn']) : ''; ?>
            <?php if($fn != "editarchive") : ?>
                <option value="pinnews"><?php fpLanguage::printLanguageConstant(LANG_EDIT_PINNEWS); ?></option>
                <?php if(fpConfig::get('twitter_access_token') != "" && fpConfig::get('twitter_access_token_secret') != "") :  ?>
                <option value="tweetnews"><?php fpLanguage::printLanguageConstant(LANG_EDIT_CREATETWEET); ?></option>                    
                <?php endif; ?>                    
            <?php endif; ?>                    
            <?php if(fpConfig::get('comments_enabled_global')) : ?>
            <option value="edcomments"><?php fpLanguage::printLanguageConstant(LANG_EDIT_COMMENTS_ED); ?></option>
            <?php endif; ?>
            <?php if(fpSecurity::checkPermissions ("editnewsarchive")) : ?>
            <option value="toarchive"><?php fpLanguage::printLanguageConstant(LANG_EDIT_ARCHIVE); ?></option>
            <?php endif; ?>
            <?php if(fpSecurity::checkPermissions ("deletenews")) : ?>
            <option value="deletenews"><?php fpLanguage::printLanguageConstant(LANG_GLOBAL_DELETE); ?></option>
                <?php if(fpConfig::get('use_trash')) : ?>
                <option value="cleartrash"><?php fpLanguage::printLanguageConstant(LANG_EDIT_TRASH_CLEAR); ?></option>
                <option value="restoretrash"><?php fpLanguage::printLanguageConstant(LANG_EDIT_TRASH_RESTORE); ?></option>
                <?php endif; ?>
            <?php endif; ?>
        </select>

        <button type="submit" id="sbmnewsactions" class="btnloader fp-ui-button"><?php fpLanguage::printOk(); ?></button>
    </div>              
</form>

<script type="text/javascript">
    jQuery(function() { 
        jQuery("#news-open-search").button({
            icons: {
                primary: "ui-icon-search"
            },
            text: true
        }).click(function() {
            showLoader(true);
            jQuery("#news-search-box").dialog({
                width: 600,
                height: 250,
                modal: true,
                resizable: true,
                title: "<?php fpLanguage::printLanguageConstant(LANG_EDIT_SEARCH) ?>",
                buttons: [
                    {
                        text: "<?php fpLanguage::printLanguageConstant(LANG_EDIT_SEARCH_START); ?>",
                        click: function() {
                            searchNews();
                            jQuery( this ).dialog( "close" );
                        },
                        icons: {
                            primary: "ui-icon-search", text: true
                        }                            
                    },
                    {
                        text: "<?php fpLanguage::printClose(); ?>",
                        click: function() {
                            jQuery( this ).dialog( "close" );
                        }
                    }
                ]                     
            });
            showLoader(false);
            return false;
        }); 
    });
    
    var lastnewssearch = 0;
    var waitmsg        = '<?php fpLanguage::printLanguageConstant(LANG_EDIT_SEARCH_WAITMSG) ?>';
</script> 