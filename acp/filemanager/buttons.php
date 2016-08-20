<?php if(!defined("LOGGED_IN_USER")) { die(); } ?>
<select class="chosen-select chosen-select-bottom" id="filemngractions" name="filemngractions" style="width:250px;">
    <option value=""><?php fpLanguage::printLanguageConstant(LANG_EDIT_ACTION); ?></option>                                
<?php if(fpSecurity::checkPermissions ("newthumbs")) : ?>
    <option value="recrtthmbs"><?php fpLanguage::printLanguageConstant(LANG_EDITOR_NEWTHUMBS); ?></option>
<?php endif; ?>
<?php if(fpConfig::currentUser('usrlevel') == 1 || fpConfig::currentUser('usrlevel') == 2) : ?>
    <option value="rbldfindex"><?php fpLanguage::printLanguageConstant(LANG_EDITOR_REBUILDFILEINDEX); ?></option>
<?php endif; ?>
<?php if(fpSecurity::checkPermissions ("deletefiles")) : ?>
    <option value="filedelete"><?php fpLanguage::printLanguageConstant(LANG_GLOBAL_DELETE); ?></option>
<?php endif; ?>
</select>

<button type="submit" id="fp-filemgr-okbtn" name="sbmok" class="btnloader fp-ui-button"><?php fpLanguage::printOk(); ?></button>