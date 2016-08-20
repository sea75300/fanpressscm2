<?php 
 /**
   * FanPress CM ACP Navigation
   * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
   * @copyright 2011-2014
   *
   */

    if(!defined("LOGGED_IN_USER")) { die(); }
?>
    <!-- Admin-Bereich-Navigation -->
    <div class="admin-navi <?php if($mobileDetect->isMobile() || $mobileDetect->isTablet()) : ?>admin-navi-mobile<?php endif; ?>">
        <ul class="menu">
            <li class="dashboard"><a href="<?php print FPBASEURLACP; ?>index.php" title="<?php fpLanguage::printLanguageConstant(LANG_DASHBOARD); ?>"><span class="ui-icon ui-icon-home"></span> <span class="navlinkdescr"><?php fpLanguage::printLanguageConstant(LANG_DASHBOARD); ?></span></a></li>

            <?php foreach (fpView::getNavigation() as $navigationGroup) : ?>            
                <?php foreach ($navigationGroup as $groupName => $navigationItem) : ?>
                    <?php
                        if(isset($navigationItem['permission'])) {
                            $hasModuleAccess = true;                        
                            foreach ($navigationItem['permission'] as $permission) { $hasModuleAccess &= fpSecurity::checkPermissions($permission); }
                            if(!$hasModuleAccess) continue;
                        }                        
                    ?>

                    <li>
                        <a href="<?php print $navigationItem['url']; ?>" title="<?php fpLanguage::printLanguageConstant($navigationItem['description']); ?>" class="<?php fpLanguage::printLanguageConstant($navigationItem['class']); ?>" id="<?php fpLanguage::printLanguageConstant($navigationItem['id']); ?>">
                            <span class="<?php fpLanguage::printLanguageConstant($navigationItem['icon']); ?>"></span>
                            <span class="navlinkdescr"><?php fpLanguage::printLanguageConstant($navigationItem['description']); ?></span>
                        </a>
                        <?php if(isset($navigationItem['submenu']) && count($navigationItem['submenu'])) : ?>
                            <ul class="submenu">
                                <?php foreach ($navigationItem['submenu'] as $submenuItem) : ?>                            
                                    <?php
                                        if(isset($submenuItem['permission'])) {
                                            $hasSubAccess = true;                        
                                            foreach ($submenuItem['permission'] as $permission) { $hasSubAccess &= fpSecurity::checkPermissions($permission); }
                                            if(!$hasSubAccess) continue;
                                        }
                                    ?>
                                    <li>
                                        <a href="<?php print $submenuItem['url']; ?>" title="<?php fpLanguage::printLanguageConstant($submenuItem['description']); ?>" class="<?php fpLanguage::printLanguageConstant($submenuItem['class']); ?>" id="<?php fpLanguage::printLanguageConstant($submenuItem['id']); ?>">
                                            <?php fpLanguage::printLanguageConstant($submenuItem['description']); ?>
                                        </a>
                                    </li>
                                    <?php if(isset($submenuItem['spacer']) && $submenuItem['spacer']) :?>
                                        <div class="admin-nav-modmgr-link"></div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>            
                <?php endforeach; ?>

                <?php if($groupName == 'editnews') : ?>
                    <?php fpModuleEventsAcp::runAddToNavigationMainPos4(); ?>
                <?php endif; ?>
                <?php if($groupName == 'modules') : ?>
                    <?php fpModuleEventsAcp::runAddToNavigationMainPos7(); ?>
                <?php endif; ?>                    
            <?php endforeach; ?>
        </ul>
        
        <div class="hidenav">
            <a href="" onclick="return false;"><i class="fa fa-arrow-circle-left" title="<?php fpLanguage::printLanguageConstant(LANG_HIDENAV); ?>"></i></a>            
        </div>
    </div>  
    
    <?php if($mobileDetect->isMobile() || $mobileDetect->isTablet()) : ?>
    <script type="text/javascript">jQuery(function() { jQuery('.hidenav').trigger('click'); });</script>    
    <?php endif; ?>
<?php $inprofile = false; ?>  