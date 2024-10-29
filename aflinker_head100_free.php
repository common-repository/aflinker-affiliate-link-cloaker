<?php
/*
Plugin Name: AFLinker - Affiliate Link Cloaker, URL Shortener<br />SEO Affiliate Links Generator and Affiliate Redirects Manager.<br />Free Edition
Plugin URI: http://www.aflinker.com/
Version: 1.306
Author: AF Linker Team, http://www.aflinker.com/
Author URI: http://www.aflinker.com/
Description: Affiliate Link cloaker, SEO enhanced affiliate links generator, redirects manager and link clicks tracking system. AFLinker wraps relevant keyphrases within your posts, pages and RSS feeds into clickable links or clean, cloaked affiliate links - redirects. You define search patterns for relevant keywords and AFLinker will automatically wrap matching phrases and even images with clickable links of your choice. AFLinker gives you a choice to make your affiliate links totally invisible to visitors and even to search engines. With AFLinker you may safely build affiliate review and promotional sites immune from Google affiliate sites slap. AFLinker could also act as your own private URL shortener service for your own domain. AFLinker fully integrates with Google Analytics allowing you to track visitor clicks even on outgoing links.
*/

/* **************************************************************************
This software is provided "as is" without any express or implied warranties,
including, but not limited to, the implied warranties of merchantibility and
fitness for any purpose.
In no event shall the copyright owner, website owner or contributors be liable
for any direct, indirect, incidental, special, exemplary, or consequential
damages (including, but not limited to, procurement of substitute goods or services;
loss of use, data, rankings with any search engines, any penalties for usage of
this software or loss of profits; or business interruption) however caused and
on any theory of liability, whether in contract, strict liability, or
tort(including negligence or otherwise) arising in any way out of the use of
this software, even if advised of the possibility of such damage.
To request source code for AFLinker please contact http://www.aflinker.com/contact
************************************************************************** */


define('AFLINKER_VERSION',  '1.306');
define('AF_PLUGIN_FILENAME',  basename(__FILE__));
define('PLUGIN_TABLE_PREFIX', 'AF_');

require_once (dirname(__FILE__) . '/af_include_all.php');

register_activation_hook (__FILE__, 'AF_activated');

//===========================================================================
function AF_activated ()
{
   create_database_tables ();
}
//===========================================================================

?>
