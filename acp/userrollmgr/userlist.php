<?php if(!defined("LOGGED_IN_USER")) { die(); } ?>          
<form method="post" action="sysconfig.php?mod=users">      
    <table width="100%" border="0">
        <tr>
            <td class="tdheadline" style="text-align:left;"><?php fpLanguage::printLanguageConstant(LANG_USR_SYSUSR); ?></td>
            <td class="tdheadline" style="width:225px;"><?php fpLanguage::printLanguageConstant(LANG_USR_EMAIL); ?></td>
            <td class="tdheadline" style="width:100px;"><?php fpLanguage::printLanguageConstant(LANG_USR_USRLEVEL); ?></td>
            <td class="tdheadline" style="width:125px;"><?php fpLanguage::printLanguageConstant(LANG_USR_USRADDEDDATE); ?></td>           
            <td class="tdheadline" style="width:100px;"><?php fpLanguage::printLanguageConstant(LANG_USR_NEWSCOUNT); ?></td>  
            <td class="tdheadline" style="width:25px;"></td>         
        </tr>
        <?php $arows = $fpUser->getAllUsers(); ?>
        <?php foreach($arows AS $user) : ?>
        <tr>      
            <td><a href="sysconfig.php?mod=users&amp;fn=edit&amp;userid=<?php print $user->id; ?>"><?php print htmlspecialchars_decode($user->sysusr); ?></a></td>
            <td class="tdtcontent"><?php print htmlspecialchars_decode($user->email); ?></td>
            <td class="tdtcontent"><?php print fpLanguage::replaceDBDataByLanguageData(htmlspecialchars_decode($user->leveltitle), $langReplaceDataArray); ?></td>
            <td class="tdtcontent"><?php print date(fpConfig::get('timedate_mask'), $user->registertime); ?></td>
            <td class="tdtcontent"><?php print $fpUser->countWrittenNews($user->id); ?></td>
            <td class="tdtcontent"><input type="radio" name="delusr"  value="<?php print $user->id; ?>"></td>      
        </tr>      
    <?php endforeach; ?>
    </table>
    <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">             
    <?php if($idxstats['authorscount'] > 0) : ?>
        <button type="submit" name="sbmdisusr" class="btnloader fp-ui-button"><?php fpLanguage::printLanguageConstant(LANG_GLOBAL_DISABLE); ?></button>
    <?php endif; ?>
        <button type="submit" name="sbmdelusr" class="btnloader fp-ui-button"><?php fpLanguage::printLanguageConstant(LANG_GLOBAL_DELETE); ?></button>
    </div>             
</form>