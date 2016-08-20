<?php
    /**
     * Kommentare anzeigen
     * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
     * @copyright 2011-2014
     *
     */

    if(!defined("LOGGED_IN_USER")) { die(); }

    if(fpSecurity::checkPermissions ("editcomments")) :
?>
    <form method="post" action="<?php print FPBASEURLACP; ?>editnews.php?fn=edit&nid=<?php print fpSecurity::Filter1($_GET["nid"]); ?>">
        <?php $comments = $fpComment->getAllComments(1, (int) $_GET["nid"]); ?>
        <?php include FPBASEDIR.'/acp/comments/loadlist.php'; ?>
    </form>  
<?php endif; ?>