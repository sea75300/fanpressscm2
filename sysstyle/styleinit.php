<?php if(!defined("LOGGED_IN_USER")) { die(); } ?>
<!DOCTYPE HTML>
<HTML>
    <head>
        <title>FanPress CM  <?php print FPSYSVERSION; ?> - <?php fpLanguage::printLanguageConstant(LANG_ACP_HEADLINE); ?></title>
        <meta http-equiv="Content-Language" content="de">
        <meta http-equiv="content-type" content= "text/html; charset=iso-8859-1">
        <meta name="robots" content="noindex, nofollow">  
        <link rel="shortcut icon" href="<?php print FP_ROOT_DIR ?>sysstyle/favicon.png" type="image/png" /> 

        <?php fpView::getStyleCssFiles(); ?>        
        <?php fpView::getStyleJsFiles(); ?>
    </head> 