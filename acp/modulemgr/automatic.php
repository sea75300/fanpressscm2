<?php if(!defined("LOGGED_IN_USER")) { die(); } ?>    
<div id="tabsGeneral">
    <ul>
        <li><a href="#tabs-modules"><?php fpLanguage::printLanguageConstant(LANG_MODULES_LIST); ?></a></li>
        <?php if(fpSecurity::checkPermissions('moduleinstall')) : ?>
        <li><a href="#tabs-modules-upload"><?php fpLanguage::printLanguageConstant(LANG_MODULES_UPLOADZIP); ?></a></li>
        <?php endif; ?>
    </ul>
    <div id="tabs-modules">
        <table class="fp-ui-options fp-module-manager">
            <tr>
                <th class="tdheadline"><?php fpLanguage::printLanguageConstant(LANG_MODULES_KEY); ?></th>
                <th class="tdheadline"><?php fpLanguage::printLanguageConstant(LANG_MODULES_NAME); ?></th>
                <th class="tdheadline"><?php fpLanguage::printLanguageConstant(LANG_MODULES_TYPE); ?></th>
                <th class="tdheadline"><?php fpLanguage::printLanguageConstant(LANG_MODULES_VERSIONLOCAL); ?></th>
                <th class="tdheadline"><?php fpLanguage::printLanguageConstant(LANG_MODULES_VERSIONSRV); ?></th>                         
                <th class="tdheadline"><?php fpLanguage::printLanguageConstant(LANG_MODULES_ACTION); ?></th>
            </tr>
        <?php $moduleList = $fpSystem->checkForModuleUpdates(); ?>

        <?php foreach ($moduleList['remotelist'] as $moduleKey => $moduleValue) : ?>
            <tr class="module-manager-list-row1 <?php if(isset($moduleList['locallist'][$moduleKey]['updateav'])) : ?>module-manager-list-update<?php endif; ?>">
                <td class="tdcontent module-manager-list-td1 <?php if(isset($moduleList['locallist'][$moduleKey]['updateav'])) : ?>important-notice-text<?php endif; ?>">
                    <i><?php print $moduleKey; ?></i>
                </td>
                <td class="tdcontent module-manager-list-td2 <?php if(isset($moduleList['locallist'][$moduleKey]['updateav'])) : ?>important-notice-text<?php endif; ?>">
                    <b><?php print $moduleValue['name']; ?></b>
                </td>
                <td class="tdcontent module-manager-list-td3 <?php if(isset($moduleList['locallist'][$moduleKey]['updateav'])) : ?>important-notice-text<?php endif; ?>">
                    <?php if(isset($moduleList['locallist'][$moduleKey]['type'])) : ?>
                        <?php print $moduleList['locallist'][$moduleKey]['type']; ?>
                    <?php else : ?>                                    
                        <?php fpLanguage::printLanguageConstant(LANG_MODULES_NOTINSTALLED); ?>
                    <?php endif; ?>
                </td>                            
                <td class="tdcontent module-manager-list-td4 <?php if(isset($moduleList['locallist'][$moduleKey]['updateav'])) : ?>important-notice-text<?php endif; ?>">
                <?php if(!isset($moduleList['locallist'][$moduleKey])) : ?>
                    <?php fpLanguage::printLanguageConstant(LANG_MODULES_NOTINSTALLED); ?>
                <?php else : ?>                                    
                    <?php print $moduleList['locallist'][$moduleKey]['version']; ?>
                <?php endif; ?>
                </td>
                <td class="tdcontent module-manager-list-td5 <?php if(isset($moduleList['locallist'][$moduleKey]['updateav'])) : ?>important-notice-text<?php endif; ?>">
                    <?php print $moduleValue['version']; ?>
                </td>
                <td class="tdcontent module-manager-list-td6 module-manager-list-buttons" rowspan="2">                                
                <?php if(!isset($moduleList['locallist'][$moduleKey])) : ?>
                    <a class="btnloader fp-ui-button" id="<?php print $moduleKey; ?>_install" href="modules.php?ext=<?php print $moduleKey; ?>&loadmodule=<?php print $moduleKey; ?>_<?php print $moduleValue['version']; ?>&act=install" title="<?php fpLanguage::printLanguageConstant(LANG_MODULES_INSTALLMOD); ?>">
                        <i class="fa fa-download"></i>
                    </a>                                 
                <?php elseif(isset($moduleList['locallist'][$moduleKey])) : ?>
                    <?php if(fpModules::moduleIsActive($moduleKey)) : ?>
                        <a class="btnloader fp-ui-button" href="modules.php?ext=<?php print $moduleKey; ?>&act=open" title="<?php fpLanguage::printLanguageConstant(LANG_MODULES_OPEN); ?>">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <?php if(fpSecurity::checkPermissions('moduleendisable')) : ?>
                        <a class="btnloader fp-ui-button fp-modmgr-btn" id="<?php print $moduleKey; ?>_moduledisable" href="ajaxproc.php?ext=<?php print $moduleKey; ?>&act=disable" title="<?php fpLanguage::printLanguageConstant(LANG_GLOBAL_DISABLE); ?>">
                            <i class="fa fa-power-off"></i>
                        </a>
                        <?php endif; ?>
                    <?php else : ?>
                        <?php if(fpSecurity::checkPermissions('moduleendisable')) : ?>
                        <a class="btnloader fp-ui-button fp-modmgr-btn" id="<?php print $moduleKey; ?>_moduleenable" href="ajaxproc.php?ext=<?php print $moduleKey; ?>&act=enable" title="<?php fpLanguage::printLanguageConstant(LANG_GLOBAL_ENABLE); ?>">                                                        
                            <i class="fa fa-play-circle"></i>
                        </a>
                        <?php endif; ?>
                        <?php if(fpSecurity::checkPermissions('moduleuninstall')) : ?>
                        <a class="btnloader fp-ui-button fp-modmgr-btn" id="<?php print $moduleKey; ?>_moduleuninstall" href="ajaxproc.php?ext=<?php print $moduleKey; ?>&act=uninstall" title="<?php fpLanguage::printLanguageConstant(LANG_MODULES_UNINSTAll); ?>">
                            <i class="fa fa-trash-o "></i>
                        </a>
                        <?php endif; ?>
                        <?php if(file_exists(FPBASEDIR."/inc/modules/".$moduleKey."/install.php") && fpSecurity::checkPermissions('moduleinstall')) : ?>
                            <a class="btnloader fp-ui-button" href="modules.php?ext=<?php print $moduleKey; ?>&act=installphp" title="<?php fpLanguage::printLanguageConstant(LANG_MODULES_INSTAll); ?>">
                                <i class="fa fa-check-square"></i>
                            </a>
                        <?php endif; ?>                    
                    <?php endif; ?>   

                    <?php if(isset($moduleList['locallist'][$moduleKey]['updateav']) && fpSecurity::checkPermissions('moduleinstall')) : ?>
                    <a class="btnloader fp-ui-button fp-modmgr-spin-hover" id="<?php print $moduleKey; ?>_update" href="modules.php?ext=<?php print $moduleKey; ?>&loadmodule=<?php print $moduleKey; ?>_<?php print $moduleValue['version']; ?>&act=update" title="<?php fpLanguage::printLanguageConstant(LANG_MODULES_UPDATE); ?>">
                        <i class="fa fa-refresh"></i>
                    </a>
                    <?php endif; ?>   
                <?php endif; ?>
                </td>
            </tr>
            <tr class="module-manager-list-row2 <?php if(isset($moduleList['locallist'][$moduleKey]['updateav'])) : ?>module-manager-list-update<?php endif; ?>">
                <td colspan="5" class="tdcontent module-manager-list-td7 <?php if(isset($moduleList['locallist'][$moduleKey]['updateav'])) : ?>important-notice-text<?php endif; ?>">
                    <?php print $moduleValue['description']; ?>
                </td>
            </tr>
            <tr class="module-manager-list-row3">
                <td colspan="6"></td>
            </tr>            
        <?php endforeach; ?>
        </table>
    </div>
    
    <?php if(fpSecurity::checkPermissions('moduleinstall')) : ?>
    <div id="tabs-modules-upload">
        <form action="modules.php" method="POST" enctype="multipart/form-data">
            <div class="fileInputList">
                <input type="file" name="modulefile" size="50" maxlength="255" class="fileinput fp-ui-button">
            </div>
            
            <div class="fileInputFormButtons fp-editor-buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
                <button id="start-upload-input-old" type="submit" name="sbmmodulefile" class="btnloader"><?php fpLanguage::printLanguageConstant(LANG_EDITOR_UPLOADPIC); ?></button>
            </div>            
        </form>
    </div>
    <?php endif; ?>
</div>