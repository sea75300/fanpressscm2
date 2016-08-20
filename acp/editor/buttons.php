<?php if(!defined("LOGGED_IN_USER")) { die(); } ?>
<div style="display:none;" class="ui-widget-content ui-corner-all ui-state-normal fp-dialog" id="fp-extended-dialog">
    <h3>
        <?php fpLanguage::printExtended(); ?>
        <button class="fp-dialog-close"><?php fpLanguage::printClose(); ?></button>
    </h3>

    <table style="width: 100%;">
        <?php if(!isset($_GET["fn"]) || !isset($_GET["nid"])) : ?>
        <tr>
            <td class="dialog-label">
                <label><i class="fa fa-clock-o"></i> <?php fpLanguage::printLanguageConstant(LANG_EDITOR_POSTPONETO); ?></label>
            </td>
            <td>
                <input style="float:left;" id="openPostponInput" type="checkbox" name="postponeto[checked]">
            </td>
        </tr>
        <tr id="postponeinput">
            <td colspan="2">
                <table class="fp-ui-options">
                    <tr>
                        <td>
                            <input id="postponeinputpicker" type="text" value="<?php print date('d.m.Y'); ?>" name="postponeto[date]">
                            <input class="fp-ui-spinner-hour" type="text" value="<?php print date('H'); ?>" name="postponeto[hour]">
                            <input class="fp-ui-spinner-minutes" type="text" value="<?php print date('i'); ?>" name="postponeto[minute]">
                        </td>
                    </tr>                    
                </table>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <?php
                $preview = $fpNewsPost->isPreview();            
                $pinnews = $fpNewsPost->isPinned();
                
                $enacmts = (int) (isset($_POST['commentenabled'])) ? $_POST['commentenabled'] : $fpNewsPost->getCommentsActive();
                
                $archived = (int) $fpNewsPost->isArchived();
            ?>                     
            <td class="dialog-label">
                <label class="pinNews"><i class="fa fa-thumb-tack fa-rotate-90"></i> <?php fpLanguage::printLanguageConstant(LANG_EDIT_ISPINNED); ?>:</label>                             
            </td>
            <td >
                <select class="chosen-select-dialog pinNews" name="pinnews" <?php if($archived) : ?>disabled<?php endif; ?>>
                    <option value="0" <?php if($pinnews == 0) : ?>selected="selected"<?php endif; ?>><?php fpLanguage::printNo(); ?></option>
                    <option value="1" <?php if($pinnews == 1) : ?>selected="selected"<?php endif; ?>><?php fpLanguage::printYes(); ?></option>
                </select>
                <?php if($archived) : ?><input type="hidden" value="0" name="pinnews"><?php endif; ?>                           
            </td>
        </tr>
        <?php if(fpConfig::get('comments_enabled_global')) : ?>
        <tr>
            <td class="dialog-label">
                <label><i class="fa fa-comment-o"></i> <?php fpLanguage::printLanguageConstant(LANG_WRITE_COMMENTSDISABLED); ?>:</label>    
            </td>
            <td >
                <select class="chosen-select-dialog" name="commentenabled">
                    <option value="1" <?php if($enacmts == 1) : ?>selected="selected"<?php endif; ?>><?php fpLanguage::printNo(); ?></option>
                    <option value="0" <?php if($enacmts == 0) : ?>selected="selected"<?php endif; ?>><?php fpLanguage::printYes(); ?></option>
                </select>                         
            </td>
        </tr>
        <?php else : ?>
        <input type="hidden" name="commentenabled" value="0">
        <?php endif; ?>

        <?php if ($iseditmode == 1) : ?>  
        <tr>
            <?php if($preview == 0) : ?>
            <td class="dialog-label">
                <label><i class="fa fa-archive"></i> <?php fpLanguage::printLanguageConstant(LANG_EDIT_INARCHIVE); ?>:</label>
            </td>
            <td >
                <select class="chosen-select-dialog" name="toarchive" <?php if($archived) : ?>disabled<?php endif; ?>>
                    <option id="archivedN" value="0" <?php if(!$archived) : ?>selected="selected"<?php endif; ?>><?php fpLanguage::printNo(); ?></option>
                    <option id="archivedY" value="1" <?php if($archived) : ?>selected="selected"<?php endif; ?>><?php fpLanguage::printYes(); ?></option>
                </select>                          
            </td>
            <?php endif; ?>                     
        </tr>        
        <?php endif; ?>

        <?php if(($preview == 0 && $iseditmode != 1) || ($iseditmode == 1 && $preview == 1)) : ?>
        <tr>
            <td class="dialog-label">
                <label><i class="fa fa-file-text-o"></i> <?php fpLanguage::printLanguageConstant(LANG_EDITOR_PREVIEW); ?>:</label>
            </td>
            <td >
                <select class="chosen-select-dialog" name="sbmnp_prev">
                    <option id="previewN" value="0" <?php if($preview == 0) : ?>selected="selected"<?php endif; ?>><?php fpLanguage::printNo(); ?></option>
                    <option id="previewY" value="1" <?php if($preview == 1) : ?>selected="selected"<?php endif; ?>><?php fpLanguage::printYes(); ?></option>
                </select>
            </td>
        </tr>
        <?php endif; ?>

        <?php fpModuleEventsAcp::runOnAddEditorExtendedField(); ?>
        
    </table>
</div>

<?php if ($iseditmode == 1) : ?>
    <?php if($archived || $preview != 0) : ?><input type="hidden" value="<?php print $archived; ?>" name="toarchive"><?php endif; ?>
    <?php if($fpNewsPost->isPreview() == 2) : ?><input type="hidden" value="2" name="sbmnp_prev"><?php endif; ?>           
<?php endif; ?>

<?php if(!isset($_GET['showrev'])) : ?>    
<div class="fp-editor-buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">     
    <button id="btnextmenu" class="fp-ui-button" name="extbtn"><?php fpLanguage::printExtended(); ?></button>
    <button type="submit" id="sbmbtnsave" class="btnloader fp-ui-button" name="sbmbtnsave" title="Ctrl + S"><?php fpLanguage::printSave(); ?></button>
</div>
<script>var keyShortcutsEnabled = true;</script>
<?php else : ?>
<div class="fp-editor-buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
    <a href="editnews.php?fn=edit&nid=<?php print $fpNewsPost->getId(); ?>" class="btnloader fp-ui-button" ><?php fpLanguage::printLanguageConstant(LANG_EDITOR_BACKTOCURRENT); ?></a>
</div>
<script>var keyShortcutsEnabled = false;</script>
<?php endif; ?>