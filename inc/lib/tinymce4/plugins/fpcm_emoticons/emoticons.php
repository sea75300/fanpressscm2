<?php
    define('NO_HEADER','');
    require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))).'/inc/acpcommon.php';
    
    $smilieRows  = $fpFileSystem->getSmilies();   
    
    $count = 0;
?>
<div role="presentation">
    <table class="tinymce_fpcm_emoticons">
        <tr>
            
        

        <?php foreach($smilieRows AS $smilieRow) : ?>
        <?php
            $count++;
        
            $fileInfo   = getimagesize(FPBASEDIR."/img/smilies/".$smilieRow->sml_filename);
            $code       = fpSecurity::Filter4($smilieRow->sml_code);
        ?>

            <td style="width:30px;height:24px;">
                <img <?php print $fileInfo[3]; ?> src="<?php print FP_ROOT_DIR."img/smilies/".$smilieRow->sml_filename; ?>" border="0" title="<?php print $code; ?>" alt="<?php print $code; ?>">
            </td>
            
            <?php if($count == 8) : ?>
                </tr><tr>
                <?php $count = 0; ?>
            <?php endif; ?>

        <?php endforeach; ?>          
        </tr>
    </table>
</div>    
