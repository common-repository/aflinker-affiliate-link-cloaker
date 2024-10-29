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
function get_admin_menu_html_template ()
{
$admin_menu =<<<MENU_HTML
<!-- ========== [AFLinker Admin Settings] ========== -->
<div align="center" style="margin:10px 12px 0;">
<div align="center" style="font-family: Georgia, 'Times New Roman', Times, serif;font-size:18px;margin:30px 0 12px;background-color:#b8d6fb;padding:4px;border:1px solid #00469c;">AFLinker<br />Automatic SEO-enhanced affiliate links generator, link cloaker and intelligent redirects manager</div>
<img src="{{{AFLINKER_LOGO_URL}}}" style="margin-bottom:10px;" />
<div align="center" style="width:50%;margin:4px;padding:2px;font-weight:bold;border:2px solid gray;background-color:#DDD;">AF Linker {{{AFLINKER_VERSION}}} [{{{AFLINKER_EDITION}}}]</div>
<!-- {{{GLOB_WHITE_HAT_MESSAGE}}} -->
<!-- {{{/GLOB_WHITE_HAT_MESSAGE}}} -->
<!-- Redirects Settings Table -->
<form action="{{{SERVER__REQUEST_URI}}}" method="post">
<table style="background-color:#555;" width="100%" border="1">
  <tr>
    <td style="font-family: Georgia, 'Times New Roman', Times, serif;font-size: 18px;margin:0 0 10px;background:#555;color:#FFF;line-height:32px;" colspan="6"><div align="center">Redirects Table</div></td>
  </tr>
  <tr>
    <td style="background-color:#B5FFA8" width="12%"><div align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;"><strong>Name</strong><br />
      for your reference only</div></td>
    <td style="background-color:#B5FFA8" width="22%"><div align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;"><strong>Source (pretty) URL<span style="color:red;">*</span></strong><br />
    relative to <b>but not including</b> this blog URL:<br /><span style="font-size:85%;font-weight:bold;">{{{BLOG_URL}}}</span></div></td>
    <td style="background-color:#B5FFA8" width="22%"><div align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;"><strong>Real Destination URL</strong> <br />
      destination (affiliate) link for real visitors</div></td>
    <td style="background-color:#B5FFA8" width="22%"><div align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;"><strong>Non-affiliate Destination URL</strong> <br />
      destination URL for search engine crawlers and spiders only<br />If empty: Real URL will be used</div></td>
    <td style="background-color:#B5FFA8" width="16%"><div align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;"><strong>Link &quot;title&quot;</strong><br />
    &quot;title&quot; attribute of a link</div></td>
    <td style="background-color:#FF9D9F;" width="6%"><div align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold;">Delete redirect</div></td>
  </tr>
  <tr>
    <td style="background-color:#FBFFB3;" colspan="6"><div align="center" style="font-family: Georgia, 'Times New Roman', Times, serif;font-size: 12px;padding:6px 0;"><strong>Currently defined Redirects:</strong></div></td>
  </tr>

  <!-- {{{CURRENT_REDIRECTS_ROWS}}} -->
  <tr>
    <td style="background-color:white;"><div><input name="arr_redirects[{{{CURR_REDIRECT_ID}}}][name]" type="text" id="textfield" value="{{{CURR_REDIRECT_NAME}}}" size="25" maxlength="80" />
    </div></td>
    <td style="background-color:white;"><div><input name="arr_redirects[{{{CURR_REDIRECT_ID}}}][source_url]" type="text" id="textfield" value="{{{CURR_REDIRECT_SRC_URL}}}" size="35" maxlength="512" />
    </div></td>
    <td style="background-color:white;"><div><input name="arr_redirects[{{{CURR_REDIRECT_ID}}}][real_dest_url]" type="text" id="textfield" value="{{{CURR_REDIRECT_DEST_URL}}}" size="35" maxlength="512" />
    </div></td>
    <td style="background-color:white;"><div><input name="arr_redirects[{{{CURR_REDIRECT_ID}}}][spider_dest_url]" type="text" id="textfield" value="{{{CURR_REDIRECT_SPDR_URL}}}" size="35" maxlength="512" />
    </div></td>
    <td style="background-color:white;"><div><input name="arr_redirects[{{{CURR_REDIRECT_ID}}}][title]" type="text" id="textfield" value="{{{CURR_REDIRECT_TITLE}}}" size="25" maxlength="200" />
      </div></td>
      <td style="background-color:white;"><div align="center">
        <input name="arr_redirects[{{{CURR_REDIRECT_ID}}}][delete]" type="checkbox"  />
      </div></td>
  </tr>
  <!-- {{{/CURRENT_REDIRECTS_ROWS}}} -->

  <tr>
    <td  style="background-color:#FBFFB3;"colspan="6"><div align="center" style="font-family: Georgia, 'Times New Roman', Times, serif;font-size: 12px;padding:6px 0;"><strong>Add new Redirect:</strong></div></td>
  </tr>
  <tr>
    <td style="background-color:white;"><div><input name="arr_redirects_new[0][name]" type="text" id="textfield" size="25" maxlength="80" /></div></td>
    <td style="background-color:white;"><div><input name="arr_redirects_new[0][source_url]" type="text" id="textfield" size="35" maxlength="512" />
    </div></td>
    <td style="background-color:white;"><div><input name="arr_redirects_new[0][real_dest_url]" type="text" id="textfield" size="35" maxlength="512" />
    </div></td>
    <td style="background-color:white;"><div><input name="arr_redirects_new[0][spider_dest_url]" type="text" id="textfield" size="35" maxlength="512" />
    </div></td>
    <td style="background-color:white;"><div><input name="arr_redirects_new[0][title]" type="text" id="textfield" size="25" maxlength="200" /></div></td>
      <td style="background-color:#CCCCCC;"><div align="center"></div></td>
  </tr>
      <tr>
    <td  style="background-color:white;"colspan="5"><div align="center" style="padding:5px 0;">
      <input type="submit" name="button_add_edit_redirects" value="Add/Update Redirects" />
    </div></td>
    <td style="background-color:white;"><div align="center">
      <input type="submit" name="button_delete_redirects" value="Delete" onClick="return confirm('Are you sure you want to delete selected Redirects?');" />
    </div></td>
      </tr>
</table>
<br />
</form>
<p>&nbsp;</p>

<!-- Keywords Settings Table -->
<form action="{{{SERVER__REQUEST_URI}}}" method="post">
<table style="background-color:#555;" width="100%" border="1">
  <tr>
    <td style="font-family: Georgia, 'Times New Roman', Times, serif;font-size: 18px;margin:0 0 10px;background:#555;color:#FFF;line-height:32px;" colspan="6"><div align="center">Keywords Table</div></td>
  </tr>
  <tr>
    <td style="background-color:#B5FFA8" width="24%"><div align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;"><strong>Search Pattern for keywords/keyphrases<span style="color:red;">*</span></strong><br />
      case insensitive<br />
    </div></td>
    <td style="background-color:#B5FFA8" width="8%"><div align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;"><strong>Regular<br />
      Expression?</strong><br />
    </div></td>
    <td style="background-color:#B5FFA8" width="8%"><div align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;"><strong>Search Order</strong></div></td>
    <td style="background-color:#B5FFA8" width="54%"><div align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;"><strong>Choose Redirect for matching keywords<span style="color:red;">*</span></strong><br />
    </div></td>
    <td style="background-color:#FF9D9F;"width="6%"><div align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold;">Delete keyword</div></td>
  </tr>
  <tr>
    <td style="background-color:#FBFFB3;" colspan="5"><div align="center" style="font-family: Georgia, 'Times New Roman', Times, serif;font-size: 12px;padding:6px 0;"><strong>Currently defined Keywords:</strong></div></td>
  </tr>

  <!-- {{{CURRENT_KEYWORDS_ROWS}}} -->
  <tr>
    <td style="background-color:white;"><div><input name="arr_keywords[{{{CURR_KEYW_ID}}}][pattern]" type="text" value="{{{CURR_KEYW_PATTERN}}}" size="60" maxlength="128" /></div></td>
    <td style="background-color:white;"><div align="center"><input name="arr_keywords[{{{CURR_KEYW_ID}}}][is_regex]" type="checkbox" {{{CURR_KEYW_CHECKED_IF_REGEX}}} value="1" /></div></td>
    <td style="background-color:white;"><div align="center"><input name="arr_keywords[{{{CURR_KEYW_ID}}}][order]" type="text" size="4" maxlength="4" value="{{{CURR_KEYW_ORDER}}}"/></div></td>
    <td style="background-color:white;"><div align="center">
      <select name="arr_keywords[{{{CURR_KEYW_ID}}}][redirect_id]" size="1">
        <option value="0">--Please select redirect to be used with these keywords--</option>

        <!-- {{{KW_ROW_REDIRECT_OPTIONS}}} -->
         <option value="{{{REDIRECT_ID}}}">{{{REDIRECT_NAME_URL}}}</option>
        <!-- {{{/KW_ROW_REDIRECT_OPTIONS}}} -->

      </select>
    </div></td>
    <td style="background-color:white;"><div align="center">
        <input name="arr_keywords[{{{CURR_KEYW_ID}}}][delete]" type="checkbox"  />
      </div></td>
  </tr>
  <!-- {{{/CURRENT_KEYWORDS_ROWS}}} -->

  <tr>
    <td style="background-color:#FBFFB3;" colspan="5"><div align="center" style="font-family: Georgia, 'Times New Roman', Times, serif;font-size: 12px;padding:6px 0;"><strong>Add new Keyword:</strong></div></td>
  </tr>
  <tr>
    <td style="background-color:white;"><div><input name="arr_keywords_new[0][pattern]" type="text" size="60" maxlength="128" />
    </div></td>
    <td style="background-color:white;"><div align="center"><input type="checkbox" name="arr_keywords_new[0][is_regex]" value="1" /></div></td>
    <td style="background-color:white;"><div align="center"><input name="arr_keywords_new[0][order]" type="text" size="4" maxlength="4" value="0" /></div></td>
    <td style="background-color:white;"><div align="center">
      <select name="arr_keywords_new[0][redirect_id]" size="1">
        <option value="0" selected="selected">--Please select redirect to be used with these keywords--</option>

        <!-- {{{KW_ROW_REDIRECT_OPTIONS}}} -->
         <option value="{{{REDIRECT_ID}}}" >{{{REDIRECT_NAME_URL}}}</option>
        <!-- {{{/KW_ROW_REDIRECT_OPTIONS}}} -->

      </select>
    </div></td>
    <td style="background-color:#CCCCCC;"><div align="center"></div></td>
  </tr>
      <tr>
    <td style="background-color:white;" colspan="4"><div align="center" style="padding:5px 0;">
      <input type="submit" name="button_add_edit_keywords" value="Add/Update Keywords" />
    </div></td>
    <td style="background-color:white;"><div align="center">
      <input type="submit" name="button_delete_keywords" value="Delete" onClick="return confirm('Are you sure you want to delete selected Keywords?');" />
    </div></td>
      </tr>
</table>
<br />
</form>


<!-- Global Settings Table -->

<form action="{{{SERVER__REQUEST_URI}}}" method="post">
<table style="background-color:#555;" width="100%" border="1">
  <tr>
    <td style="font-family: Georgia, 'Times New Roman', Times, serif;font-size: 18px;margin:0 0 10px;background:#555;color:#FFF;line-height:32px;" colspan="3"><div align="center">Global Settings  Table</div></td>
  </tr>
  <tr>
    <td style="background-color:#B5FFA8" width="25%"><div align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 14px;"><strong>Setting name</strong></div></td>
    <td style="background-color:#B5FFA8" width="21%"><div align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 14px;"><strong>Setting value</strong><br />
    </div></td>
    <td style="background-color:#B5FFA8" width="54%"><div align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 14px;"><strong>Notes</strong> <br />
    </div></td>
    </tr>

  <tr>
    <td style="background-color:white;"><div align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;font-weight:bold;margin:0 5px;">Pages/Posts ID's to ignore</div></td>
    <td style="background-color:white;"><div align="center">
      <input name="global_settings[ids_to_ignore]" type="text" value="{{{GLOB_IDS_TO_IGNORE}}}" size="35" maxlength="512" />
    </div></td>
    <td style="background-color:white;"><div align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;margin:0 5px;line-height:13px;padding:2px 0;">Comma-delimited list of pages and post ID's to pass without processing.<br />Format: <b>1,23,56</b> (no spaces)</div></td>
  </tr>

  <tr>
    <td style="background-color:white;"><div align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;font-weight:bold;margin:0 5px;">Set redirect code for redirections</div></td>
    <td style="background-color:white;"><div align="center">
    <!-- {{{GLOB_REDIRECT_CODE_OPTIONS}}} -->
    <select name="global_settings[redirect_code]" size="1">
        <option value="301">301 (moved permanently)</option>
        <option value="302">302 (found/moved temporarily)</option>
      </select>
    <!-- {{{/GLOB_REDIRECT_CODE_OPTIONS}}} -->
      </div></td>
    <td style="background-color:white;"><div align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;margin:0 5px;line-height:13px;padding:2px 0;">Default: 301 (permanent, SEO juice from redirect URL goes to target site).<br />Note: 301 redirect looks slightly better in the eyes of search engines.</div></td>
  </tr>

  <tr>
    <td style="background-color:white;"><div align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;font-weight:bold;margin:0 5px;">Max links count per keyword/per page</div></td>
    <td style="background-color:white;"><div align="center"><input name="global_settings[max_links_count]" type="text" value="{{{GLOB_MAX_LINKS_COUNT}}}" size="10" maxlength="4" /></div></td>
    <td style="background-color:white;"><div align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;margin:0 5px;line-height:13px;padding:2px 0;">Maximum number of links to insert per page for each matching keyword. Default: -1 (unlimited).<br />Note: applies only to text links.</div></td>
  </tr>

  <tr>
    <td style="background-color:white;"><div align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;font-weight:bold;margin:0 5px;">Link target type</div></td>
    <td style="background-color:white;"><div align="center">
    <!-- {{{GLOB_LINK_TARGET_TYPE_OPTIONS}}} -->
      <select name="global_settings[link_target_type]" size="1">
        <option value="_blank">_blank</option>
        <option value="_parent">_parent</option>
        <option value="_self">_self</option>
        <option value="_top">_top</option>
      </select>
    <!-- {{{/GLOB_LINK_TARGET_TYPE_OPTIONS}}} -->
</div></td>
    <td style="background-color:white;"><div align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;margin:0 5px;line-height:13px;padding:2px 0;">Set target type for links. Default: _blank (new browser page opens when link is clicked).</div></td>
  </tr>

  <tr>
    <td style="background-color:white;"><div align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;font-weight:bold;margin:0 5px;">Enable Google Analytics tracking?</div></td>
    <td style="background-color:white;"><div align="center"><input name="global_settings[enable_ga_tracking]" type="checkbox" {{{GLOB_CHECKED_IF_ENABLE_GA_TRACKING}}} /></div></td>
    <td style="background-color:white;"><div align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;margin:0 5px;line-height:13px;padding:2px 0;">
    If checked - outgoing clicks on AFLinker-created links will be tracked through Google Analytics.<br />
    Please note that you need to have your <a href="http://google.com/support/analytics/bin/answer.py?answer=55529" target="_blank">generic GA tracking code placed right after the opening <b>&lt;body&gt;</b> tag</a>.
    When this tracking is enabled - virtual '/outgoing/...' pages will appear in Analytics tracking stats.
    </div></td>
  </tr>

  <tr>
    <td style="background-color:white;"><div align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;font-weight:bold;margin:0 5px;">Add custom inline information for links</div></td>
    <td style="background-color:white;"><div align="center"><input name="global_settings[link_custom_info]" type="text" id="textfield" value="{{{GLOB_LINK_CUSTOM_INFO}}}" size="35" maxlength="512" />
    </div></td>
    <td style="background-color:white;"><div align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;margin:0 5px;line-height:13px;padding:2px 0;">Add custom information to links, such as: <b>style=&quot;...&quot; id=&quot;...&quot; class=&quot;...&quot;</b> &nbsp;Default - empty.</div></td>
  </tr>

  <tr>
    <td style="background-color:white;"><div align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;font-weight:bold;margin:0 5px;">Add rel=&quot;nofollow&quot; to links?</div></td>
    <td style="background-color:white;"><div align="center"><input name="global_settings[add_rel_nofollow]" type="checkbox" {{{GLOB_CHECKED_IF_ADD_REL_NOFOLLOW}}} /></div></td>
    <td style="background-color:white;"><div align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;margin:0 5px;line-height:13px;padding:2px 0;">Prevent search engines from following redirects and spilling page rank.</div></td>
  </tr>

  <tr>
    <td style="background-color:white;"><div align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;font-weight:bold;margin:0 5px;">Process images?</div></td>
    <td style="background-color:white;"><div align="center"><input name="global_settings[process_images]" type="checkbox" {{{GLOB_CHECKED_IF_PROCESS_IMAGES}}} /></div></td>
    <td style="background-color:white;"><div align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;margin:0 5px;line-height:13px;padding:2px 0;">If checked - images with matching &quot;alt&quot; and &quot;title&quot; tags will be wrapped in links as well.</div></td>
  </tr>

  <tr>
    <td colspan="3" style="background-color:white;">
      <div align="center" style="font-family: Georgia, 'Times New Roman', Times, serif;font-size:18px;margin:2px 5px;padding:2px 0;"><span style="background-color:black;color:white;border:1px solid red;padding:2px 10px;">Black Hat Features</span>
       <!-- {{{GLOB_WHITE_HAT_MESSAGE}}} -->
       <!-- {{{/GLOB_WHITE_HAT_MESSAGE}}} -->
      </div>
    </td>
  </tr>

  <tr>
    <td style="background-color:white;"><div align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;font-weight:bold;margin:0 5px;">Google Slap immunity enabled?</div></td>
    <td style="background-color:white;"><div align="center"><input name="global_settings[google_slap_immune]" type="checkbox" {{{GLOB_DISABLED_IF_WHITE_HAT}}} {{{GLOB_CHECKED_IF_GOOGLE_SLAP_IMMUNE}}} /></div></td>
    <td style="background-color:white;"><div align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;margin:0 5px;line-height:13px;padding:2px 0;line-height:13px;padding:2px 0;">If checked - AFLinker will redirect search engine spiders and crawlers to non-affiliate destination URLs, while sending all human visitors to your affiliate links (Real Destination URLs).</div></td>
  </tr>

  <tr>
    <td style="background-color:white;"><div align="left" style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;font-weight:bold;margin:0 5px;">Force non-affiliate destination URLs</div></td>
    <td style="background-color:white;"><div align="center"><input name="global_settings[force_non_aff_urls]" type="checkbox" {{{GLOB_DISABLED_IF_WHITE_HAT}}} {{{GLOB_CHECKED_IF_FORCE_NON_AFF_URLS}}} /></div></td>
    <td style="background-color:white;"><div align="left" div style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;margin:0 5px;line-height:13px;padding:2px 0;line-height:13px;padding:2px 0;">If checked - all visitors will be redirected to non-affiliate destination URLs.<br />Note: Turn it on <span style="color:red;">temporarily only</span> in cases such as waiting for your PPC landing page to be approved by humans at Google Adwords team.</div></td>
  </tr>

  <tr>
    <td  style="background-color:white;"colspan="3"><div align="center" style="padding:5px 0;">
      <input type="submit" name="button_update_global_settings" value="Update Global Settings" />
    </div></td>
  </tr>
</table>
</form>
</div>
<!-- ========== [AFLinker Admin Settings] ========== -->
MENU_HTML;

return $admin_menu;
}
//===========================================================================

?>
