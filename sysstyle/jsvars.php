<script type="text/javascript">
    var jsFProotPath = '<?php print FP_ROOT_DIR; ?>';
    var jsFPdialogProfile = '<?php fpLanguage::printLanguageConstant(LANG_GLOBAL_QUICKLINKS); ?>';
    var jsFPdialogProfileOpen = '<?php fpLanguage::printLanguageConstant(LANG_SYSCFG_PROFILE); ?>';
    var jsFPdialogLogout = '<?php fpLanguage::printLanguageConstant(LANG_LOGOUT); ?>';
    var jsFPclose = '<?php fpLanguage::printLanguageConstant(LANG_GLOBAL_CLOSE); ?>';
    var availableDTMasks = <?php print FPOPTIONSDTMASKSAC; ?>;
    var jsSyseditor = '<?php print fpConfig::get('system_editor'); ?>';
    var jsFileManagerHeadline = '<?php fpLanguage::printLanguageConstant(LANG_UPLOAD_FILEMANAGER); ?>';
</script>