<?php
/*
 This file (main.php) is an initialization point for your custom AFLinker extension.
 If it exists - it will be loaded via include_once() at plugin initalization time.
 See: AFLinker_head*.php and ext_manager.php files
 See readme.txt file for more information.
*/

add_filter ('the_content', 'MY_BEFORE_the_content', 1); // Priority = 1 => this filter will run before AFLinker's filter. All AFLinker's filters are run at priority = 2.
add_filter ('the_content', 'MY_AFTER_the_content',  3); // Priority = 3 => this filter will run after AFLinker will process content.

// My custom content filter function to be run before AFLinker's 'the_content' filter.
function MY_BEFORE_the_content ($content)
{
return $content . '<br /><b>My Before filter was here</b>';
}

// My custom content filter function to be run after AFLinker's 'the_content' filter.
function MY_AFTER_the_content ($content)
{
return $content . '<br /><b>My After filter was here</b>';
}

?>