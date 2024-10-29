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

//===========================================================================
// Create AFLinker DB tables
function create_database_tables ()
{

   // Create database tables if not exist
   global $wpdb;

   $af_settings_table_name = AF_SETTINGS_TABLE;
   $af_settings2_table_name = AF_SETTINGS2_TABLE;
   $af_redir_table_name = AF_REDIRECTS_TABLE;
   $af_kw_table_name    = AF_KEYWORDS_TABLE;

   if($wpdb->get_var("SHOW TABLES LIKE '$af_settings_table_name'") != $af_settings_table_name)
      $b_first_time = TRUE;
   else
      $b_first_time = FALSE;

   // Create settings table
   $query = "CREATE TABLE IF NOT EXISTS `$af_settings_table_name` (
      `id` bigint(20) NOT NULL AUTO_INCREMENT,
      `key` varchar(32) NOT NULL,
      `value` varchar(1024) NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `key` (`key`)
      )";
   $wpdb->query ($query);

   // Create redirects table
   $query = "CREATE TABLE IF NOT EXISTS `$af_redir_table_name` (
      `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
      `name` VARCHAR( 80 ) NOT NULL ,
      `source_url` VARCHAR( 512 ) NOT NULL ,
      `real_dest_url` VARCHAR( 512 ) NOT NULL ,
      `spider_dest_url` VARCHAR( 512 ) NOT NULL ,
      `title` VARCHAR( 200 ) NOT NULL
      )";
   $wpdb->query ($query);

   // Create keywords table
   $query = "CREATE TABLE IF NOT EXISTS `$af_kw_table_name` (
      `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
      `pattern` VARCHAR( 128 ) NOT NULL ,
      `is_regex` SMALLINT NOT NULL DEFAULT '0' COMMENT '1 or 0. 1=regex, 0=plain text',
      `order` INT NOT NULL DEFAULT '-1',
      `redirect_id` BIGINT NOT NULL COMMENT 'index in AF_redirects table'
      )";
   $wpdb->query ($query);


   $query = "CREATE TABLE IF NOT EXISTS `$af_settings2_table_name` (
      `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
      `pid` INT NOT NULL ,
      `value` VARCHAR( 10240 ) NOT NULL ,
      UNIQUE (`pid`)
      )";
   $wpdb->query ($query);

   if ($b_first_time)
      {
      // If first time - populate tables with default values
      // Note: use $wpdb->escape('...'); to sanitize inputs

      $query = "INSERT INTO `$af_settings_table_name` (`key`, `value`)
         VALUES
         ('ids_to_ignore', ''),
         ('redirect_code', '301'),
         ('max_links_count', '-1'),
         ('link_target_type', '_blank'),
         ('enable_ga_tracking', ''),
         ('link_custom_info', ''),
         ('add_rel_nofollow', '1'),
         ('process_images', '1'),
         ('google_slap_immune', '1'),
         ('force_non_aff_urls', 0)";

      $wpdb->query ($query);

      $query = "INSERT INTO `$af_redir_table_name` (`name` ,`source_url`,`real_dest_url`,`spider_dest_url`,`title`)
         VALUES ('Hostgator', '/goto/hosting', 'http://secure.hostgator.com/cgi-bin/affiliates/clickthru.cgi?id=gesman', 'http://www.hostgator.com/', 'Reliable Webhosting Service'
         )";
      $wpdb->query ($query);

      // $wpdb->num_rows – Count of the number of rows returned by the last query (useful in SELECT queries)
      // $wpdb->insert_id — ID of the AUTO_INCREMENT value of the last query (useful in INSERT queries)
      // $wpdb->rows_affected – Count of the number of rows affected by the last query (useful in INSERT, UPDATE and DELETE queries)

      $data1 = addslashes ('web\\s*hosting\\s+service');
      $query = "INSERT INTO `$af_kw_table_name` (`pattern` ,`is_regex` ,`redirect_id`)
         VALUES ('best webhost', '0', '{$wpdb->insert_id}'), ('$data1', '1', '{$wpdb->insert_id}'
         )";
      $wpdb->query ($query);
      }
}
//===========================================================================

//===========================================================================
// Returns assoc array of global settings
function load_global_settings ()
{
   global $wpdb;

   $query = "SELECT * FROM `" . AF_SETTINGS_TABLE . "`";
   $af_settings_tmp  = $wpdb->get_results($query, ARRAY_A);

   // Repack global settings into single assoc array.
   $af_settings = array();
   foreach ($af_settings_tmp as $af_setting)
      $af_settings[$af_setting['key']] = stripslashes($af_setting['value']);

   return ($af_settings);
}
//===========================================================================

//===========================================================================
// Update k=>v of a single global setting in DB
function update_global_setting ($key, $value)
{
   global $wpdb;

   $query = "UPDATE `" . AF_SETTINGS_TABLE . "` SET `value` = '$value' WHERE `key`='" . addslashes(stripslashes(html_entity_decode($key))) . "'";
   $RetCode = $wpdb->query ($query);  // The function returns an integer corresponding to the number of rows affected/selected, FALSE on error.

   if (!$RetCode)
      {
      $query = "INSERT INTO `" . AF_SETTINGS_TABLE . "` (`key`, `value`) VALUES ('$key', '$value')";
      $RetCode = $wpdb->query ($query);
      }
}
//===========================================================================

//===========================================================================
// Do admin panel business, assemble and output admin page HTML
function render_admin_html ()
{
   global $wpdb;

   //---------------------------------------
   // Load AFLinker data from database
   $af_settings  = load_global_settings();
   $query = "SELECT * FROM `" . AF_REDIRECTS_TABLE . "`";
   $af_redirects = $wpdb->get_results($query, ARRAY_A);
   $query = "SELECT * FROM `" . AF_KEYWORDS_TABLE . "` ORDER BY `order` ASC, LENGTH(`pattern`) DESC";
   $af_keywords  = $wpdb->get_results($query, ARRAY_A);

   if (!is_array($af_redirects))
      $af_redirects = array();
   if (!is_array($af_keywords))
      $af_keywords = array();
   //---------------------------------------

   $admin_menu = get_admin_menu_html_template();

   // Start "brushing" admin_data...

   //---------------------------------------
   // Substitute few variables
   $admin_menu = preg_replace ("|\{\{\{SERVER__REQUEST_URI\}\}\}|", $_SERVER['REQUEST_URI'], $admin_menu);

   $aflinker_logo_url  = WP_PLUGIN_URL . '/af-linker/aflinker_logo.png';
   $admin_menu = preg_replace ("|\{\{\{AFLINKER_LOGO_URL\}\}\}|", $aflinker_logo_url, $admin_menu);
   $admin_menu = preg_replace ("|\{\{\{BLOG_URL\}\}\}|", rtrim(get_bloginfo ('wpurl'), '/'), $admin_menu);
   //---------------------------------------

   //---------------------------------------
   // Prepare html to fill in 'KW_ROW_REDIRECT_OPTIONS'.
   // It will be placed into '$all_redirect_options_for_any_kw'. Please note that none of rows marked as "selected".
   $all_redirect_options_for_any_kw = "";
   $single_redirect_option_for_kw_pattern = extract_special_tag_data ('KW_ROW_REDIRECT_OPTIONS', $admin_menu);
   foreach ($af_redirects as $af_redirect)
      {
      $curr_option_row = $single_redirect_option_for_kw_pattern;
      $curr_option_row = preg_replace ("|\{\{\{REDIRECT_ID\}\}\}|", $af_redirect['id'], $curr_option_row);

      $redirect_name_url = $af_redirect['name'] . ": " . $af_redirect['real_dest_url'];
      if (strlen ($redirect_name_url)>80)
         $redirect_name_url = substr ($redirect_name_url, 0, 38) . "..." . substr ($redirect_name_url, -38);

      $curr_option_row = preg_replace ("|\{\{\{REDIRECT_NAME_URL\}\}\}|", $redirect_name_url, $curr_option_row);
      $all_redirect_options_for_any_kw .= $curr_option_row;
      }
   // Eventually to follow with
   // $admin_menu = replace_special_tag_data ('KW_ROW_REDIRECT_OPTIONS', $admin_menu, $all_redirect_options_for_any_kw);
   //---------------------------------------

   //---------------------------------------
   // Fill in current redirects rows
   $all_redirect_rows = "";
   // Extract pattern
   $single_redirect_row_pattern = extract_special_tag_data ('CURRENT_REDIRECTS_ROWS', $admin_menu);
   foreach ($af_redirects as $af_redirect)
      {
      $curr_redirect_row = $single_redirect_row_pattern;
      $curr_redirect_row = preg_replace ("|\{\{\{CURR_REDIRECT_ID\}\}\}|",       $af_redirect['id'],              $curr_redirect_row);
      $curr_redirect_row = preg_replace ("|\{\{\{CURR_REDIRECT_NAME\}\}\}|",     $af_redirect['name'],            $curr_redirect_row);
      $curr_redirect_row = preg_replace ("|\{\{\{CURR_REDIRECT_SRC_URL\}\}\}|",  $af_redirect['source_url'],      $curr_redirect_row);
      $curr_redirect_row = preg_replace ("|\{\{\{CURR_REDIRECT_DEST_URL\}\}\}|", $af_redirect['real_dest_url'],   $curr_redirect_row);
      $curr_redirect_row = preg_replace ("|\{\{\{CURR_REDIRECT_SPDR_URL\}\}\}|", $af_redirect['spider_dest_url'], $curr_redirect_row);
      $curr_redirect_row = preg_replace ("|\{\{\{CURR_REDIRECT_TITLE\}\}\}|",    $af_redirect['title'],           $curr_redirect_row);
      $all_redirect_rows .= $curr_redirect_row;
      }
   $admin_menu = replace_special_tag_data ('CURRENT_REDIRECTS_ROWS', $admin_menu, $all_redirect_rows);
   //---------------------------------------

   //---------------------------------------
   // Fill in current keyword rows
   $all_keyword_rows = "";
   // Extract main keyword pattern
   $single_keyword_row_pattern = extract_special_tag_data ('CURRENT_KEYWORDS_ROWS', $admin_menu);
   foreach ($af_keywords as $af_keyword)
      {
      $curr_keyword_row = $single_keyword_row_pattern;
      $curr_keyword_row = preg_replace ("|\{\{\{CURR_KEYW_ID\}\}\}|",               $af_keyword['id'],      $curr_keyword_row);
      $curr_keyword_row = preg_replace ("|\{\{\{CURR_KEYW_PATTERN\}\}\}|",          $af_keyword['pattern'], $curr_keyword_row);
      $curr_keyword_row = preg_replace ("|\{\{\{CURR_KEYW_CHECKED_IF_REGEX\}\}\}|", $af_keyword['is_regex']?'checked="checked"':'', $curr_keyword_row);
      $curr_keyword_row = preg_replace ("|\{\{\{CURR_KEYW_ORDER\}\}\}|",            $af_keyword['order'],   $curr_keyword_row);

      // Mark proper redirect option as 'selected'
      $plain_pattern = '<option value="'. $af_keyword['redirect_id'] . '"';
      $regex_pattern = preg_quote ($plain_pattern);
      $all_redirect_options_for_THIS_kw = preg_replace ("|$regex_pattern|", $plain_pattern . ' selected="selected"', $all_redirect_options_for_any_kw);
      $curr_keyword_row = replace_special_tag_data ('KW_ROW_REDIRECT_OPTIONS', $curr_keyword_row, $all_redirect_options_for_THIS_kw);

      $all_keyword_rows .= $curr_keyword_row;
      }
   $admin_menu = replace_special_tag_data ('CURRENT_KEYWORDS_ROWS', $admin_menu, $all_keyword_rows);
   //---------------------------------------

   //---------------------------------------
   // Do-fill redirect options for "enter new keyword pattern"
   $admin_menu = replace_special_tag_data ('KW_ROW_REDIRECT_OPTIONS', $admin_menu, $all_redirect_options_for_any_kw);
   //---------------------------------------

   //---------------------------------------
   // Fill-in global settings
   foreach ($af_settings as $k=>$v)
      $af_settings_html[$k] = htmlentities($v);   // Show stuff in HTML-friendly format

   //---------------------------------------
   // Set license-specific substitutions.

   $aflinker_edition = AF_gbh (TRUE);

   switch ($aflinker_edition)
      {
      case 'pu':
         $admin_menu = preg_replace ('|\{\{\{AFLINKER_EDITION\}\}\}|', 'Professional Unlimited <span style="color:white;background-color:black;border:1px solid red;padding:1px 5px;">Black Hat</span> Edition', $admin_menu);
         break;

      case 'free-bh':
         $admin_menu = preg_replace ('|\{\{\{AFLINKER_EDITION\}\}\}|', 'Standard <span style="color:white;background-color:black;border:1px solid red;padding:1px 5px;">Black Hat</span> Edition', $admin_menu);
         break;

      case 'free-wh':
      default:
         $admin_menu = preg_replace ('|\{\{\{AFLINKER_EDITION\}\}\}|', 'Standard <b>White Hat</b> Edition', $admin_menu);
         break;
      }

   if ($aflinker_edition == 'free-wh')
      {
      $admin_menu = preg_replace ('|\{\{\{GLOB_DISABLED_IF_WHITE_HAT\}\}\}|',  'disabled="disabled"', $admin_menu);

      $white_hat_promo_message=<<<WWW
    <div align="center" style="width:75%;margin:5px;padding:3px;border:3px solid red;color:red;background-color:#ffff00;">
      <b><span style="font-size:120%;">Note: Number of essential features are disabled in White Hat version</span>
      <br /><a href="http://www.aflinker.com/download-aflinker-black-hat">To enable all features please download Black Hat license of AFLinker here</a></b>
    </div>
WWW;
      $admin_menu = replace_special_tag_data ('GLOB_WHITE_HAT_MESSAGE', $admin_menu, $white_hat_promo_message);
      }
   else
      {
      $admin_menu = preg_replace ('|\{\{\{GLOB_DISABLED_IF_WHITE_HAT\}\}\}|',  '', $admin_menu);
      $admin_menu = replace_special_tag_data ('GLOB_WHITE_HAT_MESSAGE', $admin_menu, "");
      }
   //---------------------------------------

   $admin_menu = preg_replace ('|\{\{\{AFLINKER_VERSION\}\}\}|', AFLINKER_VERSION, $admin_menu);

   $admin_menu = preg_replace ('|\{\{\{GLOB_IDS_TO_IGNORE\}\}\}|', $af_settings_html['ids_to_ignore'], $admin_menu);

   // Process {{{GLOB_REDIRECT_CODE_OPTIONS}}}
   $glob_redirect_code_options = extract_special_tag_data ('GLOB_REDIRECT_CODE_OPTIONS', $admin_menu);
   $plain_pattern = '<option value="'. $af_settings_html['redirect_code'] . '"';
   $regex_pattern = preg_quote ($plain_pattern);
   $glob_redirect_code_options = preg_replace ("|$regex_pattern|", $plain_pattern . ' selected="selected"', $glob_redirect_code_options);
   $admin_menu = replace_special_tag_data ('GLOB_REDIRECT_CODE_OPTIONS', $admin_menu, $glob_redirect_code_options);
   //------------------

   $admin_menu = preg_replace ('|\{\{\{GLOB_MAX_LINKS_COUNT\}\}\}|', $af_settings_html['max_links_count'], $admin_menu);

   // Process {{{GLOB_LINK_TARGET_TYPE_OPTIONS}}}
   $glob_link_target_type_options = extract_special_tag_data ('GLOB_LINK_TARGET_TYPE_OPTIONS', $admin_menu);
   $plain_pattern = '<option value="'. $af_settings_html['link_target_type'] . '"';
   $regex_pattern = preg_quote ($plain_pattern);
   $glob_link_target_type_options = preg_replace ("|$regex_pattern|", $plain_pattern . ' selected="selected"', $glob_link_target_type_options);
   $admin_menu = replace_special_tag_data ('GLOB_LINK_TARGET_TYPE_OPTIONS', $admin_menu, $glob_link_target_type_options);
   //------------------

   $admin_menu = preg_replace ('|\{\{\{GLOB_CHECKED_IF_ENABLE_GA_TRACKING\}\}\}|',  $af_settings_html['enable_ga_tracking']?'checked="checked"':'',   $admin_menu);
   $admin_menu = preg_replace ('|\{\{\{GLOB_LINK_CUSTOM_INFO\}\}\}|',               $af_settings_html['link_custom_info'], $admin_menu);
   $admin_menu = preg_replace ('|\{\{\{GLOB_CHECKED_IF_ADD_REL_NOFOLLOW\}\}\}|',    $af_settings_html['add_rel_nofollow']?'checked="checked"':'',     $admin_menu);
   $admin_menu = preg_replace ('|\{\{\{GLOB_CHECKED_IF_PROCESS_IMAGES\}\}\}|',      $af_settings_html['process_images']?'checked="checked"':'',       $admin_menu);
   $admin_menu = preg_replace ('|\{\{\{GLOB_CHECKED_IF_GOOGLE_SLAP_IMMUNE\}\}\}|',  $af_settings_html['google_slap_immune']?'checked="checked"':'',   $admin_menu);
   $admin_menu = preg_replace ('|\{\{\{GLOB_CHECKED_IF_FORCE_NON_AFF_URLS\}\}\}|',  $af_settings_html['force_non_aff_urls']?'checked="checked"':'',   $admin_menu);
   //---------------------------------------

   if (isset($_POST) && count($_POST))
      {
      ?> <div align="center" class="updated"><p><strong><?php _e("AFLinker plugin settings updated.", "AFLinker");?></strong></p></div> <?php
      }
   echo $admin_menu;


   //---------------------------------------
   // Load custom AFLinker extensions admin area
   //
   $AF_ext_dirname = @dirname(__FILE__) . '/extensions';
   $AF_ext_dirname = str_replace ('\\', '/', $AF_ext_dirname);
   $AF_dh = opendir ($AF_ext_dirname);
   if ($AF_dh)
      {
      while (($AF_found_file = readdir($AF_dh)) !== FALSE)
         {
         // Load main.cpp from any extension which directory is not named like 'Sample_'.
         if (is_dir($AF_ext_dirname . "/$AF_found_file") && $AF_found_file[0] != '.' && strcmp('YourExtensionSample', $AF_found_file) && strncmp ('Sample_', $AF_found_file, 7) && file_exists ($AF_ext_dirname . "/$AF_found_file/admin.php"))
            {
            include_once ($AF_ext_dirname . "/$AF_found_file/admin.php");
            }
         }
      closedir($AF_dh);
      }
   //---------------------------------------

}
//===========================================================================

//===========================================================================
//
// Returns anything in between <!-- {{{SPECIAL_TAG}}} --> .... <!-- {{{/SPECIAL_TAG}}} -->

function extract_special_tag_data ($bare_tag, $input_html)
{
   $start_tag = preg_quote('<!--') . '\s*' . preg_quote('{{{'  . $bare_tag . '}}}') . '\s*' . preg_quote('-->');
   $end_tag   = preg_quote('<!--') . '\s*' . preg_quote('{{{/' . $bare_tag . '}}}') . '\s*' . preg_quote('-->');
   if (!preg_match ("#$start_tag(.*?)$end_tag#s", $input_html, $matches))
      return "";

   return (trim($matches[1]));
}
//===========================================================================

//===========================================================================
//
// Returns  <!-- {{{SPECIAL_TAG}}} --> .... <!-- {{{/SPECIAL_TAG}}} -->
// replaced with '$replacement'.

function replace_special_tag_data ($bare_tag, $input_html, $replacement)
{
   $start_tag = preg_quote('<!--') . '\s*' . preg_quote('{{{'  . $bare_tag . '}}}') . '\s*' . preg_quote('-->');
   $end_tag   = preg_quote('<!--') . '\s*' . preg_quote('{{{/' . $bare_tag . '}}}') . '\s*' . preg_quote('-->');
   return (preg_replace ("#$start_tag".".*?"."$end_tag#s", $replacement, $input_html));
}
//===========================================================================

//===========================================================================
// Execute Add/Update redirects POST request
function add_or_update_redirects ()
{
   global $wpdb;

   // Update each existing redirect in dbase
   $af_redir_table_name = AF_REDIRECTS_TABLE;
   $af_kw_table_name    = AF_KEYWORDS_TABLE;

   if (is_array($_POST['arr_redirects']))
      {
      foreach ($_POST['arr_redirects'] as $redirect_id=>$raw_redirect_data)
         {
         // Sanitize inputs
         $clean_redirect_data = array();
         foreach ($raw_redirect_data as $k=>$v)
            $clean_redirect_data[$k] = $wpdb->escape($v);

         $query = "UPDATE `$af_redir_table_name` SET `name` = '{$clean_redirect_data['name']}',
            `source_url` = '{$clean_redirect_data['source_url']}',
            `real_dest_url` = '{$clean_redirect_data['real_dest_url']}',
            `spider_dest_url` = '{$clean_redirect_data['spider_dest_url']}',
            `title` = '{$clean_redirect_data['title']}' WHERE `$af_redir_table_name`.`id`=$redirect_id";
         $wpdb->query ($query);
         }
      }

   // Add new redirect if requested
   if (is_array($_POST['arr_redirects_new']))
      {
      foreach ($_POST['arr_redirects_new'] as $new_redirect)
         {
         // Make sure valid data is present
         // Valid data:
         // - source url must be present
         // - real dest url must be present OR source url is NOT a local URL (=> not a redirect).
         if ($new_redirect['source_url'] && ($new_redirect['real_dest_url'] || $new_redirect['source_url'][0] != '/'))
            {
            $query = "INSERT INTO `$af_redir_table_name`
               (`name`,`source_url`,`real_dest_url`,`spider_dest_url`,`title`) VALUES (
               '{$new_redirect['name']}', '{$new_redirect['source_url']}', '{$new_redirect['real_dest_url']}', '{$new_redirect['spider_dest_url']}', '{$new_redirect['title']}'
               )";
            $wpdb->query ($query);
            }
         }
      }
}
//===========================================================================

//===========================================================================
// Execute Delete redirects POST request
function delete_redirects ()
{
   global $wpdb;

   // Get list of redirect id's scheduled for deletion
   $af_redir_table_name = AF_REDIRECTS_TABLE;
   $af_kw_table_name    = AF_KEYWORDS_TABLE;

   $delete_ids = array();
   foreach ($_POST['arr_redirects'] as $redirect_id=>$redirect_data)
      {
      if (isset($redirect_data['delete']))
         $delete_ids[] = $redirect_id;
      }
   if (count($delete_ids))
      {
      $query = "DELETE FROM `$af_redir_table_name` WHERE `id` = " . implode (' OR `id` = ', $delete_ids);
      $wpdb->query ($query);

      // To maintain integrity of database we now need to set redirect_id=0 for all keywords that had deleted redirect_id's
      $query = "UPDATE `$af_kw_table_name` SET `redirect_id` = '0' WHERE `redirect_id` = " . implode (' OR `redirect_id` = ', $delete_ids);
      $wpdb->query ($query);
      }
}
//===========================================================================

//===========================================================================
// Execute Add/Update keywords POST request
function add_or_update_keywords ()
{
   global $wpdb;

   // Update each existing keyword in dbase
   $af_redir_table_name = AF_REDIRECTS_TABLE;
   $af_kw_table_name    = AF_KEYWORDS_TABLE;

   if (is_array($_POST['arr_keywords']))
      {
      foreach ($_POST['arr_keywords'] as $keyword_id=>$raw_keyword_data)
         {
         if (isset($raw_keyword_data['is_regex']))
            $is_regex = '1';
         else
            $is_regex = '0';
         $query = "UPDATE `$af_kw_table_name` SET
            `pattern` = '" . $wpdb->escape($raw_keyword_data['pattern']) . "',
            `is_regex` = '$is_regex',
            `order` = '{$raw_keyword_data['order']}',
            `redirect_id` = '{$raw_keyword_data['redirect_id']}'
            WHERE `$af_kw_table_name`.`id`=$keyword_id";
         $wpdb->query ($query);
         }
      }

   // Add new keyword if requested
   if (is_array($_POST['arr_keywords_new']))
      {
      foreach ($_POST['arr_keywords_new'] as $new_keyword)
         {
         // Make sure valid data is present
         if ($new_keyword['pattern'])
            {
            $data1 = addslashes ($new_keyword['pattern']);
            $query = "INSERT INTO `$af_kw_table_name`
               (`pattern`,`is_regex`,`order`,`redirect_id`) VALUES (
               '$data1', '{$new_keyword['is_regex']}', '{$new_keyword['order']}', '{$new_keyword['redirect_id']}'
               )";
            $wpdb->query ($query);
            }
         }
      }
}
//===========================================================================

//===========================================================================
// Execute Delete keywords POST request
function delete_keywords ()
{
   global $wpdb;

   // Get list of keyword id's scheduled for deletion
   $af_redir_table_name = AF_REDIRECTS_TABLE;
   $af_kw_table_name    = AF_KEYWORDS_TABLE;

   $delete_ids = array();
   if (is_array($_POST['arr_keywords']))
      {
      foreach ($_POST['arr_keywords'] as $keyword_id=>$keyword_data)
         {
         if (isset($keyword_data['delete']))
            $delete_ids[] = $keyword_id;
         }
      }

   if (count($delete_ids))
      {
      $query = "DELETE FROM `$af_kw_table_name` WHERE `id` = " . implode (' OR `id` = ', $delete_ids);
      $wpdb->query ($query);
      }
}
//===========================================================================

//===========================================================================
function update_global_settings ()
{
   foreach ($_POST['global_settings'] as $global_setting_key=>$global_setting_value)
      update_global_setting ($global_setting_key, $global_setting_value);

   if (!isset ($_POST['global_settings']['enable_ga_tracking']))
      update_global_setting ('enable_ga_tracking', 0);

   if (!isset ($_POST['global_settings']['add_rel_nofollow']))
      update_global_setting ('add_rel_nofollow', 0);

   if (!isset ($_POST['global_settings']['process_images']))
      update_global_setting ('process_images', 0);

   if (!isset ($_POST['global_settings']['google_slap_immune']))
      update_global_setting ('google_slap_immune', 0);

   if (!isset ($_POST['global_settings']['force_non_aff_urls']))
      update_global_setting ('force_non_aff_urls', 0);
}
//===========================================================================

//===========================================================================
function visit_is_search_engine_spider()
{
   return preg_match ('#(slurp|bot|sp[iy]der|scrub(by|the)|crawl(er|ing|@)|yandex)#i', $_SERVER['HTTP_USER_AGENT']);
}
//===========================================================================

//===========================================================================
function AF_log_event ($filename, $linenum, $message, $extra_text="")
{
   $log_filename   = dirname(__FILE__) . '/__log.php';
   $logfile_header = '<?php header("Location: /"); exit(); ?>' . "\r\n" . '/* =============== AFLinker LOG file =============== */' . "\r\n";
   $logfile_tail   = "\r\nEND";

   // Delete too long logfiles.
   if (@file_exists ($log_filename) && @filesize($log_filename)>1000000)
      unlink ($log_filename);

   $filename = basename ($filename);

   if (@file_exists ($log_filename))
      {
      // 'r+' non destructive R/W mode.
      $fhandle = @fopen ($log_filename, 'r+');
      if ($fhandle)
         @fseek ($fhandle, -strlen($logfile_tail), SEEK_END);
      }
   else
      {
      $fhandle = @fopen ($log_filename, 'w');
      if ($fhandle)
         @fwrite ($fhandle, $logfile_header);
      }

   if ($fhandle)
      {
      @fwrite ($fhandle, "\r\n// " . $_SERVER['REMOTE_ADDR'] . '(' . $_SERVER['REMOTE_PORT'] . ')' . ' -> ' . date("Y-m-d, G:i:s") . "|$filename($linenum)|: " . $message . ($extra_text?"\r\n//    Extra Data: $extra_text":"") . $logfile_tail);
      @fclose ($fhandle);
      }
}
//===========================================================================


?>