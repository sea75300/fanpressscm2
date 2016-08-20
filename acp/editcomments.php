<?php 
    /**
     * FanPress CM global comment list
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 2.4
     */

    require_once dirname(dirname(__FILE__)).'/inc/acpcommon.php';

    if(LOGGED_IN_USER && fpSecurity::checkPermissions ('editnews') && fpSecurity::checkPermissions('editcomments')) :
        
        if (isset($_POST["sbmdelcomment"]) && isset($_POST["delcmt"])) {

            $deletedComments = $_POST["delcmt"];

            foreach ($deletedComments as $commentId) {
                $comment = new fpComment((int) $commentId);
                $comment->deleteIdOnly = true;
                $comment->delete();                
            }
        }        
?>
    <div class="box box-fixed-margin" id="contentbox">
        <h1><?php fpLanguage::printLanguageConstant(LANG_EDIT_COMMENTS) ?></h1>
        
        <?php fpModuleEventsAcp::runOnAddACPMessage(); ?>
        
        <div id="tabsGeneral">
            <ul>
                <li><a href="#tabs-comments-list"><?php fpLanguage::printLanguageConstant(LANG_EDIT_COMMENTS) ?></a></li>
            </ul>

            <div id="tabs-comments-list">
                <form method="post" action="<?php print FPBASEURLACP; ?>editcomments.php">
                    <?php $comments = $fpComment->getAllComments(1, -1); ?>
                    <?php include FPBASEDIR.'/acp/comments/loadlist.php'; ?>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var jsEditCommentHL = '<?php fpLanguage::printLanguageConstant(LANG_EDITCMT_TITLE); ?>';
    </script>
<?php
    else :
        if(!fpSecurity::checkPermissions("editnews") || !fpSecurity::checkPermissions('editcomments')) { fpMessages::showNoAccess(); }
    endif;
?>    
<?php include FPBASEDIR."/sysstyle/sysfooter.php"; ?>