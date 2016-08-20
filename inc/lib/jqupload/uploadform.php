<form id="fileupload" action="http://<?php print $_SERVER["HTTP_HOST"].FP_ROOT_DIR ?>inc/lib/jqupload/server/index.php" method="POST" enctype="multipart/form-data">
    <div class="fileupload-buttonbar">
        <div class="fileupload-progress fade" style="display:none;margin:10px 0;">
            <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
            <div class="progress-extended">&nbsp;</div>
        </div>
    </div>    
    
    <table role="presentation" style="width:100%;margin-bottom:10px;"><tbody class="files"></tbody></table>
    
    <div class="fileupload-buttonbar fp-editor-buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
        <div class="fileupload-buttons">
            <span class="fileinput-button">
                <span><?php fpLanguage::printLanguageConstant(LANG_UPLOAD_JQU_ADDFL); ?></span>
                <input type="file" name="files[]" multiple>
            </span>
            <button type="submit" class="start"><?php fpLanguage::printLanguageConstant(LANG_UPLOAD_JQU_START); ?></button>
            <button type="reset" class="cancel"><?php fpLanguage::printLanguageConstant(LANG_UPLOAD_JQU_CANCL); ?></button>
            <span class="fileupload-process"></span>
        </div>
    </div>
</form>

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade news-list-row">
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error"></strong>
        </td>

        <td class="jqupload-row-buttons" style="width:100px;text-align:center;">
            {% if (!i && !o.options.autoUpload) { %}
                <button class="start jqupload-btn"><?php fpLanguage::printLanguageConstant(LANG_UPLOAD_JQU_START); ?></button>
            {% } %}
            {% if (!i) { %}
                <button class="cancel jqupload-btn"><?php fpLanguage::printLanguageConstant(LANG_UPLOAD_JQU_CANCL); ?></button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">

</script>

<!-- The Templates plugin is included to render the upload/download listings -->
<script src="http://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php print FP_ROOT_DIR ?>inc/lib/jqupload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php print FP_ROOT_DIR ?>inc/lib/jqupload/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="<?php print FP_ROOT_DIR ?>inc/lib/jqupload/js/jquery.fileupload-process.js"></script>
<!-- The File Upload validation plugin -->
<script src="<?php print FP_ROOT_DIR ?>inc/lib/jqupload/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="<?php print FP_ROOT_DIR ?>inc/lib/jqupload/js/jquery.fileupload-ui.js"></script>
<!-- The File Upload jQuery UI plugin -->
<script src="<?php print FP_ROOT_DIR ?>inc/lib/jqupload/js/jquery.fileupload-jquery-ui.js"></script>
<!-- The main application script -->
<script src="<?php print FP_ROOT_DIR ?>inc/lib/jqupload/js/main.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="<?php print FP_ROOT_DIR ?>inc/lib/jqupload/js/cors/jquery.xdr-transport.js"></script>
<![endif]-->