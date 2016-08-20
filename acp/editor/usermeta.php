<?php if(!defined("LOGGED_IN_USER")) { die(); } ?>
<div class="editor_meta_box">
    <div class="editor_meta">   
        <b><?php fpLanguage::printLanguageConstant(LANG_EDITOR_AUTHOREDIT); ?>:</b>
        <?php print htmlspecialchars_decode($fpNewsPost->getAuthorName()); ?> &bull;
        <?php print date(fpConfig::get('timedate_mask'), $fpNewsPost->getWrittentime()); ?>
        <?php if($fpNewsPost->getEditedtime()) : ?>
            (<?php fpLanguage::printLanguageConstant(LANG_EDITOR_LASTEDIT); ?> <?php print date(fpConfig::get('timedate_mask'), $fpNewsPost->getEditedtime()); ?>)
        <?php endif; ?>    
            <div>
              <span><b><?php fpLanguage::printLanguageConstant(LANG_NEWS_URL); ?>:</b> <a href="<?php print $alink; ?>"><?php print $alink; ?></a></span> &bull;
              <span><a href="" id="editor_link_twitterlink"><?php fpLanguage::printLanguageConstant(LANG_EDITOR_SHORTLINKS); ?></a></span>          
            </div>  
    </div>
    <div class="editor_meta2">
        <?php include FPBASEDIR.'/acp/editor/usermeta_status.php'; ?>
    </div>
    <div class="clear"></div>    
</div>