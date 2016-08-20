<?php if(!defined('LOGGED_IN_USER')) die(); ?>
<?php
    $news_id = (int) $_GET["nid"];

    $fpNewsPost = new fpArticle($news_id);

    $revisions = array();
    if(fpConfig::get('revisions')) {
        if(isset($_GET['restorerev'])) {
            if($fpNewsPost->restoreRevision((int) fpSecurity::fpfilter(array('filterstring' => $_GET['restorerev'])))) {
                fpMessages::showSysNotice(LANG_WRITE_REVISION_RESTORED);
                $fpCache->cleanup();
            }
        }

        if(isset($_GET['showrev'])) {            
            $revTime = (int) fpSecurity::fpfilter(array('filterstring' => $_GET['showrev']));            
            $fpNewsPost->getRevision($revTime);
        }        
        
        if(isset($_POST['deleterevisions'])) {
            if($fpNewsPost->deleteRevisions($_POST['deleterevisions'])) {
                fpMessages::showSysNotice(LANG_WRITE_REVISIONS_DELETED);
            }
        }

        $revisions = $fpNewsPost->getRevisions();            
    }

    if (isset($_POST["sbmdelcomment"]) && isset($_POST["delcmt"])) {

        $deletedComments = $_POST["delcmt"];

        foreach ($deletedComments as $commentId) {
            $comment = new fpComment((int) $commentId);
            $comment->setNewsid((int) $_GET["nid"]);
            $comment->delete();                
        }
    }

    if (isset($_POST["sbmbtnsave"])) {

        $fpNewsPost->createRevision();

        $ispreview      = isset($_POST["sbmnp_prev"]) ? (int) $_POST["sbmnp_prev"] : 0; 
        $np_cat_array   = empty($_POST["cat"]) ? 1 : implode(";",$_POST["cat"]);

        $fpNewsPost->setTitel(fpSecurity::fpfilter(array('filterstring' => $_POST["titel"]), array(1,3,2,7)));
        $fpNewsPost->setContent(fpSecurity::fpfilter(array('filterstring' => $_POST["newstext"]), array(7)));
        $fpNewsPost->setCategory($np_cat_array);
        $fpNewsPost->setPreview($ispreview);

        $comments_active = (isset($_POST['commentenabled'])) ? (int) $_POST['commentenabled'] : 0;                
        $fpNewsPost->setCommentsActive($comments_active);            

        $is_pinned = (isset($_POST['pinnews'])) ? (int) $_POST['pinnews'] : 0;
        $fpNewsPost->setPinned($is_pinned);

        $is_archived = (isset($_POST['toarchive'])) ? (int) $_POST['toarchive'] : 0;
        if($is_archived)$fpNewsPost->setPinned (0);
        $fpNewsPost->setArchived($is_archived);

        if($fpNewsPost->update()) {
            fpMessages::showSysNotice(LANG_EDIT_SAVEDMSG);
        }
        
        $fpCache->cleanup();
    }

    if(isset($_GET["added"])) {  
        $fpCache->cleanup();
        $addedStatus = (int) fpSecurity::Filter5($_GET["added"]);            
        switch ($addedStatus) {
            case 1:
                fpMessages::showSysNotice(LANG_WRITE_PUBLISHED);
                if($fpSystem->canConnect() && function_exists('curl_version') && !$fpNewsPost->isPreview()) {
                    $alink = $fpNewsPost->getArticleUrl();
                    $fpNews->createTweet($fpNewsPost->getTitel(),$fpNews->createShortLink($alink));
                }                    
            break;
            case 2:
                fpMessages::showSysNotice(LANG_WRITE_PREVIEWPUB);
            break;
            default :
            break;
        }
    }

    $commentCount = $fpComment->countCommentsOfNews($news_id,1);
?>
<div id="tabsGeneral">
    <ul>
        <li><a href="#tabs-news-edit"><?php fpLanguage::printLanguageConstant(LANG_NEWSEDITOR); ?></a></li>
        <?php if($commentCount > 0 && !isset($revTime) && fpSecurity::checkPermissions ("editcomments")) : ?>
        <li><a href="#tabs-news-comments"><?php fpLanguage::printLanguageConstant(LANG_EDIT_COMMENTS); ?></a></li>
        <?php endif; ?>
        <?php if(count($revisions) && !isset($revTime)) : ?>
        <li><a href="#tabs-news-revisions"><?php fpLanguage::printLanguageConstant(LANG_EDIT_REVISIONS); ?></a></li>
        <?php endif; ?>
    </ul>

    <div id="tabs-news-edit">
<?php
    $iseditmode = 1;

    $ntitle = htmlspecialchars_decode($fpNewsPost->getTitel());
    if($fpNewsPost->isPreview() == 1 || $fpNewsPost->isPreview() == 2) {
        $alink = fpConfig::get('system_url')."?fn=showprev&nid=".$fpNewsPost->getId();
    } else {
        $alink = fpConfig::get('system_url')."?fn=cmt&nid=".$fpNewsPost->getId();
    }

    switch(fpConfig::get('system_editor')) {
        case "classic" :
            include FPBASEDIR."/acp/editor/html.php";
        break;
        default :            
            include FPBASEDIR."/acp/editor/tinymce.php";
        break;                    
    }
?>        
    </div>

    <?php if($commentCount > 0 && !isset($revTime) && fpSecurity::checkPermissions ("editcomments")) : ?>
    <div id="tabs-news-comments">        
        <?php include FPBASEDIR."/acp/comments/editorlist.php"; ?>
    </div>
    <?php endif; ?>

    <?php if(count($revisions) && !isset($revTime)) : ?>
    <div id="tabs-news-revisions">        
        <?php include FPBASEDIR."/acp/editor/revisions.php"; ?>
    </div>
    <?php endif; ?>
</div>

<script type="text/javascript">
    var jsEditCommentHL = '<?php fpLanguage::printLanguageConstant(LANG_EDITCMT_TITLE); ?>';
    var fpNewsListActionConfirmMsg = '<?php fpLanguage::printLanguageConstant(LANG_EDIT_ACTION_CONFIRM_MSG); ?>';
</script>