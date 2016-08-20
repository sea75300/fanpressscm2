<?php
    $tabsHead       = "";
    $tabsContent    = "";
    foreach ($customeFiles as $key => $value) {        
        $tabsHead       .= "<li><a href=\"#tabs-$key\">$key</a></li>\n";
        $tabsContent    .= "<div id=\"tabs-$key\"><p><textarea class=\"template_editor\" rows=\"15\" cols=\"70\" name=\"files[".str_replace('.', '_', $key)."]\">\n$value\n</textarea></p></div>\n";
    }    
?>

    <form method="post">
        <div id="tabsGeneral">
            <ul><?php print $tabsHead;?></ul>
            <?php print $tabsContent; ?>
        </div>
        <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
            <button type="submit" id="sbmbtnsave" class="btnloader fp-ui-button" name="btncfesave"><?php fpLanguage::printSave(); ?></button>
        </div>
    </form>



