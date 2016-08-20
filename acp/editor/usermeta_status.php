<?php if(!defined("LOGGED_IN_USER")) { die(); } ?>
<span class="fp-news-status<?php if((isset($fpNewsPost) && $fpNewsPost->isPinned()) || (isset($row->is_pinned) && $row->is_pinned)) : ?>-1<?php endif; ?>" title="<?php fpLanguage::printLanguageConstant(LANG_EDIT_ISPINNED); ?>">
    <i class="fa fa-thumb-tack fa-rotate-90"></i>
</span>

<span class="fp-news-status<?php if((isset($fpNewsPost) && $fpNewsPost->isPreview() == 1) || (isset($row->ispreview) && $row->ispreview == 1)) : ?>-1<?php endif; ?>" class="fp-news-status<?php if((isset($fpNewsPost) && $fpNewsPost->isPinned()) || (isset($row->is_pinned) && $row->is_pinned)) : ?>-1<?php endif; ?>" title="<?php fpLanguage::printLanguageConstant(LANG_EDITOR_PREVIWBOX); ?>">
    <i class="fa fa-file-text-o"></i>
</span>

<span class="fp-news-status<?php if((isset($fpNewsPost) && $fpNewsPost->isPreview() == 2) || (isset($row->ispreview) && $row->ispreview == 2)) : ?>-1<?php endif; ?>" title="<?php fpLanguage::printLanguageConstant(LANG_WRITE_POSTPONE); ?>">
    <i class="fa fa-clock-o"></i>
</span>

<span class="fp-news-status<?php if((!fpConfig::get('comments_enabled_global') || (isset($fpNewsPost) && !$fpNewsPost->getCommentsActive()) || (isset($row->comments_active) && !$row->comments_active))) : ?>-1<?php endif; ?>" title="<?php fpLanguage::printLanguageConstant(LANG_WRITE_COMMENTSDISABLED); ?>">
    <i class="fa fa-comments-o"></i>
</span>

<?php if(!isset($showArchived) && !isset($showActive)) : ?>
<span class="fp-news-status<?php if(isset($fpNewsPost) && ($fpNewsPost->isArchived()) || (isset($row->is_archived) && $row->is_archived)) : ?>-1<?php endif; ?>" title="<?php fpLanguage::printLanguageConstant(LANG_EDIT_INARCHIVE); ?>">
    <i class="fa fa-archive"></i>
</span>
<?php endif; ?>

<?php if(isset($revTime) && $revTime) : ?>
<span class="fp-news-status-1" title="<?php fpLanguage::printLanguageConstant(LANG_EDIT_REVISION); ?>: <?php print date(fpConfig::get('timedate_mask'), $revTime); ?>">
    <i class="fa fa-history"></i>
</span>
<?php endif; ?>