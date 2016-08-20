<?php if(!defined("LOGGED_IN_USER")) { die(); } ?>
<div class="editor_cat">
    <?php $categorieRows = $fpNews->getCategories(true);  ?>

    <?php $cat_array = array(); ?>
    
    <?php if(isset($fpNewsPost))    { $cat_array = explode(";",$fpNewsPost->getCategory()); } ?>
    <?php if(isset($_POST['cat']))  { $cat_array = array_map('intval', $_POST['cat']); } ?>
    
    <div class="fp-ui-buttonset">
        <?php foreach($categorieRows AS $categorieRow) : ?>
            
        <?php if($categorieRow->minlevel >= fpConfig::currentUser('usrlevel')) : ?>
        <input type="checkbox" name="cat[]" value="<?php print $categorieRow->id; ?>" id="cat<?php print $categorieRow->id; ?>" <?php if(in_array($categorieRow->id,$cat_array)) : ?>checked="checked"<?php endif; ?>> <label for="cat<?php print $categorieRow->id; ?>"><?php print $categorieRow->catname; ?></label>
        <?php endif; ?>

        <?php endforeach; ?>        
    </div>    
</div>