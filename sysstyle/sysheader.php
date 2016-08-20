<?php
    /**
     * FanPress CM Style Header
     * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
     * @copyright 2011-2014
     */

    include FPBASEDIR.'/sysstyle/styleinit.php';
?>
    <body id="body"> 
        
        <?php include FPBASEDIR.'/sysstyle/jsvars.php'; ?>
        
        <div class="fp-ui-dialog-layer" id="profile-dialog-layer">
            <table>
                <tr>
                    <td rowspan="2" class="fp-profile-info-icon"><i class="fa fa-info-circle"></i></td>
                    <td><b><?php fpLanguage::printLanguageConstant(LANG_HEADER_LOGGEDINSINCE); ?>:</b> <?php print date(fpConfig::get('timedate_mask'),fpConfig::currentUser('logintime')); ?> (<?php print fpConfig::get('time_zone'); ?>)</td>
                </tr>
                <tr>
                    <td><b>IP:</b> <?php print fpSecurity::Filter5($_SERVER["REMOTE_ADDR"]); ?></td>
                </tr>
            </table>
        </div>         
        
        <div id="header" class="header <?php if($mobileDetect->isMobile() || $mobileDetect->isTablet()) : ?>header-mobile<?php endif; ?>">
            <div class="header-inner">
                <div class="header-td1">
                    <div class="fp-logo-img"><img class="fp-logo" src="<?php print FP_ROOT_DIR ?>sysstyle/syslogo.png" alt="FanPress CM"></div>
                    <div class="fp-logo-text"><span>FanPress CM</span> <span>News System</span></div>
                    <div class="clear"></div>
                </div>
                <?php if(LOGGED_IN_USER) : ?>
                <div class="header-td3">                        
                    <a id="open-news" href="<?php print fpConfig::get('system_url'); ?>" target="_blank"><?php fpLanguage::printLanguageConstant(LANG_EDIT_OPENNEWS); ?></a>
                    <button id="clear-cache"><?php fpLanguage::printLanguageConstant(LANG_CACHE_CLEAR); ?></button>
                    <button id="profile-menu-open" class="fp-ui-button"><?php print fpConfig::currentUser('name'); ?></button>
                </div>
                 <?php endif; ?>                    
                <div class="clear"></div>
            </div>                           
        </div>        
        
        <div class="wrapper">

            <?php if(LOGGED_IN_USER) { include FPBASEDIR."/sysstyle/adminnavi.php"; } ?> 
            
            <div class="content-wrapper <?php if(!LOGGED_IN_USER) : ?>content-wrapper-nonav<?php endif; ?>">
            <?php if(!$mobileDetect->isMobile() && !$mobileDetect->isTablet()) : ?><div id="header-top-spacer" style="height: 62px;"></div><?php endif; ?>
