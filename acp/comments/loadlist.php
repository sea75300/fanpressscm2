<?php
    /**
     * FanPress CM view class
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 2.5
     */

    if(!defined("LOGGED_IN_USER")) { die(); }
?>  

<?php if(fpSecurity::checkPermissions ("editcomments")) : ?>
    <table width="100%" border="0">
        <tr>
            <td class="tdheadline" style="text-align:left;"><?php fpLanguage::printLanguageConstant(LANG_COMMENT_NAME); ?></td>
            <td class="tdheadline" style="width:200px;"><?php fpLanguage::printLanguageConstant(LANG_COMMENT_EMAIL); ?></td>
            <td class="tdheadline" style="width:150px;"><?php fpLanguage::printLanguageConstant(LANG_EDIT_DATE); ?></td>
            <td class="tdheadline" style="width:75px;"><input type="checkbox" id="fileselectboxall" value=""></td>
        </tr>

        <?php foreach($comments AS $comments) : ?>
        <tr class="comment-list-row">
            <td>
                <?php if(fpConfig::currentUser('usrlevel') == 1 || fpSecurity::checkPermissions('deletecomments')) : ?>
                <a rel="fpacpcommentedit" href="<?php print FPBASEURLACP; ?>comments/editcomment.php?cid=<?php print $comments->id; ?>" class="fbcommentedit"><?php print htmlspecialchars_decode($comments->author_name); ?></a>
                <?php else : ?>
                <strong><?php print htmlspecialchars_decode($comments->author_name); ?></strong>
                <?php endif; ?>
                <div class="editor_meta2 editor_meta2_list">
                <?php
                    switch($comments->status) {
                        case 1:
                            fpLanguage::printLanguageConstant(LANG_COMMENT_PRIVATE);
                        break;
                        case 2:
                            fpLanguage::printLanguageConstant(LANG_COMMENT_APPROVED_NO);
                        break;
                        default:
                            if(fpConfig::get('confirm_comments') == 1) { fpLanguage::printLanguageConstant(LANG_COMMENT_APPROVED_YES); }
                        break;                    
                    }
                ?>                
                </div>
            </td>
            <td class="tdtcontent"><?php print htmlspecialchars_decode($comments->author_email); ?></td>
            <td class="tdtcontent"><?php print date(fpConfig::get('timedate_mask'), $comments->comment_time); ?></td>
            <td class="tdtcontent">
            <?php if(fpConfig::currentUser('usrlevel') == 1 || fpSecurity::checkPermissions('deletecomments')) : ?>
                <input class="fileselectbox" type="checkbox" name="delcmt[]" value="<?php print $comments->id; ?>">
            <?php endif; ?>
            </td>
        </tr>    
        <?php endforeach; ?>
    </table>

    <?php if(fpConfig::currentUser('usrlevel') == 1 || fpSecurity::checkPermissions('deletecomments')) : ?>
        <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
            <button type="submit" name="sbmdelcomment" class="btnloader fp-ui-button"><?php fpLanguage::printLanguageConstant(LANG_GLOBAL_DELETE); ?></button>
        </div>
    <?php endif; ?>
<?php endif; ?>