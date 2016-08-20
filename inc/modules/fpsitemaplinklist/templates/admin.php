    <form method="post">
        <div id="tabsGeneral">
            <ul>
                <li><a href="#fpcm-tabs-general"><?php print $tabsHead;?></a></li>
                <li><a href="#fpcm-tabs-linklist"><?php print $tabsListHead;?></a></li>                
            </ul>
            
            <div id="fpcm-tabs-general">
                <input type="text" name="xmlfilepath" value="<?php print $defaultPath; ?>" style="width:400px;">
            </div>
            
            <div id="fpcm-tabs-linklist">
                <p><?php print $tabsListCheck; ?></p>
                
                <?php foreach ($links as $key => $value) : ?>
                <p>                    
                    <input name="activelinks[]" id="activelink<?php print md5($key); ?>" type="checkbox" value="<?php print $value; ?>" <?php if(in_array($key, $active)) : ?>checked="checked"<?php endif; ?>>
                    <label for="activelink<?php print md5($key); ?>"><?php print $value; ?></label>
                </p>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
            <button type="submit" id="sbmbtnsave" class="btnloader fp-ui-button" name="btncfesave"><?php fpLanguage::printSave(); ?></button>
        </div>
    </form>