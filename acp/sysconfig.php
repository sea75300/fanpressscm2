<?php
 /**
   * Optionen Hauptseite
   * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
   * @copyright 2011-2014
   *
   */
    require_once dirname(dirname(__FILE__)).'/inc/acpcommon.php';
  
    $module = isset($_GET["mod"]) ? $_GET["mod"] : null;

    switch($module) {
        case "system":
            $incpg = "sysconfig/options.php";
        break;
        case "ipbann":
            $incpg = "sysconfig/ipbann.php";
        break;      
        case "category":
            $incpg = "sysconfig/categories.php";
        break;    
        case "permissions":
            $incpg = "sysconfig/permissions.php";
        break;
        case "templates":
            $incpg = "sysconfig/templates.php";
        break;    
        case "smilies":
            $incpg = "sysconfig/smilies.php";
        break;  
        case "upload":    
            $incpg = "sysconfig/attachments.php";
        break;
        case "syslog":    
            $incpg = "sysconfig/usrlog.php";
        break;
        case "users":
            $incpg = "sysconfig/usrconfig.php";
        break;       
        default:
            $incpg = "sysconfig/profile.php";
        break;
    }
?>
<?php if(LOGGED_IN_USER) : ?>
    <div class="box box-fixed-margin" id="contentbox">
        <?php if($module != "modules" && !is_null($module)) : ?>
        <h1><?php fpLanguage::printLanguageConstant(LANG_OPTIONS); ?></h1>
        <?php fpModuleEventsAcp::runOnAddACPMessage(); ?>
        <?php endif; ?>

        <?php if($module != "modules" && !is_null($module)) : ?><div class="admin-content" id="admincontent"><?php endif; ?>
            
            <?php include $incpg; ?>
        <?php if($module != "modules" && !is_null($module)) : ?></div><?php endif; ?>
    </div>    
<?php
    else :
        fpMessages::showNoAccess(LANG_ERROR_NOACCESS);
    endif;
?>
<?php include FPBASEDIR."/sysstyle/sysfooter.php"; ?>