<html>
    <head>
        <title>FanPress CM Module Repository</title>
        <meta http-equiv="Content-Language" content="de">
        <meta http-equiv="content-type" content= "text/html; charset=iso-8859-1">
        <meta name="robots" content="noindex, nofollow">
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.10.4/themes/redmond/jquery-ui.css">        
        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
    </head>
    <body id="body">
       
        <table class="fp-ui-options fp-module-manager">
            <tr>
                <th style="padding-bottom:0.8em;"><?php print $lang["key"]; ?></th>
                <th style="padding-bottom:0.8em;"><?php print $lang["name"]; ?></th>
                <th style="padding-bottom:0.8em;width:15%;"><?php print $lang["version"]; ?></th>
                <th style="padding-bottom:0.8em;width:15%;"><?php print $lang["minversion"]; ?></th>       
                <th style="padding-bottom:0.8em;width:15%;"></th>
            </tr>        
        <?php foreach($modulesServer AS $moduleKey => $module) : ?>
            <?php if($version < $module['minsysverion'] || $version > $module['maxsysverion']) continue; ?>
            <tr class="module-manager-list-row1">
                <td class="module-manager-list-td1"><i><?php print $moduleKey; ?></i></td>
                <td class="module-manager-list-td2"><b><?php print $module['name']; ?></b></td>
                <td class="module-manager-list-td4"><?php print $module['version']; ?></td>
                <td class="module-manager-list-td5"><?php print $module['minsysverion']; ?></td>
                <td class="module-manager-list-td6" rowspan="2">
                    <a class="fp-ui-button" id="<?php print $moduleKey; ?>_install" href="http://nobody-knows.org/updatepools/fanpress/modules/packages/<?php print $moduleKey; ?>_<?php print $module['version']; ?>.zip" title="<?php print $lang["download"]; ?>">
                        <?php print $lang["download"]; ?>
                    </a>  
                </td>
            </tr>
            <tr class="module-manager-list-row2">
                <td colspan="4" class="module-manager-list-td7">
                    <p><?php print $module['description']; ?></p>
                </td>
            </tr>
            <tr class="module-manager-list-row3">
                <td colspan="5"></td>
            </tr>             
        <?php endforeach; ?>
        </table>        
        
        <script type="text/javascript">
            jQuery('a').button({ icons: { primary: "ui-icon-transferthick-e-w" }, text: true });
        
        </script>
        
    </body>
</html>