<?php if(!defined("LOGGED_IN_USER")) { die(); } ?>
<form method="post" action="editnews.php?fn=edit&nid=<?php print $fpNewsPost->getId(); ?>" name="nform" class="editor_form" accept-charset="ISO-8859-1">
    <table style="width:100%;">
        <tr>
            <td class="tdheadline" style="width:25px;"></td>
            <td class="tdheadline" style="text-align:left;"><?php fpLanguage::printLanguageConstant(LANG_EDIT_TITLE); ?></td>
            <td class="tdheadline" style="width:25%;"><?php fpLanguage::printLanguageConstant(LANG_EDIT_DATE); ?></td>
            <td class="tdheadline" style="width:75px;"><input type="checkbox" id="fileselectboxall" value=""></td>
        </tr>    
        <?php foreach ($revisions as $revisionTime => $revisionTitle) : ?>
        
        <tr class="news-list-row">
            <td class="tdtcontent" style="width:25px;">
                <a href="editnews.php?fn=edit&amp;nid=<?php print $fpNewsPost->getid(); ?>&restorerev=<?php print $revisionTime; ?>" class="btnloader fp-ui-button-restore"><?php fpLanguage::printLanguageConstant(LANG_EDIT_RESTORE_VERSION); ?></a>
            </td>
            <td class="tdtcontent" style="text-align:left;">
                <a href="editnews.php?fn=edit&amp;nid=<?php print $fpNewsPost->getid(); ?>&showrev=<?php print $revisionTime; ?>"><?php print htmlspecialchars_decode($revisionTitle); ?></a>
            </td>
            <td class="tdtcontent"><?php print date(fpConfig::get('timedate_mask'), $revisionTime); ?></td>
            <td class="tdtcontent">
                <?php if(fpConfig::currentUser('usrlevel') == 1) : ?>
                <input type="checkbox" class="fileselectbox" name="deleterevisions[]" value="<?php print $revisionTime; ?>">
                <?php endif; ?>
            </td>
        </tr>

        <?php endforeach; ?>    
    </table>

    <?php if(fpConfig::currentUser('usrlevel') == 1) : ?>
    <div class="tdtcontent buttons button_edit_row fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
        <button type="submit" id="sbmnewsactions" class="btnloader fp-ui-button"><?php fpLanguage::printOk(); ?></button>
    </div>    
    <?php endif; ?>
</form>