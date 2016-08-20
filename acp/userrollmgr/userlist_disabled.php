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
        <?php $drows = $fpUser->getDisabledUser(); ?>

        <?php foreach($drows AS $disabledUser) : ?>
        <tr>      
            <td><strong><?php print htmlspecialchars_decode($disabledUser->sysusr); ?></strong></td>
            <td class="tdtcontent"><?php print htmlspecialchars_decode($disabledUser->email); ?></td>
            <td class="tdtcontent">-</td>
            <td class="tdtcontent"><?php print date(fpConfig::get('timedate_mask'), $disabledUser->registertime); ?></td>
            <td class="tdtcontent"><?php print $fpUser->countWrittenNews($disabledUser->id); ?></td>
            <td class="tdtcontent"><input type="radio" name="delusr"  value="<?php print $disabledUser->id; ?>"></td>      
        </tr>      
    <?php endforeach; ?> 
    </table>
    <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
    <?php if($idxstats['authorscount'] > 0) : ?>
        <button type="submit" name="sbmenausr" class="btnloader fp-ui-button"><?php fpLanguage::printLanguageConstant(LANG_GLOBAL_ENABLE); ?></button>
    <?php endif; ?>
        <button type="submit" name="sbmdelusr" class="btnloader fp-ui-button"><?php fpLanguage::printLanguageConstant(LANG_GLOBAL_DELETE); ?></button>
    </div>              
</form> 