=== AFLinker - Affiliate Link Cloaker and SEO Links Redirect Manager ===
Contributors: gesman
Main URL:     http://www.aflinker.com/
Tags: affiliate link cloaker, affiliate links cloaker, affiliate marketing, affiliate marketing software, online affiliate marketing, internet affiliate marketing, affiliate marketing business, affiliate link, affiliate links, url shortener, url shortening, redirection, redirect, redirector, 301 redirect, SEO redirect, keyword link, keyword links, google slap, google adwords slap

Requires at least: 2.5
Tested up to: 2.8.5
Stable tag: trunk
== Description ==

AFLinker is multi-functional plugin for affiliate marketing automation.
It combines features of affiliate Link cloaker, SEO enhanced affiliate links generator, your own URL shortener service and redirect manager.
AFLinker converts predefined keywords within the text of your website pages into clickable clean (cloaked) links.

You define keywords via search pattern or regular expression and assign affiliate link to each keyword.
Aflinker automatically scan for your keyphrases within your web pages and dynamically converts them into clean looking cloaked (concealed) redirects to your affiliate sites.
Your affiliate links are never visible for human visitors and search engines alike.

For example if YOUR-SITE.com offers reviews of different hosting services, AFLinker could automatically wrap every occurence of **web hosting** keyword
with **www.YOUR-SITE.com/web-hosting** clickable link - redirect. When clicked, **www.YOUR-SITE.com/web-hosting** will immediately redirect visitor to your affiliate link.
Additionally to that AFLinker could also **make your pictures and images clickable** in the same fashion.
AFLinker lets you concentrate on creating your website content and leave time-consuming error-prone work of adding affiliate links on your pages to the plugin.

AFLinker includes totally unique feature - **immunity from "Google affiliate sites slap"** making your affiliate links invisible to search engines.
**Search engine Slap Immunity** is a powerful feature that allows you to safely build affiliate review and affiliate promotion sites.

Additionally to all above AFLinker offers you to have your own URL shortening service for your own domain.

This single plugin allows you to replace all other affiliate cloaking and redirection plugins combinations with a single plugin.

**AFLinker Features**

*   Automated insertion of clean cloaked redirects around relevant keywords based on keyword patterns you specify.
*   Ability to create links/redirects around images. Images are more often clicked on by visitors than text links. This important alone could boost your commission income without you lifting a finger.
*   Ability to create **clean redirects** without exposing your affiliate codes and prevent commission theft.
*   Ability to turn on **Immunity from Google Affiliate sites slap**. This option makes your affiliate links and affiliate redirects completely invisible to search engines.
*   Support for articles, posts, pages and RSS feeds.
*   Ability to create different redirects for visitors and for search engines.
*   **SEO Optimized** link creation allows you to add custom "title" text to links. This feature improves overall SEO score of your website pages and shows helpful tooltips, encouraging visitors to click on your links.
*   **Direct Integration with Google Analytics for detailed and precise tracking**. AFLinker gives you ability to track every click on link from every page directly via your Google analytics account. Tracking information includes: date, time, page and even link number on that page if you have more than one link pointing to the same location.
*   Simplicity: Single administration screen to input, edit and manage all your settings.
*   Simplicity: Set and forget principle. Everything functions automatically.
*   Ability to create affiliate income empires with multiple sites quickly and inexpensively. Just add content to your website - and the rest is taken care by AFLinker.
*   Ability to specify keyword search patterns via plain text or regular expressions.
*   Ability to add "nofollow" to generated links.
*   Ability to ignore pages and posts of your choice.
*   Ability to customize redirection codes between 301 (permanent redirect) and 302 (temporary redirect).
*   Ability to limit the number of link creations  per page.
*   Ability to visually customize links via insertion of custom CSS id, class and style snippets.
*   Ability to create regular, custom, external non-affiliate redirects just like other redirection plugins do.
*   Ability to have your own custom URL shortener with your own domain and website.
*   NOTE: AFLinker now **includes free open extension API's** allowing anyone to create **custom functionality extensions for AFLinker**.
    Write your own enhancement for AFLinker, sell it and we will promote it for you for free directly from http://www.aflinker.com website!
    Code samples for custom functionality extension is available in this directory: .\extensions\Sample_FunctionalityExtension
*   Note: Free version of AFLinker plugin is fully featured but contain branding and sponsored backlinks to support it's development efforts.
    To request unbranded version of AFLinker please contact http://www.aflinker.com/contact
* **AFLinker supports any standard wordpress theme**. No changes are needed to existing sites to make it benefit from AFLinker.


== Installation ==

1.  Make sure your permalinks are set to anything but default settings. Default settings are the most SEO unfriendly setting.
    To set permalinks properly do this:
    - Login to Wordpress administration panel
    - Go to Settings->Permalinks, click (x) "Custom Structure", enter this value into edit box: /%postname%/
    - Press [Save Changes]

2.  Upload plugin files into ./wp-content/plugins directory maintaining tree structure.

3.  Activate plugin at the plugin administration page.


== Usage Guide ==

    To use AFLinker you need to create 1 or more redirects and then assign proper redirect to each of your keywords (search patterns).
    To explain this concept let walk you step by step by example.
    Let suppose YOUR-SITE.com acts as an affiliate review site promoting webhosting services for different hosting providers.
    You created number of content pages describing webhosting services and features.
    Now you want to automate process of inserting clean affiliate links around relevant keyphrases such as "webhosting service" and "hosting company".
    Note: for demo purposes fresh AFLinker installation includes couple of redirects and keywords settings just to show you how they look like.
    Feel free to delete them and then add your own redirects and keyword patterns as further decribed.

    Creating redirects
    ------------------
    -   In "Redirects Table" under "Add New Redirect" enter name of your redirect, such as: "My Hostgator redirect"
    -   Under "Source (pretty) URL" enter "/webhosting". This will create clean redirect link that will look like:
        http://www.YOUR-SITE.com/webhosting
        It does not contain any visible affiliate link or affiliate code.
    -   Under "Real Destination URL" input your real affiliate link, such as:
        http://secure.hostgator.com/cgi-bin/affiliates/clickthru.cgi?id=your-affiliate-id
        This is where your visitors will be redirected when clicked on http://www.YOUR-SITE.com/webhosting link.
    -   Under "Link title" enter "Best Webhosting Company" or some other relevant text for your visitors to give them a hint what this link is about.
        Link title also serves important SEO purpose by giving search engine extra information about your content and helps to boost your SEO rankings.
        This field is optional but using your main keywords within link title is important and recommended.
    -   Press Add/Update Redirects button to save your new redirect.
    -   Repeat above steps to enter any number of extra redirects.

    Creating keywords
    -----------------
    -   Keywords Table gives you a way to define search patterns for finding relevant keywords within posts and pages. Keywords and keyphrases matching these patterns will be "wrapped" with a proper links.
        Same system works for images, but for images - their "title" and "alt" tags will be searched and matched.
        If either "title" or "alt" tag of an image will be found to match one of the patterns - it will be made clickable according to redirect you specify.
        So let get to the settings.
    -   In "Keywords Table" under "Search Pattern for keywords/keyphrases" enter search pattern to define your keyphrase, such as "hosting company".
    -   Uncheck "Regular Expression?" checkbox.
    -   Leave "Search Order" at default - 0. When you have many keyphrases you may set preferred search order.
        For example you may want to make sure that "web hosting company" keyphrase to be processed before "web hosting" keyphrase.
        Otherwise "web hosting" part of it will be wrapped with link but "... company" part of it will never be.
        If you leave this number at default '0' - AFLinker will scan for longer patterns first which is sufficient in most cases.
    -   Under "Choose Redirect for matching keywords" select proper redirect.
    -   Click Add/Update Keywords button.
    -   Now let move on to move to potentially more complicated keyword: "webhosting service". Suppose you have plenty of content and you want to sprinkle your affiliate links
        around every "webhosting service" keyphrase. But your content was created by ghostwriters and in many cases they use phrases such as:
        "web hosting service"   or
        "webhosting service"    or
        "web hosting services"
        It would be nice to create one keyword pattern for all these cases. With the help of regular expressions - it is easy.
    -   Under "Search Pattern for keywords/keyphrases" enter "web\s*hosting\s+services?"
        This regular expression allows to match all above variations of relevant keyphrases in a single step.
    -   Check "Regular Expression?" checkbox - [x].
    -   Under "Choose Redirect for matching keywords" select proper redirect.
    -   Click Add/Update Keywords button.
    -   Repeat above steps for more keywords if wanted to.
    -   Done.
    -   Note: using regular expressions is a great way to match multiple number of keywords/keyphrases with just one pattern.
        For example this regular expression:   web\s*host(ing)?  will match all the following keyphrases:
            webhost
            web host
            web hosting
            webhosting

    Global Settings Table
    ---------------------
    -   In "Global Settings" table you may customize different options that globally affects AFLinker functions.
        Each of these options have help information shown so it will not be duplicated here.

    Using AFLinker as URL Shortener Service for your own domain
    -----------------------------------------------------------
    -   Building your own URL shortener with AFLinker is very simple and easy.
        You just need to create redirects and enter Source(pretty) URL and desired Real Destination URL.
        For example for Source(pretty) URL enter this: /cash
        For Real Destination URL - enter existing long URL on your site such as: /10-tips-to-make-money-online/
        Alternatively you may enter under Real Destination URL name of external web site, such as: http://www.SOME-SITE.com/some/page/to/read.html
        Once you done that - every click to YOUR-SITE.com/cash will be redirected to above destinations. It's ready!
        And you don't need to create any keywords patterns for that.

* Notes:
    -   Free version of AFLinker plugin is fully featured but contain branding and sponsored backlinks to support it's development efforts.
        To request unbranded version of AFLinker please contact http://www.aflinker.com/contact

== Screenshots ==

1. AFLinker Admin Panel


== Documentation ==

More detailed and up to dated information about AFLinker usage could be found here: [AFLinker User Guide](http://www.aflinker.com/)
