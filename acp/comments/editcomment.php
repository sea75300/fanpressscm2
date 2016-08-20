<?php
 /**
   * Kommentar bearbeiten
   * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
   * @copyright 2011-2014
   *
   */
    define('NO_HEADER','');
    require_once dirname(dirname(dirname(__FILE__))).'/inc/acpcommon.php';
    
    $cmteddited = false;
    
    include FPBASEDIR.'/sysstyle/styleinit.php';
?> 
<body id="body" style="height:auto;padding-right:0.5em;overflow:hidden;" class="nogradeint">

<?php
  if(LOGGED_IN_USER && fpSecurity::checkPermissions ("editcomments")) {
      $comment = new fpComment($_GET["cid"]);
      
      if(isset($_POST["sbmedtcmt"])) {       
        $comment->setAuthorEmail(fpSecurity::fpfilter(array('filterstring' => $_POST['email'])));
        $comment->setAuthorUrl(fpSecurity::fpfilter(array('filterstring' => $_POST['url']), array(1,4,2,7)));
        
        $commentText    = fpSecurity::fpfilter(
            array('filterstring' => $_POST['cmttext'], 'allowedtags' => "<a><b><i><sub><em><strong><u><blockquote><p>"),
            array(1,2,7)
        );        
        $comment->setCommentText($commentText);
        
        $status = (isset($_POST["isprvt"])) ? (int) fpSecurity::Filter1($_POST["isprvt"]): 0;        
        $comment->setStatus($status);
        $comment->update();
        
        $cmteddited = true;
      }
      
      
?>
    <?php
      if($cmteddited) { fpMessages::showSysNotice(LANG_COMMENT_EDITTEDMSG); }
    ?>
    <form method="post" action="editcomment.php?cid=<?php print $comment->getId(); ?>">
        <div id="tabsGeneral">
            <ul>
                <li><a href="#tabs-news-edit"><?php fpLanguage::printLanguageConstant(LANG_EDITCMT_TITLE); ?></a></li>
            </ul>

            <div id="tabs-news-edit">
                <table class="fp-comment-edit">
                    <tr>
                        <td class="tdheadline2">
                            <label><?php fpLanguage::printLanguageConstant(LANG_COMMENT_WRITTENFROM); ?>:</label>
                        </td>
                        <td>
                            <?php print htmlspecialchars_decode($comment->getAuthorName()); ?> @ <?php print date(fpConfig::get('timedate_mask'), $comment->getCommentTime()); ?> (IP: <?php print $comment->getIp(); ?>)
                        </td>
                    </tr>
                    <tr>
                        <td class="tdheadline2">
                            <label><?php fpLanguage::printLanguageConstant(LANG_COMMENT_EMAIL); ?>:</label>
                        </td>
                        <td>                                
                            <input type="text" name="email" size="50" maxlength="255" value="<?php print htmlspecialchars_decode($comment->getAuthorEmail()); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="tdheadline2">
                            <label><?php fpLanguage::printLanguageConstant(LANG_COMMENT_URL); ?>:</label>
                        </td>
                        <td>
                            <input type="text" name="url" size="50" maxlength="255" value="<?php print htmlspecialchars_decode($comment->getAuthorUrl()); ?>">
                        </td>
                    </tr>   
                    <tr>
                        <td colspan="2">
                            <textarea class="editor-textarea-comment" style="height:265px;width:100%;" name="cmttext" cols="50" rows="10"><?php print htmlspecialchars_decode($comment->getCommentText()); ?></textarea>
                        </td>
                    </tr>
                </table>


            </div>
        </div>

        <div class="buttons">
        <?php if(fpConfig::get('confirm_comments') == 0) : ?>
            <label for="isprvt"><?php fpLanguage::printLanguageConstant(LANG_COMMENT_PRIVATE); ?></label>
            <input type="checkbox" value="<?php if ($comment->status == 1 || $comment->getStatus() == 2) { print $comment->getStatus(); } else { print "1"; } ?>" name="isprvt"<?php if ($comment->getStatus() == 1 || $comment->getStatus() == 2) { print "checked=\"checked\""; } ?>>
        <?php else : ?>
            <label for="isprvt"><?php fpLanguage::printLanguageConstant(LANG_COMMENT_APPROVED_NO); ?></label>
            <input type="radio" value="2" name="isprvt"<?php if ($comment->getStatus() == 2) { print "checked=\"checked\""; } ?>>
            <label for="isprvt"><?php fpLanguage::printLanguageConstant(LANG_COMMENT_APPROVED_YES); ?></label>
            <input type="radio" value="0" name="isprvt"<?php if ($comment->getStatus() == 0) { print "checked=\"checked\""; } ?>>
        <?php endif; ?>                
            <button type="submit" name="sbmedtcmt" class="btnloader fp-ui-button"><?php fpLanguage::printSave(); ?></button>
        </div>            
    </form>
<?php
    } else {
        if(!fpSecurity::checkPermissions("editnews") || !fpSecurity::checkPermissions ("editcomments")) { fpMessages::showNoAccess(); }
        if(!LOGGED_IN_USER) { fpMessages::showNoAccess(LANG_ERROR_NOACCESS); }
    }  
?>
    </body>
</html>  