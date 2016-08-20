<?php if(!defined("LOGGED_IN_USER")) { die(); } ?>
<form method="post" action="sysconfig.php?mod=users">      
    <table width="100%" border="0">
        <tr>
            <td class="tdheadline" style="text-align:left;"><?php fpLanguage::printLanguageConstant(LANG_USR_ROLLS_NAME); ?></td>
            <td class="tdheadline" style="width:25px;"></td>         
        </tr>
  <?php
      $rolls = $fpUser->getUsrLevels();
      foreach($rolls AS $roll) :
  ?>
        <tr>      
            <td>
            <?php if($roll->id <= 3) : ?>
                <strong><?php print htmlspecialchars_decode($roll->leveltitle); ?></strong>
            <?php else : ?>
                <a href="sysconfig.php?mod=users&amp;fn=editroll&amp;id=<?php print $roll->id; ?>"><?php print htmlspecialchars_decode($roll->leveltitle); ?></a>                            
            <?php endif; ?>
            </td>
            <td class="tdtcontent"><input type="radio" name="delroll"  value="<?php print $roll->id; ?>" <?php if($roll->id <= 3) : ?>readonly="readonly"<?php endif; ?>></td>      
        </tr>      
  <?php endforeach; ?> 
    </table>
    <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
        <button type="submit" name="sbmdelroll" class="btnloader fp-ui-button"><?php fpLanguage::printLanguageConstant(LANG_GLOBAL_DELETE); ?></button>
    </div>              
</form>