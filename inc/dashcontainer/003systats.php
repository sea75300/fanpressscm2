<div class="fpcm-dashboard-conatiner">
    <div class="fpcm-dashboard-conatiner-inner fpcm-dashboard-conatiner-inner-boxes ui-widget-content ui-corner-all ui-state-normal">
        <h3 class="ui-corner-top  ui-corner-all"><span class="fa fa-bar-chart"></span> <?php fpLanguage::printLanguageConstant(LANG_INDEX_STATS); ?></h3>
        <div>
            <ul>
                <li><b><?php fpLanguage::printLanguageConstant(LANG_STATS_NEWS); ?></b>: <?php print $idxstats['newscount']; ?></li>
                <li><b><?php fpLanguage::printLanguageConstant(LANG_STATS_NEWS_AC); ?></b>: <?php print $idxstats['newspostactive']; ?></li>
                <li><b><?php fpLanguage::printLanguageConstant(LANG_STATS_NEWS_AR); ?></b>: <?php print $idxstats['newspostarchive']; ?></li>
                <li><b><?php fpLanguage::printLanguageConstant(LANG_STATS_NEWSP); ?></b>: <?php print $idxstats['newcountprev']; ?></li>
                <li><b><?php fpLanguage::printLanguageConstant(LANG_STATS_COMMENTS); ?></b>: <?php print $idxstats['commentcount']; ?></li>
                <li<?php if($idxstats['commentcountpriv'] > 0 && fpConfig::get('confirm_comments') == 1) { print " class=\"important-notice-text\""; } ?>><b><?php fpLanguage::printLanguageConstant(LANG_STATS_COMMENTSP); ?></b>: <?php print $idxstats['commentcountpriv']; ?></li>            
                <li><b><?php fpLanguage::printLanguageConstant(LANG_STATS_USERS); ?></b>: <?php print $idxstats['authorscount']; ?></li>
                <li><b><?php fpLanguage::printLanguageConstant(LANG_STATS_CATEGORIES); ?></b>: <?php print $idxstats['categorycount']; ?></li>
                <li><b><?php fpLanguage::printLanguageConstant(LANG_STATS_SMILIES); ?></b>: <?php print $idxstats['smiliescount']; ?></li>
                <li><b><?php fpLanguage::printLanguageConstant(LANG_STATS_UPLOADS); ?></b>: <?php print $idxstats['uploadcount']; ?></li>
                <li><b><?php fpLanguage::printLanguageConstant(LANG_STATS_SIZE_UPLOAD); ?></b>: <?php print $idxstats['uploadsize']; ?></li>
                <li><b><?php fpLanguage::printLanguageConstant(LANG_STATS_SIZE_CACHE); ?></b>: <?php print $idxstats['cachesize']; ?></li>                        
            </ul>             
        </div>                       
    </div>
</div>  