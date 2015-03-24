<?php
define("CODEBASE_VERSION", "3.3.0");
define("DEBUG_FORM", 0);
define("DEBUG_REPORT", 0);
define("DEBUG_MEMORY", 0);
define("PWD_LEN_MIN", 4);
define("SYS_LOG_SLOW", 5);  // Flag queries longer than this (mS) as SLOW in debug file
define("PIWIK_DEV", 1);     // '1' forces community modules to engage with Piwik stats
// test for RSS calendar feed:
//   http://desktop.stphilipsunionville.com/rss/shared_events?what=calendar&YYYY=2008&MM=12
//   http://desktop.westmountparkchurch.org/rss/shared_events?what=calendar&YYYY=2007&MM=09
define(
    "DOCTYPE",
    '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'
);
//define("DOCTYPE", '<!DOCTYPE html SYSTEM "%HOST%/xhtml1-strict-with-iframe.dtd">');
/*
--------------------------------------------------------------------------------
3.3.0.2374 (2015-03-23) - MASSIVE build! (see CONFESSION below...)
Summary:
  1) Moved Google_Maps class to namespace \Map\GoogleMaps and Geocode_Cache to \Map\GeocodeCache
  2) New object and report / form for \Map\AddressSubstitution fr BNN importer corrections
  3) Global changed all public references for function get_version() to static getVersion() -
     every class is affected!

     CONFESSION:
       I didn't update every version code on every file I made that global getVersion() code change to -
       There were too many and life is just too short.

3.3.0.2374 (2015-03-23)
Summary:
  (Provide top-level summary here)

Final Checksums:
  Classes     CS:d8147e02
  Database    CS:c15014ce
  Libraries   CS:e8a227c1
  Reports     CS:f06a043d

Code Changes:
  codebase.php                                                                                   3.3.0     (2015-03-23)
    1) Updated version information
  classes/class.action.php                                                                       1.0.22    (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.activity.php                                                                     1.0.19    (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.ajax.php                                                                         1.0.24    (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
    2) Fix to internal reference for get_version() to getVersion()
  classes/class.akismet.php                                                                      0.41.f    (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
    2) Split out SocketWriteRead class into its own file
  classes/class.array2xml.php                                                                    1.0.1     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.article.php                                                                      1.0.39    (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.backup.php                                                                       1.1.10    (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.barcode.php                                                                      1.0.4     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.base.php                                                                         1.0.14    (2015-03-23)
    1) Method getVersion() is now static
  classes/class.base_error.php                                                                   1.0.2     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.beanstream_gateway.php                                                           1.0.5     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.block_layout.php                                                                 1.0.62    (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.bugtracker.php                                                                   1.0.7     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.captcha.php                                                                      1.0.1     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.cart.php                                                                         1.0.6     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.category.php                                                                     1.0.2     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.category_assign.php                                                              1.0.5     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.cezpdf.php                                                                       1.0.1     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.chasepaymentech_gateway.php                                                      1.0.7     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.checkout.php                                                                     1.0.44    (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.ckfinder.php                                                                     1.0.6     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.colour_scheme.php                                                                1.0.2     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.comment.php                                                                      1.0.19    (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.community.php                                                                    1.0.116   (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.community_article.php                                                            1.0.3     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.community_display.php                                                            1.0.39    (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.community_event.php                                                              1.0.3     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.community_member.php                                                             1.0.107   (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.community_member_article.php                                                     1.0.3     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.community_member_display.php                                                     1.0.41    (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.community_member_event.php                                                       1.0.3     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.community_member_news_item.php                                                   1.0.3     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.community_member_podcast.php                                                     1.0.3     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.community_member_posting.php                                                     1.0.4     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.community_member_resource.php                                                    1.0.7     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.community_member_summary.php                                                     1.0.20    (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.community_membership.php                                                         1.0.5     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.community_news_item.php                                                          1.0.3     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.community_podcast.php                                                            1.0.3     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.community_posting.php                                                            1.0.5     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.community_resource.php                                                           1.0.4     (2015-03-13)
    1) Changes made following move of Community_Member_Calendar to namespaced \Component\CommunityMemberCalendar
  classes/class.community_sponsorship_plan.php                                                   1.0.3     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.component.php                                                                    1.0.108   (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.component_base.php                                                               1.0.22    (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.component_custom_form.php                                                        1.0.4     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.component_customiser_button.php                                                  1.0.2     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.component_document_covers.php                                                    1.0.0     (2011-12-30)
    1) Initial release - moved from Component class
  classes/class.component_document_reader.php                                                    1.0.0     (2011-12-30)
    1) Initial release - moved from Component class
  classes/class.component_donate.php                                                             1.0.2     (2014-01-28)
    1) Newline after JS onload code
  classes/class.component_edit_your_profile.php                                                  1.0.0     (2013-06-03)
    1) Initial release - moved from Person class and User class
  classes/class.component_email_form.php                                                         1.0.1     (2013-10-29)
    1) Brought component up to date with latest standards
    2) Now sets 'reply to' address to Email field if given
  classes/class.component_email_newsletter_signup.php                                            1.0.0     (2011-12-30)
    1) Initial release - moved from Component class
  classes/class.component_email_opt_in.php                                                       1.0.0     (2014-06-22)
    1) Initial release
  classes/class.component_email_opt_out.php                                                      1.0.0     (2014-06-22)
    1) Initial release
  classes/class.component_email_to_friend.php                                                    1.0.0     (2011-12-29)
    1) Initial release - moved from Component class
  classes/class.component_event_registration.php                                                 1.0.7     (2014-02-06)
    1) Now invokes Report_Form_Field_Lookup class to handle ajax lookup
  classes/class.component_events_map.php                                                         1.0.2     (2015-01-31)
    1) Changes to internally used parameters in Component_Events_Map::_setup_load_event_IDs():
         Old: limit,         order_by
         New: results_limit, results_order
    2) Now PSR-2 Compliant
  classes/class.component_facebook_like.php                                                      1.0.4     (2012-11-02)
    1) Bug fix for jquery call - was unescaped
  classes/class.component_flickr.php                                                             1.0.0     (2011-12-30)
    1) Initial release - moved from Component class
  classes/class.component_folder_viewer.php                                                      1.0.0     (2011-12-30)
    1) Initial release - moved from Component class
  classes/class.component_forgotten_password.php                                                 1.0.1     (2014-01-06)
    1) Component_Forgotten_Password now uses User class to draw actual control
  classes/class.component_form.php                                                               1.0.0     (2010-10-12)
    1) Initial release
  classes/class.component_gallery_album.php                                                      1.0.71    (2014-04-03)
    1) Last build prevented valid configuration of NO root folder which caused some issues
       This build now correctly handles that circumstance
  classes/class.component_gallery_album_gallery_image.php                                        1.0.3     (2013-11-08)
    1) Now includes 'Up' button
  classes/class.component_gallery_fader.php                                                      1.0.42    (2015-01-31)
    1) Changes to internally used parameters in Component_Gallery_Fader::_setup_load_records():
         Old: filter_limit,  filter_order_by
         New: results_limit, results_order
    2) Now PSR-2 Compliant
  classes/class.component_gallery_thumbnails.php                                                 1.0.35    (2014-01-31)
    1) Changes to internally used parameters in Component_Gallery_Thumbnails::_setup_load_images():
         Old: filter_limit,  filter_order_by
         New: results_limit, results_order
    2) Now PSR-2 Compliant
  classes/class.component_gc_weather.php                                                         1.0.0     (2010-12-11)
    1) Initial release
  classes/class.component_google_map.php                                                         1.0.0     (2011-12-30)
    1) Initial release - moved from Component class
  classes/class.component_google_plusone.php                                                     1.0.0     (2012-01-25)
    1) Initial release
  classes/class.component_group_member_redirector.php                                            1.0.1     (2013-11-20)
    1) Component_Group_Member_Redirector::draw() removed support for
       permADMIN and permAPPROVER
  classes/class.component_image_fader.php                                                        1.0.1     (2014-01-28)
    1) Newline after js onload code
  classes/class.component_image_gallery.php                                                      1.0.0     (2011-12-30)
    1) Initial release - moved from Component class
  classes/class.component_image_text.php                                                         1.0.0     (2010-07-07)
    1) Moved Component::image_text() into here
  classes/class.component_importer.php                                                           1.0.2     (2012-01-23)
    1) Now uses _setup() and _setup_load_parameters() in base class
  classes/class.component_inline_signin.php                                                      1.0.2     (2014-01-28)
    1) Newline after js onload code
  classes/class.component_jumploader.php                                                         1.0.4     (2011-03-30)
    1) Added Component_Jumploader::_draw_uploaded_files_table()
  classes/class.component_language_button.php                                                    1.0.1     (2012-12-08)
    1) French c-cedilla charcacter now defined as HTML entity
  classes/class.component_member_search.php                                                      1.0.12    (2014-03-28)
    1) Now has additional CPs:
         'filter_type'
         'show_range_ring'
         'show_start_ring'
    2) Component_Member_Search::_get_query_limit_for_filter() now filters on type
    2) Component_Member_Search::_setup_lookup_search_location() now remembers
       search_area to provide for range ring indicating certainty of search location
  classes/class.component_nav_links.php                                                          1.0.1     (2012-11-16)
    1) Now cleanly handles issue where there are no buttons to draw links for
  classes/class.component_order_detail.php                                                       1.0.6     (2013-10-31)
    1) Component_Order_Detail::draw() now uses ECL tag 'draw_signin()' rather than
       the hated 'component_signin_context' which is now gone forever.
  classes/class.component_password_protect.php                                                   1.0.3     (2014-01-28)
    1) Newline and semicolon after js in Component_Password_Protect::_draw_js()
  classes/class.component_paypal_return.php                                                      1.0.1     (2013-10-11)
    1) Now reports error if there are no gateway settings defined for component
       or for the site
  classes/class.component_persons_listing.php                                                    1.0.1     (2014-01-17)
    1) Change to Component_Persons_Listing::_draw_entry() map info-window code
       to use new ecc_map.point.i() helper function
  classes/class.component_persons_map.php                                                        1.0.1     (2013-10-28)
    1) Added CP for filter_sp to filter on state / province
    2) CP for filter_category now defaults to '*' meaning everything
  classes/class.component_poll.php                                                               1.0.2     (2012-11-03)
    1) Now inserts extra break between multiple items where required
  classes/class.component_poll_archive.php                                                       1.0.1     (2012-11-03)
    1) Big changes to allow for use of standard methods for setup and control panel
       and refactoring to reduce stament nesting
    2) Changes to deal with fields being renamed in poll_choice table in preparation
       for moving to postings table
  classes/class.component_prayer_request.php                                                     1.0.0     (2015-02-14)
    1) Initial release - Moved from Church Module
  classes/class.component_prev_next.php                                                          1.0.1     (2014-08-12)
    1) Changes to bring this component up to date and to have a configrable control panel
    2) Added ability to take a list of pages and take links from them to splice into the
       sequence to allow for navigation through pages not normally navigable via navsuites
  classes/class.component_product_dropdown.php                                                   1.0.2     (2014-04-16)
    1) Now has separate setup method and applies correct visibility to displayed items
  classes/class.component_random_news.php                                                        1.0.1     (2015-01-28)
    1) Now has parameters to show content and title and CM editing of news item currently being displayed
  classes/class.component_related_block.php                                                      1.0.4     (2012-01-23)
    1) Changes to _setup() to harness protected method in Component_Base class
    2) Removed local implementation of _setup_load_parameters()
  classes/class.component_remote_page_content.php                                                1.0.0     (2011-12-31)
    1) Initial release - moved from Component class
  classes/class.component_rss_displayer.php                                                      1.0.1     (2014-01-28)
    1) Added newline and semicolon after JS onload code
  classes/class.component_rss_headlines.php                                                      1.0.0     (2011-12-31)
    1) Initial release - moved from Component class
  classes/class.component_sdmenu.php                                                             1.0.5     (2014-01-28)
    1) Newline after JS code in Component_SDMenu::_draw_js_onload()
  classes/class.component_search_date_picker.php                                                 1.0.0     (2011-12-31)
    1) Initial release - moved from Component class
  classes/class.component_search_tag_cloud.php                                                   1.0.0     (2011-12-31)
    1) Initial release - moved from Component class
  classes/class.component_search_word_cloud.php                                                  1.0.0     (2011-12-31)
    1) Initial release - moved from Component class
  classes/class.component_secure_email.php                                                       1.0.0     (2011-12-31)
    1) Initial release - moved from Component class
  classes/class.component_share_this.php                                                         1.0.2     (2012-11-06)
    1) Removed hidden paragraph for facebook context -
       We now have open graph (og) metatags provided by
       Displayable_Item::_draw_detail_include_og_support()
  classes/class.component_shop.php                                                               1.0.8     (2012-01-23)
    1) Changes to _setup() to harness protected method in Component_Base class
    2) Removed local implementation of _setup_load_parameters()
  classes/class.component_signin.php                                                             1.0.2     (2014-01-28)
    1) Newline and semicolun after JS code in Component_Signin::_draw_signin()
  classes/class.component_signup.php                                                             1.0.2     (2013-06-03)
    1) Added new CP for width
  classes/class.component_sitemap.php                                                            1.0.2     (2012-12-19)
    1) Changes to Component_Sitemap::get_sitemap() to handle possibility that the
       current pages doesn't appear on the nav structure at all
  classes/class.component_social_icon.php                                                        1.0.1     (2013-07-30)
    1) SEO improvments with inclusion of inline height and width attributes
  classes/class.component_splash_page.php                                                        1.0.0     (2012-01-01)
    1) Initial release - moved from Component class
  classes/class.component_subscribe.php                                                          1.0.1     (2010-10-25)
    1) Bit more work on subscription component - not complete yet
  classes/class.component_survey.php                                                             1.0.0     (2013-03-05)
    1) Initial release
  classes/class.component_time_tracker.php                                                       1.0.3     (2012-10-28)
    1) Changes to use jquery for elemet selection, not prototypejs
  classes/class.component_twitter.php                                                            1.0.2     (2012-05-04)
    1) Added Component_Twitter::draw_tweets()
  classes/class.component_video_player.php                                                       1.0.1     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
    2) Fix to internal reference for get_version() to getVersion()
  classes/class.contact.php                                                                      1.0.5     (2013-11-07)
    1) Added edit_parameters to allow this type to be iewed in a listings panel
  classes/class.content_block.php                                                                1.0.9     (2013-06-27)
    1) Moved displaying of content_blocks as ECL tags into own dedicated class
  classes/class.context_menu.php                                                                 1.0.75    (2014-03-29)
    1) Context_Menu::_cm_community_member() now sets communityID when adding new items
  classes/class.country.php                                                                      1.0.1     (2011-09-06)
    1) Changes to Country::get_iso3166() to use lst_named_type and avoid
       hard-coded listtypeID and made static
  classes/class.cpdf.php                                                                         1.0.0     (2009-07-11)
    Initial release
  classes/class.credit_memo.php                                                                  1.0.11    (2011-08-26)
    1) Changes to Credit_Memo::draw_items() for renaming of product_category to
       product_grouping table
  classes/class.crm_case.php                                                                     1.0.12    (2014-02-18)
    1) Refreshed fields list - now declared as a class constant
  classes/class.crm_case_task.php                                                                1.0.2     (2010-10-04)
    1) Changes to setter and getter names for parent-based object properties
  classes/class.cron.php                                                                         1.0.10    (2014-02-21)
    1) CRON::heartbeat_actions() now includes queued map updates
    2) Bug fix for CRON::heartbeat() to correctly prevent a second thread from being
       activated within 55 seconds of a previous activation
  classes/class.curl.php                                                                         1.0.1     (2009-10-21)
    1) Changes to Curl::_construct() to allow for cookies file for persistent sessions
  classes/class.custom_form.php                                                                  1.0.41    (2014-02-07)
    1) JS callback functions for ajax fedex rate lookups now contained within sajax
       object to reduce namespace clutter
  classes/class.custom_registration.php                                                          1.0.3     (2012-08-28)
    1) Further tweak to correct newline conversion - wasn't quite right last time
  classes/class.displayable_item.php                                                             1.0.152   (2015-03-23)
    1) Now uses namespaced \Map\GoogleMap class in place of Google_Map
    2) Method get_version() renamed to getVersion() and made static
  classes/class.droplib.php                                                                      2.1.0.c   (2012-10-14)
    1) Additional parameter for DropLib_Http::fetch() - use_post
    2) New method DropLib::delta() - takes cursor and returns delta list of changes
  classes/class.dtd.php                                                                          1.0.0     (2012-02-03)
    1) Initial release
  classes/class.ecc_facebook.php                                                                 1.0.0     (2010-05-03)
    Initial release
  classes/class.ecl_tag.php                                                                      1.0.3     (2012-05-27)
    1) Tweak to ECL_Tag::get_all() to make sure that tag names are all lowercase
       for consistent use, even in email broardcast where plain text version of
       message is in uppercase, including ECL tags
  classes/class.event.php                                                                        1.0.104   (2015-02-06)
    1) New CP for listings - results_order - previously not possible to change display order
    2) Now PSR-2 Compliant
  classes/class.event_recurrence.php                                                             1.0.12    (2013-12-10)
    1) Now uses Displayable_Item::_draw_render_JSON() instead of its own variant
  classes/class.export.php                                                                       1.0.25    (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
    2) Export::draw() now looks for exportSql() and then tries export_sql() if the former method isn't found
  classes/class.extended_community.php                                                           1.0.0     (2015-03-17)
    1) Moved into its own file - was originally part of class.component_communities_display.php
  classes/class.fck.php                                                                          1.0.22    (2014-04-17)
    1) Changes to include indenting rules for parsed code
  classes/class.fdf.php                                                                          1.0.0     (2009-07-02)
    Initial release
  classes/class.field_template.php                                                               1.0.1     (2010-10-04)
    1) Changes to setter and getter names for parent-based object properties
  classes/class.filesystem.php                                                                   1.0.17    (2015-01-11)
    1) Changes to FileSystem::get_file_changes() to deal with unix-style line endings in classes
    2) Now PSR-2 Compliant
  classes/class.font_face.php                                                                    1.0.1     (2010-10-04)
    1) Changes to setter and getter names for parent-based object properties
  classes/class.gallery_album.php                                                                1.0.33    (2015-01-31)
    1) Changes to internally used parameters in Gallery_Album::BL_contained_items():
         Old: filter_limit,  paging_controls
         New: results_limit, results_paging
    2) Now PSR-2 Compliant
  classes/class.gallery_image.php                                                                1.0.23    (2013-06-07)
    1) Changed the following CPs for listings mode:
         Old: 'grouping_tabs',    'filter_limit',  'filter_order_by', 'paging_controls'
         New: 'results_grouping', 'results_limit', 'results_order',   'results_paging'
  classes/class.gateway_setting.php                                                              1.0.4     (2012-09-05)
    1) Gateway_Setting::do_donation() wasn't recognising gateway types of
       'Paypal (Live)' and 'Paypal (Test)' - it does now.
  classes/class.gateway_type.php                                                                 1.0.2     (2012-05-08)
    1) Added handle_report_copy() method for cloning entries
    2) Removed stub for Gateway_Type::get_beanstream_country() -
       was never implemented nor used
  classes/class.gc_weather.php                                                                   1.0.0     (2010-12-10)
    Initial release
  classes/class.google_map.php                                                                   1.0.48    (2015-03-23)
    1) Moved main code into new namespaced class under map
    2) This class is now just a stub for backward compatability
    3) Method get_version() renamed to getVersion() and made static
  classes/class.group.php                                                                        1.0.29    (2014-06-22)
    1) Group::member_assign() now includes support for permEMAILOPTIN and optional parameter
       $email_subscription_log
  classes/class.group_assign.php                                                                 1.0.2     (2012-10-01)
    1) Group_Assign::get_selector_sql() has colour-codes entries for MasterAdmin
       now that option-transfer function is now preserving these after sorting
    2) Group_Assign::get_selector_sql() no longer takes any parameters so looks
       similar to other methods of the same name
  classes/class.group_member.php                                                                 1.0.5     (2014-06-22)
    1) Added permEMAILOPTIN to permArr
  classes/class.group_wizard.php                                                                 1.0.13    (2013-10-27)
    1) Group_Wizard::_setup_get_targetIDs() now uses Record::set_group_concat_max_len()
       to change MYSQL session variable group_concat_max_len
  classes/class.gwsocket.php                                                                     1.0.0     (2009-07-02)
    Initial release
  classes/class.handler.php                                                                      1.0.0     (2009-07-02)
    Initial release
  classes/class.help.php                                                                         1.0.7     (2012-11-28)
    1) Help::menu() now uses System::get_item_version() not
       System::get_version() as before
  classes/class.history.php                                                                      1.0.5     (2012-09-20)
    1) History:initialise() now sets shop page to '' to allow this to be determined
       by CP values in checkout
  classes/class.html.php                                                                         1.0.88    (2015-03-17)
    1) Made some aliases for PSR-2 compliant method names:
          HTML::drawSectionTabButtons() ->  HTML::draw_section_tab_buttons()
          HTML::drawSectionTabDiv()     ->  HTML::draw_section_tab_div()
          HTML::drawFormBox()           ->  HTML::draw_form_box()
  classes/class.html2text.php                                                                    1.0.1     (2012-06-12)
    1) Added extra search and replace clauses for ECL tags to strip extra slashes
       inside bolded tags
  classes/class.http_raw_socket.php                                                              1.0.0     (2009-07-02)
    Initial release
  classes/class.image_factory.php                                                                1.0.10    (2012-01-20)
    1) Changes to Image_Factory::xml_to_image() for text handling using datasource
       to make it show field name if datasource field is not actually set
  classes/class.image_template.php                                                               1.0.2     (2011-08-24)
    1) Added handle_report_copy() to implement renaming of cloned item
  classes/class.job_posting.php                                                                  1.0.20    (2013-06-07)
    1) Changed the following CPs for listings mode:
         Old: 'grouping_tabs',    'filter_limit',  'filter_order_by', 'paging_controls'
         New: 'results_grouping', 'results_limit', 'results_order',   'results_paging'
  classes/class.jsmin.php                                                                        1.0.0     (2010-05-10)
    Initial release
  classes/class.jumploader.php                                                                   1.0.7     (2011-07-05)
    1) Jumploader JS now registers presence of java applet to allow it to be
       hidden if required, e.g. with dropdown menu or layer-based divs.
  classes/class.keyword.php                                                                      1.0.10    (2011-11-18)
    1) Keyword::delete() now removes associated csv entries for all types
       including products (not previously handled) and contacts
       (was looking in contacts table for these - that doesn't event exist now!)
  classes/class.keyword_assign.php                                                               1.0.2     (2010-10-19)
    1) Keyword_Assign::set_for_assignment() now calls insert() method
  classes/class.language.php                                                                     1.0.3     (2012-12-09)
    1) Changes to Language::prepare_field() to handle resaving of single language
       content after conversion to a multi-language system.
    2) Bug fix for changing language to ensure that viewer remains on original page
  classes/class.language_assign.php                                                              1.0.1     (2013-10-10)
    1) New method Language_Assign::get_listdata_for_assignment()
    2) New method Language_Assign::get_text_csv_for_assignment()
  classes/class.layout.php                                                                       1.0.28    (2014-02-18)
    1) Refreshed fields list - now declared as a class constant
  classes/class.link.php                                                                         1.0.6     (2013-06-07)
    1) Changed the following CPs for listings mode:
         Old: 'grouping_tabs',    'filter_limit',  'filter_order_by', 'paging_controls'
         New: 'results_grouping', 'results_limit', 'results_order',   'results_paging'
  classes/class.listdata.php                                                                     1.0.5     (2014-02-18)
    1) Refreshed fields list - now declared as a class constant
  classes/class.listtype.php                                                                     1.0.7     (2014-02-18)
    1) Refreshed fields list - now declared as a class constant
  classes/class.lst_delivery_method.php                                                          1.0.0     (2009-07-02)
    Initial release
  classes/class.lst_delivery_status.php                                                          1.0.0     (2009-07-02)
    Initial release
  classes/class.lst_effective_period_unit.php                                                    1.0.0     (2010-09-20)
    Initial release
  classes/class.lst_language.php                                                                 1.0.0     (2013-10-08)
    Initial release
  classes/class.lst_named_type.php                                                               1.0.4     (2012-04-20)
    1) Added new method lst_named_type::get_listdata() to get all items
  classes/class.lst_note_person_type.php                                                         1.0.0     (2009-07-02)
    Initial release
  classes/class.lst_payment_status.php                                                           1.0.2     (2010-10-04)
    1) Changes to setter and getter names for parent-based object properties
  classes/class.lst_persontitle.php                                                              1.0.0     (2009-07-02)
    Initial release
  classes/class.lst_product_type.php                                                             1.0.0     (2009-07-02)
    Initial release
  classes/class.lst_refund_status.php                                                            1.0.0     (2009-07-02)
    Initial release
  classes/class.mail_identity.php                                                                1.0.7     (2011-06-29)
    1) Renamed class from Email_Identity to Mail_Identity
  classes/class.mail_queue.php                                                                   1.0.37    (2014-03-11)
    1) Mail_Queue::_draw_wizard_preview() now URL decodes content supplied to it
       because the javascript that packages the content now first URL encodes it.
  classes/class.mail_queue_item.php                                                              1.0.16    (2015-03-01)
    1) Bug fix for viewing message list - needed personID to get list of messages sent
    2) New method viewMessagesForPerson
    3) Renamed const fields to FIELDS
    4) Now PSR-2 Compliant
  classes/class.mail_template.php                                                                1.0.13    (2012-05-25)
    1) Mail_Template::send_email() now sets NGreetingName
  classes/class.media.php                                                                        1.0.3     (2010-10-04)
    1) Changes to setter and getter names for parent-based object properties
  classes/class.media_audioplayer.php                                                            1.0.9     (2014-01-28)
    1) Newline after code in JS onload code in Media_Audioplayer::draw_clip()
  classes/class.media_youtube.php                                                                1.0.6     (2014-01-20)
    1) Now accepts optional fourth parameter to determine start time for clip
  classes/class.membership_rule.php                                                              1.0.2     (2010-10-04)
    1) Changes to setter and getter names for parent-based object properties
  classes/class.navbutton.php                                                                    1.0.16    (2014-02-18)
    1) Refreshed fields list - now declared as a class constant
  classes/class.navbutton_image.php                                                              1.0.8     (2012-12-08)
    1) Bug fix for one accented character in Navbutton_Image::get_uppercase()
  classes/class.navbutton_style.php                                                              1.0.8     (2014-02-18)
    1) Refreshed fields list - now declared as a class constant
  classes/class.navsuite.php                                                                     1.0.33    (2014-02-18)
    1) Refreshed fields list - now declared as a class constant
  classes/class.news_item.php                                                                    1.0.24    (2015-02-06)
    1) New CP for listings - results_order - previously not possible to change display order
    2) Now PSR-2 Compliant
  classes/class.note.php                                                                         1.0.3     (2013-06-07)
    1) Changed the following CPs for listings mode:
         Old: 'grouping_tabs',    'filter_limit',  'filter_order_by', 'paging_controls'
         New: 'results_grouping', 'results_limit', 'results_order',   'results_paging'
  classes/class.notification.php                                                                 1.0.4     (2012-10-17)
    1) Split out css into static Notification::draw_css()
    2) Split out header to static Notification::draw_footer()
    3) Split out footer to static Notification::draw_footer()
  classes/class.order.php                                                                        1.0.68    (2014-02-18)
    1) Refreshed fields list - now declared as a class constant
  classes/class.orderitem.php                                                                    1.0.14    (2014-02-18)
    1) Refreshed fields list - now declared as a class constant
  classes/class.orders_overview.php                                                              1.0.1     (2012-04-25)
    1) Fix to Orders_Overview::_setup_get_unique_payment_status() to default
       payment status to 'IGNORED' not 'Paid' if not given.
       Was failing to show orders if they all had the same status and that status
       was other than 'Paid'
    2) Was using wrong constant for returned version number
  classes/class.page.php                                                                         1.0.120   (2015-03-04)
    1) Change to Page::prepare_html_head() now Component_Bible_Links::draw() is \Component\BibleLinks::draw()
    2) Added pushContent() as alias for now deprecated push_content()
  classes/class.page_edit.php                                                                    1.0.16    (2015-01-03)
    1) Now uses OPTION_SEPARATOR constant not option_separator in Page_Edit::draw() for saving
    2) Removed 'yellow fever' correction cde - not needed with new CK Editor code
    3) Now PSR-2 Compliant
  classes/class.page_vars.php                                                                    1.0.24    (2015-01-02)
    1) Added provision to ban msnbot and bingbot if load is too high
    2) Now Page_Vars::_get_vars_for_mode() sets ID for details, export, print_form and report
    3) Now PSR-2 Compliant
  classes/class.password.php                                                                     1.0.4     (2012-03-28)
    1) Initial Release:
    2) Page_Vars::_password_check_history() now Password::check_csvlist_against_previous()
    3) Page_vars::_password_check() now Password::check_password_against_csvlist()
  classes/class.payment_method.php                                                               1.0.10    (2014-01-29)
    1) Payment_Method::draw_selector() changes to JS for loadTotalCost() to add extra newline
  classes/class.paypal_gateway.php                                                               1.0.24    (2013-03-08)
    1) PayPal_Gateway::simplePaymentVerify() -
       Extensive changes to have system show prominent 'print tickets' link
       and also to prevent errors if page is accessed with TX token but person
       isn't signed in anymore.
  classes/class.pdf.php                                                                          1.0.1     (2009-07-11)
    Changes to paths for cpdf and cezpdf
  classes/class.person.php                                                                       1.0.124   (2015-03-22)
    1) Added AMap_geocode_address and WMap_geocode_address to fields list
    2) Person::get_coords() now includes map_geocode_address
  classes/class.person_merge_profiles.php                                                        1.0.4     (2015-01-11)
    1) Changed references from System::tables to System::TABLES
    2) Now PSR-2 Compliant
  classes/class.php_excel.php                                                                    1.0.2     (2012-05-01)
    1) Changes to constructor:
       Greatly improved error handling if Pear date library isn't present -
       Now provides instructions to correct the issue
  classes/class.phpmailer.php                                                                    2.0.0     (2010-06-09)
    1) New release using PHPMailer 5.1
    2) Changes to PHPMailer::CreateHeader() to save MessageID for tracking
    3) Changes to various functions to NOT echo errors but just throw exceptions
  classes/class.phpop3.php                                                                       1.0.2     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.piwik.php                                                                        1.0.2     (2014-02-05)
    1) Piwik::get_outlinks() is incapable of accepting a filter so removed 'find'
    2) Added Piwik::get_outlink() - takes a pipe delimited find parameter
    3) Added Piwik::get_visit() - takes a pipe delimited find parameter
  classes/class.podcast.php                                                                      1.0.46    (2015-02-06)
    1) Now allows for ordering by date_d_name_a and date_d_title_a (for DCC AM / PM services on same day)
  classes/class.podcast_album.php                                                                1.0.17    (2015-02-01)
    1) Changes to internally used parameters in Podcast_Album::BL_contained_items():
         Old: filter_limit,  paging_controls
         New: results_limit, results_paging
    2) Now PSR-2 Compliant
  classes/class.poll.php                                                                         1.0.10    (2014-02-17)
    1) Refreshed fields list - now declared as a class constant
  classes/class.poll_choice.php                                                                  1.0.4     (2014-02-17)
    1) Refreshed fields list - now declared as a class constant
  classes/class.portal.php                                                                       1.0.35    (2015-03-23)
    1) Portal::get_request_path() now allows \ in a path -
       This is needed for namespace determination, e.g.
         http://desktop.churchesinyourtown.ca/_map?type=\Map\GeocodeCache&ID=709381220...
    2) Method get_version() renamed to getVersion() and made static
  classes/class.posting.php                                                                      1.0.121   (2015-03-22)
    1) Added `map_geocode_address` to fields list
    2) Posting::get_coords() now includes map_geocode_address
  classes/class.posting_contained.php                                                            1.0.242   (2015-02-01)
    1) Changed call in Posting_Contained::_get_records_sort_records()
         from $this->_get_records_sort_records_using_filter_order_by()
         to   $this->_get_records_sort_records_using_results_order()
    2) Changed call in Posting_Contained::_get_records_sort_records_by_sequence()
         from $this->_get_records_sort_records_using_filter_order_by()
         to   $this->_get_records_sort_records_using_results_order()
    3) Changes internal arguments for Posting_Contained::get_records_matching()
         Old: filter_limit,  filter_order_by
         New: results_limit, results_order
    4) Changes to internal arguments for Posting_Contained::_draw_listings_load_records()
         Old: filter_limit,  filter_order_by
         New: results_limit, results_order
    5) Now PSR-2 Compliant
  classes/class.posting_container.php                                                            1.0.4     (2012-11-21)
    1) Now Posting_Container::set_default_enclosure_folder() checks the parent
       folder if there is one, and then builds default folder based on parent
       path combined with the name of the object represented.
       Also checks that the path is properly prefixed ad suffixed with a slash
  classes/class.prayer_request.php                                                               1.0.0     (2015-02-14)
    1) Initial release - Moved from Church Module
  classes/class.product.php                                                                      1.0.77    (2015-02-01)
    1) Changes to Product::get_records() to rename some expected arguments to conform to other classes:
         Old: order_by,      limit
         New: results_order, results_limit
    2) Class constant Product::fields renamed to Product::FIELDS
    2) Now PSR-2 Compliant
  classes/class.product_catalogue.php                                                            1.0.31    (2012-10-17)
    1) Product_Catalogue::_draw_setup_load_product_groupings() now sets item field
       'has_second_row' for each item loaded
    2) Product_Catalogue::_draw_item_description() now checks each item's
       'has_second_row' value to decide whether or not to skip.
    3) Product_Catalogue::_has_catalogue_row_description() now only returns true
       where an entry HAS an image value, the column list includes Image and
       the $this->_image flag is set indicating that images are to be shown
    4) Product_Catalogue::_draw_item_credit_memo() now spans one additional column
       since quantity was removed from columns count now it spans two rows.
  classes/class.product_catalogue_checkout.php                                                   1.0.5     (2012-10-17)
    1) Product_Catalogue_Checkout::_draw_item_quantity() now ONLY spans two rows
       IF item's 'has_second_row' flag is true
  classes/class.product_catalogue_credit_memo.php                                                1.0.2     (2012-10-17)
    1) Product_Catalogue_Credit_Memo::_draw_item_quantity() now ONLY spans two rows
       IF item's 'has_second_row' flag is true
  classes/class.product_catalogue_order_history.php                                              1.0.4     (2012-10-17)
    1) Product_Catalogue_Order_History::_draw_item_quantity() now ONLY spans two rows
       IF item's 'has_second_row' flag is true
  classes/class.product_catalogue_shop.php                                                       1.0.6     (2012-10-17)
    1) Product_Catalogue_Shop::_draw_item_quantity() now ONLY spans two rows
       IF item's 'has_second_row' flag is true
  classes/class.product_grouping.php                                                             1.0.8     (2011-08-26)
    1) Renamed class to Product_Grouping and changed table name to improve consistency
    2) Changed references to `product`.`categoryID` to `product`.`groupingID`
  classes/class.product_grouping_column.php                                                      1.0.1     (2011-08-26)
    1) Changed references to Product_Category to Product_Grouping
  classes/class.product_relationship.php                                                         1.0.4     (2011-10-19)
    1) Change to Product_Relationship::draw_combo_product_relationship() to
       reference `effective_date_start`
  classes/class.push_product.php                                                                 1.0.8     (2011-11-21)
    1) Initial Release
  classes/class.push_product_assign.php                                                          1.0.1     (2011-11-21)
    1) Push_Product_Assign::set_for_assignment() - bug fix
  classes/class.qrcode.php                                                                       1.0.0     (2011-12-01)
    Initial release
  classes/class.quickbooks.php                                                                   1.0.31    (2012-12-10)
    1) Changes to handle embedded ampersand in customer data:
       QuickBooks::_qbwc_wrap_XML() now replaces any standalone ampersands with
       html entity variant
  classes/class.rating.php                                                                       1.0.5     (2014-01-28)
    1) Includes newlines after JS blocks in Rating::draw()
  classes/class.record.php                                                                       1.0.90    (2015-03-23)
    1) Record::get_coords() now uses \Map\GoogleMap class
    2) Record::on_action_set_map_location() now looks for getCoords() if it exists and uses that
    3) Method get_version() renamed to getVersion() and made static
  classes/class.refunditem.php                                                                   1.0.1     (2010-10-04)
    1) Changes to setter and getter names for parent-based object properties
  classes/class.register_event.php                                                               1.0.23    (2014-01-28)
    1) Refreshed fields list - now declared as a class constant
  classes/class.remote.php                                                                       1.0.11    (2015-01-31)
    1) Changes to Remote::get_items() to use newer argument parameters to retrieve records:
         Old: limit
         New: results_limit
    2) Now PSR-2 Compliant
  classes/class.report.php                                                                       1.0.86    (2015-03-23)
    1) Report::handle_copy() now looks for $Obj->handleReportCopy() and uses that if it exists in place of old
       camelCased variant $Obj->handle_report_copy()
    2) Method get_version() renamed to getVersion() and made static
  classes/class.report_column.php                                                                1.0.129   (2015-03-23)
    1) Report_Column::draw_form_field() for type 'fieldset_map_loc_lat_lon' now url encodes type parameter in link
       so we can safely propagate namespace prefixes for desired types
    2) Method get_version() renamed to getVersion() and made static
  classes/class.report_column_download_pdf.php                                                   1.0.2     (2013-10-30)
    1) Report_Column_Download_PDF::_setup() modified to handle XML fields
    2) Report_Column_Download_PDF::_merge_mapfile_with_data() now handles extra
       tabs as separator
  classes/class.report_column_report_field.php                                                   1.0.29    (2015-03-23)
    1) Report_Column_Report_Field::draw() for type 'fieldset_map_loc_lat_lon' now url encodes type parameter in link
       so we can safely propagate namespace prefixes for desired types
    2) Method get_version() renamed to getVersion() and made static
  classes/class.report_column_type.php                                                           1.0.0     (2009-07-02)
    Initial release
  classes/class.report_config.php                                                                1.0.7     (2012-12-03)
    1) Removed ini_set() that forces display_errors on
  classes/class.report_defaults.php                                                              1.0.3     (2014-02-18)
    1) Refreshed fields list - now declared as a class constant
  classes/class.report_filter.php                                                                1.0.16    (2014-02-18)
    1) Refreshed fields list - now declared as a class constant
  classes/class.report_filter_criteria.php                                                       1.0.2     (2010-10-04)
    1) Changes to setter and getter names for parent-based object properties
  classes/class.report_form.php                                                                  1.0.62    (2015-03-22)
    1) Report_Form::_prepare_fields() - support for 'fieldset_map_loc_lat_lon' is now reduced to read-only
  classes/class.report_form_field_lookup.php                                                     1.0.2     (2014-03-17)
    1) Now if multiple options are provided as XML for report_field, mode can also
       be specified for each option
       Report_Form_Field_Lookup::_setup_get_filter_criteria() now looks for mode
       items as well as field
  classes/class.report_report.php                                                                1.0.29    (2015-01-10)
    1) Now PSR-2 Compliant
  classes/class.report_settings.php                                                              1.0.5     (2014-02-21)
    1) Report_Settings::delete_settings_for_filter() now removes filter settings
       record if there are no filters to show
  classes/class.rss.php                                                                          1.0.29    (2015-01-31)
    1) Changes to RSS::_serve_get_records() to rename internal arguments for getting records:
         Old: limit,         order_by
         New: results_limit, results_order
    2) Changes to RSS::_serve_setup() to rename internal arguments for getting records:
         Old: limit
         New: results_limit
    3) Moved RSS_Help into its own class file
    4) Now PSR-2 Compliant
  classes/class.rss_help.php                                                                     1.0.0     (2015-02-01)
    1) Initial release - split from RSS class file
  classes/class.rss_proxy.php                                                                    1.0.1     (2011-12-31)
    1) Replaced deprecated ereg_replace functions which would fail in newer PHP
  classes/class.scheduled_task.php                                                               1.0.4     (2014-01-28)
    1) Refreshed fields list - now declared as a class constant
  classes/class.search.php                                                                       1.0.10    (2014-01-21)
    1) Changes to allow for searching by communityID
  classes/class.services_json.php                                                                1.0.1     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.shipping.php                                                                     1.0.6     (2013-10-31)
    1) Achived changelog
  classes/class.smtp.php                                                                         2.0.0     (2010-06-09)
    1) New release using class that ships with PHPMailer 5.1
  classes/class.snoopy.php                                                                       1.2.5     (2015-03-23)
    1) Method get_version() renamed to getVersion() and made static
  classes/class.socketwriteread.php                                                              1.0.0     (2015-03-23)
    1) Moved from class.akismet.php
  classes/class.sponsorship_plan.php                                                             1.0.4     (2012-11-22)
    1) Sponsorship_Plan constructor now sets subtype to 'sponsorship-plan' but
       leaves type as determined by the parent, in this case 'gallery-album'
  classes/class.state_province.php                                                               1.0.4     (2011-06-01)
    1) Removed State_Province::draw_selector() - now obsolete
  classes/class.survey.php                                                                       1.0.17    (2013-03-08)
    1) Changes to standard CPs to include options such as showing title or date
  classes/class.survey_block.php                                                                 1.0.5     (2013-09-09)
    1) Changes to apply classnames to table cells for better css control
  classes/class.survey_response.php                                                              1.0.1     (2010-10-04)
    1) Changes to setter and getter names for parent-based object properties
  classes/class.system.php                                                                       1.0.160   (2015-03-15)
    1) Changed get_version() to getVersion() to prevent cascade by namespaced components through this class
       resulting in wrong version code being returned by extending classes
  classes/class.system_copy.php                                                                  1.0.7     (2012-08-22)
    1) Now remaps posting parents to their new copied parents
    2) Now remaps page parents to their new copied parents
  classes/class.system_edit.php                                                                  1.0.33    (2015-03-08)
    1) Now uses namespaced \Component\CalendarSmall for calendar preview, not Component_Calendar_Small
  classes/class.system_export.php                                                                1.0.18    (2015-01-10)
    1) Changed references from System::tables to System::TABLES
  classes/class.system_health.php                                                                1.0.46    (2015-03-23)
    1) System_Health::_getConfigClasses() now only looks for getVersion() and not get_version() as before
       Call is also static and no longer requires checked classes to be instantiated, saving about 1MB of memory
    2) Method get_version() renamed to getVersion() and made static
  classes/class.table.php                                                                        1.0.5     (2012-09-08)
    1) Tweak to table::get_checksum() to no longer ignore fields prefixed with word
       'module' - new modules should from now on use XML fields instead of hacking
       database structure
  classes/class.tax_code.php                                                                     1.0.1     (2014-01-28)
    1) Refreshed fields list - now declared as a class constant
  classes/class.tax_regime.php                                                                   1.0.15    (2014-01-28)
    1) Refreshed fields list - now declared as a class constant
  classes/class.tax_rule.php                                                                     1.0.1     (2010-10-04)
    1) Changes to setter and getter names for parent-based object properties
  classes/class.tax_zone.php                                                                     1.0.4     (2012-12-03)
    1) Tax_Zone::copy() now has same signature as Record::copy()
  classes/class.theme.php                                                                        1.0.8     (2014-02-17)
    1) Refreshed fields list - now declared as a class constant
  classes/class.transformer.php                                                                  1.0.5     (2012-09-03)
    1) Changes to extend from Record class and use machinery there where required
    2) Removed Transformer::admin() -
       never accessed now since there is no longer a CKEditor plugin that is
       compatible with it
  classes/class.uploader.php                                                                     1.0.6     (2011-05-05)
    1) Change to Uploader::do_upload() to unlink old file if it exists before
       trying to rename new file to old file name
  classes/class.user.php                                                                         1.0.7     (2014-02-17)
    1) User::update_logon_count() now validates updated fields against field list
  classes/class.widget.php                                                                       1.0.9     (2014-02-18)
    1) Refreshed fields list - now declared as a class constant
  classes/class.xml2array.php                                                                    1.0.1     (2010-10-18)
    1) Now moved into classes folder and included automagically
    2) Minor tidy up and added get_version()
  classes/class.xml_sitemap.php                                                                  1.0.3     (2015-01-26)
    1) Disallowed robots from indexing UserFiles File, Media and Video subfolders
    2) Now PSR-2 Compliant
  classes/component/activitytabber.php                                                           1.0.6     (2015-03-04)
    1) Moved from Component_Activity_Tabber and reworked to use namespaces
    2) Now Fully PSR-2 compliant
  classes/component/adminpersonlookup.php                                                        1.0.5     (2015-03-08)
    1) Moved here from class.component_admin_person_lookup.php and reworked for namespaces
  classes/component/articlesrotator.php                                                          1.0.8     (2015-03-07)
    1) Moved from Component_Articles_Rotator and reworked to use namespaces
    2) Now has up-to-date constructor-based setup
    3) Greatly simplified code by splitting into smaller helper classes
  classes/component/base.php                                                                     1.0.3     (2015-03-17)
    1) \Component\Base::drawStatus() now uses new HTML::drawStatus() in place of old snake-case named method
  classes/component/biblelinks.php                                                               1.0.1     (2015-03-04)
    1) Moved from Component_Bible_Links and reworked to use namespaces
    2) Now Fully PSR-2 compliant
  classes/component/breadcrumbs.php                                                              1.0.5     (2015-03-08)
    1) Moved from class.component_breadcrumbs.php and reworked to use namespaces
    2) Now with much more modern component setup
  classes/component/calendarlarge.php                                                            1.0.29    (2015-03-11)
    1) Moved in here from class.component_calendar_large.php
    2) Extensivly refactored, and now uses Block Layout renderer for context menu
    3) Now fully PSR-2 Compliant
  classes/component/calendarsmall.php                                                            1.0.4     (2014-03-08)
    1) Moved in here from class.component_calendar_small.php
  classes/component/calendaryearly.php                                                           1.0.1     (2015-03-14)
    1) Moved in here from class.component_calendar_yearly.php
    2) Some refactoring to separate setup and draw operations
    3) Now fully PSR-2 Compliant
  classes/component/categorystacker.php                                                          1.0.4     (2015-03-14)
    1) Moved in here from class.component_category_stacker.php
    2) Extensivly refactored
    3) Now fully PSR-2 Compliant
  classes/component/categorytabber.php                                                           1.0.5     (2015-03-14)
    1) Moved in here from class.component_category_tabber.php
    2) Extensivly refactored
    3) Now fully PSR-2 Compliant
  classes/component/changepassword.php                                                           1.0.2     (2015-03-17)
    1) Moved from Component_Change_Password and reworked to use namespaces
    2) Now Fully PSR-2 compliant
  classes/component/collectionviewer.php                                                         1.0.52    (2015-03-07)
    1) Moved here from class.component_collection_viewer.php and reworked to use namespaces
  classes/component/combotabber.php                                                              1.0.11    (2015-03-17)
    1) Moved from Component_Combo_Tabber and reworked to use namespaces
    2) Now Fully PSR-2 compliant
  classes/component/communitiesdisplay.php                                                       1.0.6     (2015-03-17)
    1) Moved from Component_Change_Password and reworked to use namespaces
    2) Now Fully PSR-2 compliant
  classes/component/communitycalendar.php                                                        1.0.2     (2015-03-13)
    1) Now uses namespaces and is fully PSR-2 Compliant
    2) Now extends \Component\CalendarLarge and modified to suit that object
  classes/component/communitycollectionviewer.php                                                1.0.1     (2015-03-07)
    1) Now namespaced and PSR-2 compliant
  classes/component/communitymembercalendar.php                                                  1.0.2     (2015-03-13)
    1) Now extends \Component\CalendarLarge and modified to suit that object
  classes/component/contentblock.php                                                             1.0.1     (2015-03-17)
    1) Moved from Component_Content_Block and reworked to use namespaces
    2) Now Fully PSR-2 compliant
  classes/component/contentgroupmembermirror.php                                                 1.0.4     (2015-03-17)
    1) Moved from Component_Content_Group_Member_Mirror and MAJORLY reworked to use namespaces
    2) Now Fully PSR-2 compliant
  classes/component/contentsigninmirror.php                                                      1.0.3     (2015-03-17)
    1) Moved from Component_Content_Signin_Mirror and MAJORLY reworked to use namespaces
    2) Now Fully PSR-2 compliant
  classes/component/dailybibleverse.php                                                          1.0.1     (2015-03-04)
    1) Moved from Component_Daily_Bible_Verse and reworked to use namespaces
    2) Now Fully PSR-2 compliant
  classes/component/emailunsubscribe.php                                                         1.0.1     (2015-03-01)
    1) Moved from Component_Email_Unsubscribe and reworked to use namespaces
    2) Now calls Mail_Queue_Item::viewMessagesForPerson() to list messages
    3) Fully PSR-2 compliant
  classes/component/wowslider.php                                                                1.0.9     (2015-03-02)
    1) Moved from Component_WOW_Slider and reworked to use namespaces
    2) Moved optional anchor for associated links inside Block Layout context Div to confrm to XHTML strict
    3) Fully PSR-2 compliant
  classes/map/addresssubstitution.php                                                            1.0.0     (2015-03-23)
    1) Initial Release
  classes/map/geocodecache.php                                                                   1.0.5     (2015-03-23)
    1) Moved to map namespace and made PSR-2 compliant
    2) Renamed export_sql() to exportSql
  classes/map/googlemap.php                                                                      1.0.0     (2015-03-23)
    1) Moved here from class.google_map.php which is now just a stub class
    2) References to class Geocode_Cache now point to map\geocodeCache

2374.sql
  1) New table address_substitution
  2) Changes to geocode-cache report to fix icon and permissions on system column
  3) New report 'address-substitution'
  4) Updated ECL tag 'object_draw_map' to use namespaced \Map\GoogleMap
  5) Set version information

Delete:
    class.geocode_cache.php                           1.0.4

Promote:
  codebase.php                                        3.3.0
  classes/  (307 files changed)
    class.action.php                                  1.0.22    CS:92d1cc84
    class.activity.php                                1.0.19    CS:1a17c13d
    class.ajax.php                                    1.0.24    CS:8121b561
    class.akismet.php                                 0.41.f    CS:920d6584
    class.array2xml.php                               1.0.1     CS:76b4c130
    class.article.php                                 1.0.39    CS:9769ae0b
    class.backup.php                                  1.1.10    CS:cc614105
    class.barcode.php                                 1.0.4     CS:eca9ae45
    class.base.php                                    1.0.14    CS:50b22484
    class.base_error.php                              1.0.2     CS:8fb940e8
    class.beanstream_gateway.php                      1.0.5     CS:37b53ef6
    class.block_layout.php                            1.0.62    CS:20805925
    class.bugtracker.php                              1.0.7     CS:3b49ccfd
    class.captcha.php                                 1.0.1     CS:99d91fea
    class.cart.php                                    1.0.6     CS:26808dcf
    class.category.php                                1.0.2     CS:46d8b827
    class.category_assign.php                         1.0.5     CS:57230d08
    class.cezpdf.php                                  1.0.1     CS:d9fe0916
    class.chasepaymentech_gateway.php                 1.0.7     CS:98e8b339
    class.checkout.php                                1.0.44    CS:47303759
    class.ckfinder.php                                1.0.6     CS:3d198b83
    class.colour_scheme.php                           1.0.2     CS:751e9c1b
    class.comment.php                                 1.0.19    CS:4c1c5efe
    class.community.php                               1.0.116   CS:2dfbd34c
    class.community_article.php                       1.0.3     CS:476321bf
    class.community_display.php                       1.0.39    CS:f30c2eb0
    class.community_event.php                         1.0.3     CS:77c9ced9
    class.community_member.php                        1.0.107   CS:119671b8
    class.community_member_article.php                1.0.3     CS:84103817
    class.community_member_display.php                1.0.41    CS:29232f8d
    class.community_member_event.php                  1.0.3     CS:47c3440c
    class.community_member_news_item.php              1.0.3     CS:48d0f669
    class.community_member_podcast.php                1.0.3     CS:ca21628a
    class.community_member_posting.php                1.0.4     CS:90658d1d
    class.community_member_resource.php               1.0.7     CS:7f75cc7e
    class.community_member_summary.php                1.0.20    CS:dafb83ff
    class.community_membership.php                    1.0.5     CS:ce7e4fe5
    class.community_news_item.php                     1.0.3     CS:6c9c12db
    class.community_podcast.php                       1.0.3     CS:df75b8e0
    class.community_posting.php                       1.0.5     CS:7b6a1d32
    class.community_resource.php                      1.0.4     CS:828f888e
    class.community_sponsorship_plan.php              1.0.3     CS:7cb49d72
    class.component.php                               1.0.108   CS:50dd744b
    class.component_base.php                          1.0.22    CS:bff1a982
    class.component_custom_form.php                   1.0.4     CS:5ecb7eba
    class.component_customiser_button.php             1.0.2     CS:ef9c0329
    class.component_document_covers.php             * 1.0.0     CS:6688f643   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_document_reader.php             * 1.0.0     CS:bc5e3735   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_donate.php                      * 1.0.2     CS:b31ac9e    * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_edit_your_profile.php           * 1.0.0     CS:4e3201fb   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_email_form.php                  * 1.0.1     CS:ecfa3f     * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_email_newsletter_signup.php     * 1.0.0     CS:e7d1a4a5   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_email_opt_in.php                * 1.0.0     CS:2a95c052   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_email_opt_out.php               * 1.0.0     CS:e81f3450   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_email_to_friend.php             * 1.0.0     CS:3ffac8bb   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_event_registration.php          * 1.0.7     CS:99d0ea24   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_events_map.php                  * 1.0.2     CS:c46defc2   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_facebook_like.php               * 1.0.4     CS:c085d746   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_flickr.php                      * 1.0.0     CS:d56be417   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_folder_viewer.php               * 1.0.0     CS:86a2a83f   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_forgotten_password.php          * 1.0.1     CS:b0cf42f    * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_form.php                        * 1.0.0     CS:b6150d41   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_gallery_album.php               * 1.0.71    CS:ed1056b2   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_gallery_album_gallery_image.php * 1.0.3     CS:c626f759   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_gallery_fader.php               * 1.0.42    CS:9a36a919   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_gallery_thumbnails.php          * 1.0.35    CS:951867e4   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_gc_weather.php                  * 1.0.0     CS:169d1f1b   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_google_map.php                  * 1.0.0     CS:6d601124   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_google_plusone.php              * 1.0.0     CS:f9750402   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_group_member_redirector.php     * 1.0.1     CS:ec0b11a2   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_image_fader.php                 * 1.0.1     CS:207af46e   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_image_gallery.php               * 1.0.0     CS:96ee1bf8   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_image_text.php                  * 1.0.0     CS:fafb7e6f   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_importer.php                    * 1.0.2     CS:5fad515f   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_inline_signin.php               * 1.0.2     CS:27adf516   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_jumploader.php                  * 1.0.4     CS:be809f93   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_language_button.php             * 1.0.1     CS:2fe3a328   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_member_search.php               * 1.0.12    CS:84db68     * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_nav_links.php                   * 1.0.1     CS:e7db485d   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_order_detail.php                * 1.0.6     CS:2716b1d0   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_password_protect.php            * 1.0.3     CS:ae399f55   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_paypal_return.php               * 1.0.1     CS:3bad72af   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_persons_listing.php             * 1.0.1     CS:49ce06c0   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_persons_map.php                 * 1.0.1     CS:cb7d23f7   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_poll.php                        * 1.0.2     CS:6adce7d1   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_poll_archive.php                * 1.0.1     CS:e0eb4058   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_prayer_request.php              * 1.0.0     CS:ec7d1b95   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_prev_next.php                   * 1.0.1     CS:267e26b9   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_product_dropdown.php            * 1.0.2     CS:2596d391   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_random_news.php                 * 1.0.1     CS:22f34d84   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_related_block.php               * 1.0.4     CS:9a9bef64   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_remote_page_content.php         * 1.0.0     CS:d95e2965   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_rss_displayer.php               * 1.0.1     CS:6b040a4c   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_rss_headlines.php               * 1.0.0     CS:5356657b   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_sdmenu.php                      * 1.0.5     CS:e6611345   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_search_date_picker.php          * 1.0.0     CS:f77806be   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_search_tag_cloud.php            * 1.0.0     CS:294f51a5   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_search_word_cloud.php           * 1.0.0     CS:a4190296   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_secure_email.php                * 1.0.0     CS:4ca1ff40   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_share_this.php                  * 1.0.2     CS:adbdf122   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_shop.php                        * 1.0.8     CS:12926bb6   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_signin.php                      * 1.0.2     CS:d677ac65   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_signup.php                      * 1.0.2     CS:2ecaeb26   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_sitemap.php                     * 1.0.2     CS:7f995aac   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_social_icon.php                 * 1.0.1     CS:11f08556   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_splash_page.php                 * 1.0.0     CS:e57f991e   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_subscribe.php                   * 1.0.1     CS:6138f722   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_survey.php                      * 1.0.0     CS:5b837778   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_time_tracker.php                * 1.0.3     CS:490806c    * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_twitter.php                     * 1.0.2     CS:951af625   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.component_video_player.php                  1.0.1     CS:64d49857
    class.contact.php                               * 1.0.5     CS:68e9b1eb   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.content_block.php                         * 1.0.9     CS:2bb73e0f   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.context_menu.php                          * 1.0.75    CS:97a23fac   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.country.php                               * 1.0.1     CS:39e46aa6   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.cpdf.php                                  * 1.0.0     CS:9fad0b3a   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.credit_memo.php                           * 1.0.11    CS:7e84cfe4   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.crm_case.php                              * 1.0.12    CS:6b5825b5   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.crm_case_task.php                         * 1.0.2     CS:12e74983   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.cron.php                                  * 1.0.10    CS:637aeb2d   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.curl.php                                  * 1.0.1     CS:f9de57b2   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.custom_form.php                           * 1.0.41    CS:6ded5682   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.custom_registration.php                   * 1.0.3     CS:daef5ff    * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.displayable_item.php                        1.0.152   CS:a113c418
    class.droplib.php                               * 2.1.0.c   CS:7b6eaed7   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.dtd.php                                   * 1.0.0     CS:a996428f   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.ecc_facebook.php                          * 1.0.0     CS:6f282a51   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.ecl_tag.php                               * 1.0.3     CS:76624a77   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.event.php                                 * 1.0.104   CS:bd6e926b   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.event_recurrence.php                      * 1.0.12    CS:d06fc64b   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.export.php                                  1.0.25    CS:c128e729
    class.extended_community.php                    * 1.0.0     CS:724bb4c2   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.fck.php                                   * 1.0.22    CS:bccc34f6   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.fdf.php                                   * 1.0.0     CS:62b82016   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.field_template.php                        * 1.0.1     CS:417ff657   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.filesystem.php                            * 1.0.17    CS:d9f859c2   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.font_face.php                             * 1.0.1     CS:690325e1   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.gallery_album.php                         * 1.0.33    CS:ebfb7c3d   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.gallery_image.php                         * 1.0.23    CS:ab525f99   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.gateway_setting.php                       * 1.0.4     CS:a9b30269   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.gateway_type.php                          * 1.0.2     CS:3cd3cf44   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.gc_weather.php                            * 1.0.0     CS:f4a8aeee   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.google_map.php                              1.0.48    CS:c6032914
    class.group.php                                 * 1.0.29    CS:5a3df4b0   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.group_assign.php                          * 1.0.2     CS:9bb48107   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.group_member.php                          * 1.0.5     CS:7c27c8f6   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.group_wizard.php                          * 1.0.13    CS:eb43bcd0   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.gwsocket.php                              * 1.0.0     CS:af934122   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.handler.php                               * 1.0.0     CS:11fa6a89   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.help.php                                  * 1.0.7     CS:28485daf   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.history.php                               * 1.0.5     CS:c07e3f8c   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.html.php                                  * 1.0.88    CS:8d223624   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.html2text.php                             * 1.0.1     CS:217d0b8b   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.http_raw_socket.php                       * 1.0.0     CS:7fed2e0d   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.image_factory.php                         * 1.0.10    CS:43335bdb   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.image_template.php                        * 1.0.2     CS:fa8e93a4   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.job_posting.php                           * 1.0.20    CS:51ce59fa   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.jsmin.php                                 * 1.0.0     CS:8aa9202b   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.jumploader.php                            * 1.0.7     CS:7fac1506   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.keyword.php                               * 1.0.10    CS:206de727   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.keyword_assign.php                        * 1.0.2     CS:86d9d3cd   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.language.php                              * 1.0.3     CS:5d71387    * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.language_assign.php                       * 1.0.1     CS:8974f40c   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.layout.php                                * 1.0.28    CS:ed75f955   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.link.php                                  * 1.0.6     CS:b2ae7f60   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.listdata.php                              * 1.0.5     CS:8c3cbbfa   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.listtype.php                              * 1.0.7     CS:43030c10   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.lst_delivery_method.php                   * 1.0.0     CS:90060428   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.lst_delivery_status.php                   * 1.0.0     CS:7147e852   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.lst_effective_period_unit.php             * 1.0.0     CS:60c1e9da   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.lst_language.php                          * 1.0.0     CS:2ba59612   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.lst_named_type.php                        * 1.0.4     CS:1a524390   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.lst_note_person_type.php                  * 1.0.0     CS:34916ccf   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.lst_payment_status.php                    * 1.0.2     CS:cf5b6db3   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.lst_persontitle.php                       * 1.0.0     CS:ccf0a81a   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.lst_product_type.php                      * 1.0.0     CS:53bcafa0   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.lst_refund_status.php                     * 1.0.0     CS:1678668a   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.mail_identity.php                         * 1.0.6     CS:69bbff18   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.mail_queue.php                            * 1.0.37    CS:fb64185b   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.mail_queue_item.php                       * 1.0.16    CS:6bf6c2a8   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.mail_template.php                         * 1.0.13    CS:f445df4f   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.media.php                                 * 1.0.3     CS:3830441c   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.media_audioplayer.php                     * 1.0.9     CS:fe405e6f   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.media_youtube.php                         * 1.0.6     CS:2782db0f   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.membership_rule.php                       * 1.0.2     CS:dd7c8104   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.navbutton.php                             * 1.0.16    CS:794b4d05   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.navbutton_image.php                       * 1.0.8     CS:af3654cb   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.navbutton_style.php                       * 1.0.8     CS:6c2e796c   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.navsuite.php                              * 1.0.33    CS:2d3598c6   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.news_item.php                             * 1.0.24    CS:590b2ea8   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.note.php                                  * 1.0.3     CS:773e3893   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.notification.php                          * 1.0.4     CS:8c117e0    * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.order.php                                 * 1.0.68    CS:6f1709ac   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.orderitem.php                             * 1.0.14    CS:2ef9938c   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.orders_overview.php                       * 1.0.1     CS:67bf2cca   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.page.php                                  * 1.0.120   CS:4cdbfe3a   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.page_edit.php                             * 1.0.16    CS:937b7914   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.page_vars.php                             * 1.0.25    CS:e354ad7b   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.password.php                              * 1.0.0     CS:66a7578e   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.payment_method.php                        * 1.0.10    CS:20303f63   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.paypal_gateway.php                        * 1.0.24    CS:7648cd63   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.pdf.php                                   * 1.0.1     CS:69fcfaf5   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.person.php                                * 1.0.124   CS:7050f56f   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.person_merge_profiles.php                 * 1.0.4     CS:87939c99   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.php_excel.php                             * 1.0.2     CS:deb8e278   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.phpmailer.php                             * 2.0.0     CS:43909226   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.phpop3.php                                  1.0.2     CS:448eb00
    class.piwik.php                                 * 1.0.2     CS:448fdcc9   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.podcast.php                               * 1.0.46    CS:a99c8211   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.podcast_album.php                         * 1.0.17    CS:dd665209   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.poll.php                                  * 1.0.10    CS:729a45e2   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.poll_choice.php                           * 1.0.4     CS:c918aab3   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.portal.php                                  1.0.35    CS:38d37dca
    class.posting.php                               * 1.0.121   CS:9235caf1   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.posting_contained.php                     * 1.0.242   CS:c2083d8d   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.posting_container.php                     * 1.0.4     CS:f77c2949   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.prayer_request.php                        * 1.0.0     CS:7c065020   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.product.php                               * 1.0.77    CS:44fa9e5b   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.product_catalogue.php                     * 1.0.31    CS:1ac7e5e1   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.product_catalogue_checkout.php            * 1.0.5     CS:38b354ad   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.product_catalogue_credit_memo.php         * 1.0.2     CS:4dde8ab1   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.product_catalogue_order_history.php       * 1.0.4     CS:ee35aea5   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.product_catalogue_shop.php                * 1.0.6     CS:825adfc5   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.product_grouping.php                      * 1.0.8     CS:d11c50cf   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.product_grouping_column.php               * 1.0.1     CS:d009a62d   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.product_relationship.php                  * 1.0.4     CS:aa53d5bc   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.push_product.php                          * 1.0.0     CS:b8bee9cb   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.push_product_assign.php                   * 1.0.1     CS:91df8dcd   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.qrcode.php                                * 1.0.0     CS:9552549b   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.quickbooks.php                            * 1.0.31    CS:adf6bf32   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.rating.php                                * 1.0.5     CS:c8d76c72   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.record.php                                  1.0.90    CS:500b2d64
    class.refunditem.php                            * 1.0.1     CS:541168c3   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.register_event.php                        * 1.0.23    CS:3ded3e43   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.remote.php                                * 1.0.11    CS:cc71c3ab   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.report.php                                  1.0.86    CS:76d71ef4
    class.report_column.php                           1.0.129   CS:6bcc221b
    class.report_column_download_pdf.php            * 1.0.2     CS:a8e3ea4c   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.report_column_report_field.php              1.0.29    CS:6cd62076
    class.report_column_type.php                    * 1.0.0     CS:a52ba350   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.report_config.php                         * 1.0.7     CS:f881457    * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.report_defaults.php                       * 1.0.3     CS:7fca07da   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.report_filter.php                         * 1.0.16    CS:32b52c59   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.report_filter_criteria.php                * 1.0.2     CS:84f1a66e   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.report_form.php                           * 1.0.62    CS:4a424d77   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.report_form_field_lookup.php              * 1.0.2     CS:5a6bfab7   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.report_report.php                         * 1.0.29    CS:942f30c0   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.report_settings.php                       * 1.0.5     CS:b42169f2   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.rss.php                                   * 1.0.29    CS:931cb4b9   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.rss_help.php                              * 1.0.0     CS:5477dd87   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.rss_proxy.php                             * 1.0.1     CS:ec9312af   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.scheduled_task.php                        * 1.0.4     CS:94b6eaa7   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.search.php                                * 1.0.10    CS:398a8795   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.services_json.php                           1.0.1     CS:a3b8a85c
    class.shipping.php                              * 1.0.6     CS:e1856dbc   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.smtp.php                                  * 2.0.0     CS:8fef1b22   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.snoopy.php                                * 1.2.4     CS:3da7574a   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.socketwriteread.php                         1.0.0     CS:ce3717a6
    class.sponsorship_plan.php                      * 1.0.4     CS:e0b40d3c   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.state_province.php                        * 1.0.4     CS:a8af9045   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.survey.php                                * 1.0.17    CS:70c09a2a   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.survey_block.php                          * 1.0.5     CS:4ff3b4d2   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.survey_response.php                       * 1.0.1     CS:355c5596   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.system.php                                * 1.0.160   CS:c369760a   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.system_copy.php                           * 1.0.7     CS:1d813fec   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.system_edit.php                           * 1.0.33    CS:e4d217e1   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.system_export.php                         * 1.0.18    CS:bf9c3574   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.system_health.php                           1.0.46    CS:fd9c4d6d
    class.table.php                                 * 1.0.5     CS:6d770ade   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.tax_code.php                              * 1.0.1     CS:557edda    * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.tax_regime.php                            * 1.0.15    CS:a92add9b   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.tax_rule.php                              * 1.0.1     CS:9f60a7a9   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.tax_zone.php                              * 1.0.4     CS:faeeccb8   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.theme.php                                 * 1.0.8     CS:84267997   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.transformer.php                           * 1.0.5     CS:d805fe7c   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.uploader.php                              * 1.0.6     CS:e58805cb   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.user.php                                  * 1.0.7     CS:6ec5a88    * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.widget.php                                * 1.0.9     CS:27582a66   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.xml2array.php                             * 1.0.1     CS:18dcc68c   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    class.xml_sitemap.php                           * 1.0.3     CS:799d9a53   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/activitytabber.php                    * 1.0.6     CS:81264bf4   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/adminpersonlookup.php                 * 1.0.5     CS:2e6bc18f   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/articlesrotator.php                   * 1.0.8     CS:c0cab53    * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/base.php                              * 1.0.3     CS:f8c825c    * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/biblelinks.php                        * 1.0.1     CS:cc9881c1   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/breadcrumbs.php                       * 1.0.5     CS:12fccf16   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/calendarlarge.php                     * 1.0.29    CS:46a68b34   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/calendarsmall.php                     * 1.0.4     CS:273dfc9f   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/calendaryearly.php                    * 1.0.1     CS:eda3cbbb   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/categorystacker.php                   * 1.0.4     CS:4693e61d   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/categorytabber.php                    * 1.0.5     CS:a8b4f1cf   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/changepassword.php                    * 1.0.2     CS:a787378c   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/collectionviewer.php                  * 1.0.52    CS:757d9c9    * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/combotabber.php                       * 1.0.11    CS:2823fb23   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/communitiesdisplay.php                * 1.0.6     CS:cf9f20c8   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/communitycalendar.php                 * 1.0.2     CS:945c0d6a   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/communitycollectionviewer.php         * 1.0.1     CS:621069cd   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/communitymembercalendar.php           * 1.0.2     CS:321cbad6   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/contentblock.php                      * 1.0.1     CS:87117391   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/contentgroupmembermirror.php          * 1.0.4     CS:d854e239   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/contentsigninmirror.php                 1.0.3     CS:4c5b2e63
    component/dailybibleverse.php                   * 1.0.1     CS:bf6fad81   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/emailunsubscribe.php                  * 1.0.1     CS:e552f820   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    component/wowslider.php                         * 1.0.9     CS:5c8ab7a6   * PROBLEM - VERSION NUMBER DID NOT CHANGE
    map/addresssubstitution.php                       1.0.0     CS:999e54da
    map/geocodecache.php                              1.0.5     CS:7bf13a72
    map/googlemap.php                                 1.0.0     CS:490a0482
  images/icons.gif                                              CS:fcf2a910



  4) Allow #easter to be used in place of #special on special events tab in community sites



  Bug:
    where two postings (e.g. gallery album and article) have same name and date
    search results will be shown instead:
    http://www.armsofjesus.org/2009/03/14/kariobangi-youth-center
    An sql script could probably find more examples
    Provide disambiguation based on ID?

--------------------------------------------------------------------------------
TODO:
  IMPLEMENT NEW get_var() throughout to remove some crazy globals where possible
  1) Make changing a gallery album parent remap all descendents
  2) This should work: http://desktop.saministryresources.ca/page/784780593
     Page needs to have draw_detail() same as for any other displayable item
  3) When mysql 5.5 is on all servers, consider making tables InnoDB rather than MYISAM
     http://www.oracle.com/partners/en/knowledge-zone/mysql-5-5-innodb-myisam-522945.pdf
  4) Introduction of event registration limits to allow cutoff after so many bookings
     have been made / sold

BUGS:
1.12.11.1289
  Transformer button image files should ideally include system ID to prevent one
  system with a button style of 'Main' clearing out cached images for another system whose buttons
  use that systems 'Main' style when admin updates 'Main' style in the other system
  Also transformer buttons cannot have apostrophes in text nor spaces in button style
1.12.2.1280
  1) Report Filters do NOT work in Dashboard layers (or ajax reports come to that) -
1.9.12.1261
  1) If IE7's zoom mode is other than 100%, current rating highlights stars in wrong location
1.9.8.1257
  1) If a button is edited and moved from one suite to another and the original suite now
     has NO buttons, system should delete original suite and remove mapping from parent button
1.8.7.1235
  1) Doesn't prevent two postings with same name or posting with same name as a page

CODE CHANGES COMING:
  1) Consider keyword mapping by value NOT ID
  2) Look for newer version of treeview for XHTML Srict

PROPOSED ENHANCEMENTS:
  1)  Have sitemap show which pages are missing for admins - add option to generate missing pages
  2)  NEED TO TEST WITH MYSQL STRICT MODE - USE SERVER INSTANCE CONFIG
  3)  Ensure that orders for people are deleted when person is removed.
  4)  Make recursive copy / single item copy options for navbuttons and treenodes
  5)  Make recursive export for treenodes
  6)  Prevent deletions of events with actions
  7)  Consider default layout with signin for new system.
  8)  When you delete a navbar, have the system check for pages that reference it
      and have these IDs set to 1 (no navbar)
  9)  When you delete a button, have the system check for navbars that have that
      button as the parent button and have these set to 1 (no parent)
  10) When you delete a button style, have the system prevent this if there
      are navsuites defined using it.
  11) Facebook: when user with a facebook profile registers for an event, have facebook
      make an announcement
  12) Have ability to configure context menus to choose shift right or other
     combinations, based on user's preferences when editing (james' suggestion)

--------------------------------------------------------------------------------
Dialog selector colour, label and ordering conventions:
  Order: (default), (none), Global, everything else
--------------------------------------------------------------------------------
                 MASTERADMIN              SYSADMIN
--------------------------------------------------------------------------------
                 BGColor Label            BGColor Label
Default        = ffffff  (default)        ffffff  (default)
None           = d0d0d0  (none)           d0d0d0  (none)
All Systems    = e0e0ff  * Item           e0e0ff  * Item
This System    = c0ffc0  SYSTEM | Item    c0ffc0  Item
Other system   = ffe0e0  SYSTEM | Item    (-never shown-)
Other value    = ffe0c0  (Other...)       ffe0c0  (Other...)
--------------------------------------------------------------------------------
Filter Icons:
[ICON]14 14 6912 (All Records - Unfiltered View)[/ICON](All)
[ICON]17 17 6751 Created Today[/ICON]
[ICON]17 17 6768 Modified Today[/ICON]
[ICON]21 21 7338 Created This Week[/ICON]
[ICON]21 21 7359 Modified This Week[/ICON]
[ICON]23 23 7380 Created This Month[/ICON]
[ICON]23 23 7403 Modified This Month[/ICON]
[ICON]22 22 7426 Created This Year[/ICON]
[ICON]22 22 7448 Modified This Year[/ICON]
[ICON]13 13 6598 Marked as Important - Yes[/ICON]
[ICON]13 13 6611 Marked as Important - No[/ICON]
[ICON]11 11 6576 Has Categories - Yes[/ICON]
[ICON]11 11 6587 Has Categories - No[/ICON]
[ICON]21 21 7506 Has been Published to Public - Yes[/ICON]
[ICON]21 21 7527 Has been Published to Public - No[/ICON]
[ICON]22 22 7548 Has been Published to Site Users - Yes[/ICON]
[ICON]22 22 7570 Has been Published to Site Users - No[/ICON]
[ICON]22 22 7592 Has been Published to Approved Site Members - Yes[/ICON]
[ICON]22 22 7614 Has been Published to Approved Site Members - No[/ICON]
[ICON]19 19 6370 Has been Published to Groups - Yes[/ICON]
[ICON]19 19 6389 Has been Published to Groups - No[/ICON]
[ICON]21 21 7674 Has been Shared - Yes[/ICON]
[ICON]21 21 7695 Has been Shared - No[/ICON]
[ICON]18 18 6334 Has Received Comments - Yes[/ICON]
[ICON]18 18 6352 Has Received Comments - No[/ICON]
[ICON]22 22 6444 Has Recurrences - Yes[/ICON]
[ICON]22 22 6466 Has Recurrences - No[/ICON]
[ICON]22 22 6488 Is a Recurrence - Yes[/ICON]
[ICON]22 22 6510 Is a Recurrence - No[/ICON]
[ICON]18 18 6408 Has Related Products - Yes[/ICON]
[ICON]18 18 6426 Has Related Products - No[/ICON]
[ICON]22 22 6532 Has Registrants - Yes[/ICON]
[ICON]22 22 6554 Has Registrants - No[/ICON]
[ICON]21 21 7041 Is a Podcast Root Folder[/ICON]
[ICON]23 23 7062 Is a Podcast Sub Folder[/ICON]
[ICON]17 17 7007 Contains Podcasts - Yes[/ICON]
[ICON]17 17 7024 Contains Podcasts - No[/ICON]
[ICON]20 20 6967 Contains other Podcast Albums - Yes[/ICON]
[ICON]20 20 6987 Contains other Podcast Albums - No[/ICON]
[ICON]20 20 7085 Inside a Podcast Album - Yes[/ICON]
[ICON]20 20 7105 Inside a Podcast Album - No[/ICON]
[ICON]21 21 7169 Is a Gallery Root Folder[/ICON]
[ICON]21 21 7190 Is a Gallery Sub Folder[/ICON]
[ICON]22 22 7214 Contains Gallery Images - Yes[/ICON]
[ICON]22 22 7236 Contains Gallery Images - No[/ICON]
[ICON]20 20 7258 Contains other Gallery Albums - Yes[/ICON]
[ICON]20 20 7278 Contains other Gallery Albums - No[/ICON]
[ICON]20 20 7298 Inside a Gallery Album - Yes[/ICON]
[ICON]20 20 7318 Inside a Gallery Album - No[/ICON]
[ICON]17 17 7470 Is a Top-Level Parent Page[/ICON]
[ICON]19 19 7487 Is a Nested Child Page[/ICON]
[ICON]19 19 7636 Is an Administrator - Yes[/ICON]
[ICON]19 19 7655 Is an Administrator - No[/ICON]

*/

// For when codebase is included without functions.php - e.g. ajax or cron
if (!function_exists('mem')) {
    function mem($label = '')
    {
        static $mem_usage = array();
        if ($label=='') {
            return($mem_usage);
        }
        $mem_usage[] =
        array(
            'lbl' =>            $label,
            'mem' =>            number_format(memory_get_usage()),
            'class_files' =>    includes_monitor()
        );
        return;
    }
}

if (!function_exists('includes_monitor')) {
    function includes_monitor($className = '', $filePath = '')
    {
        static $includes = array();
        if ($className==''){
            return $includes;
        }
        $includes[$className] = $filePath;
    }
}

if (!function_exists('memory_get_usage')) {
    function memory_get_usage()
    {
        if (substr(PHP_OS, 0, 3) == 'WIN') {
            if (substr(PHP_OS, 0, 3) == 'WIN') {
                $output = array();
                exec('tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $output);
                return preg_replace('/[\D]/', '', $output[5]) * 1024;
            }
        } else {
            $pid = getmypid();
            exec("ps -eo%mem,rss,pid | grep $pid", $output);
            $output = explode("  ", $output[0]);
            return $output[1] * 1024;
        }
    }
}




// ************************************
// * Definitions and Includes         *
// ************************************
session_name("ECC_".SYS_ID);
session_cache_limiter('must-revalidate');
ini_set('session.use_only_cookies', 1);
ini_set('session.use_trans_sid', false);
session_start();
if (!defined("SYS_BUTTONS")) {
    define("MONO_FONT", "veramono.ttf");
    define("SYS_BUTTONS", SYS_SHARED."buttons/");
    define("SYS_CLASSES", SYS_SHARED."classes/");
    define("SYS_FONTS", SYS_SHARED."fonts/");
    define("SYS_IMAGES", SYS_SHARED."images/");
    define("SYS_JS", SYS_SHARED."js/");
    define("SYS_STYLE", SYS_SHARED."style/");
    define("SYS_SWF", SYS_SHARED."swf/");
}
define(
    "SYS_RESERVED_URL_PARTS",
    "_popup_layer, .htaccess, article, ajax, category, command, cron, css, db_export,"
    ." details, email-view, event, export,"
    ." facebook, fck, img, index.php, java, job, js, js_context, logs, mode, news,"
    ." osd, page, piwik, podcast, print_form, report, resource, robots.txt, rss,"
    ." sitemap.xml, sysjs, tags, _ticket, userfiles, webmail, xhtml1-strict-with-iframe.dtd"
);
define("SYS_BUTTON_TEMPLATES", SYS_SHARED."button_templates/");
define("SYS_EXPORT", dirname($_SERVER["SCRIPT_FILENAME"])."/export/");
define("SYS_EXPORT_CODE", SYS_SHARED."export_code/");
define("SYS_FACEBOOK", SYS_SHARED."facebook/");
define("SYS_LOGS", "./logs/");
define("SYS_LOG_FILE", "debug.txt");
define("SYS_MODULES", SYS_SHARED."modules/");
define("OPTION_SEPARATOR", "[ECL]option_separator[/ECL]");
define(
    "SYS_STANDARD_FIELDS",
    "anchor,bulk_update,command,component_help,DD,filterExact,"
    ."filterField,filterValue,goto,limit,memberID,MM,mode,offset,print,report_name,rnd,"
    ."search_categories,search_date_end,search_date_start,search_keywords,search_offset,"
    ."search_text,search_type,selected_section,selectID,sortBy,source,submode,targetField,"
    ."targetFieldID,targetID,targetReportID,targetValue,topbar_search,YYYY"
);
define("SYS_UPGRADE_URL", "http://www.ecclesiact.com/");

mem('in codebase-1');
$_old_path = ini_get('include_path');
// Do this because encoded system.php blows away include_path for PEAR
include_once(SYS_SHARED."system.php");
ini_set('include_path', $_old_path);

include_once(SYS_SHARED."adodb-time.inc.php");
$custom_file = (defined("AJAX_VERSION") ? "../" : "./")."custom.php";
if (file_exists($custom_file)) {
    include_once($custom_file);
}

// This is called whenever system trys to create a non existant class of the given name
function __autoload($className)
{
    return portal_autoload($className);
}

function portal_autoload($className)
{
    if (class_exists($className)) {
        return;
    }
    $fileName = 'class.'.strToLower($className).'.php';
    $filePath = SYS_CLASSES.$fileName;
    if (file_exists($filePath)) {
        require_once($filePath);
        includes_monitor($className, $filePath);
        return;
    }
    $filePath = SYS_SHARED.$fileName;
    if (file_exists($filePath)) {
        require_once($filePath);
        includes_monitor($className, $filePath);
        return;
    }
    $fileName = str_replace('\\', DIRECTORY_SEPARATOR, strToLower($className).'.php');
    $filePath = SYS_CLASSES.$fileName;
    if (file_exists($filePath)) {
        require_once($filePath);
        includes_monitor($className, $filePath);
        return;
    }
//    print 'looking for '.$include_file;
}


spl_autoload_register('portal_autoload');

mem('in codebase-2');
$component_result = array();
// ************************************
// * Extract variables                *
// ************************************
extract($_REQUEST);        // Extracts all request variables (GET, COOKIE and POST) into global scope.
Cart::initialise();


// Added for PCI Compliance, 2006/01/06
// Test: enter this URL:
// http://www.auroraonline.com/?report_name="><script>alert('ScanAlert')</script><"
if (isset($anchor)) {
    $anchor =             sanitize('html', $anchor);
}
if (isset($bulk_update)) {
    $bulk_update =        sanitize('range', $bulk_update, 0, 1, 0);
}
if (isset($columnID)) {
    $columnID =           sanitize('ID', $columnID);
}
if (isset($command)) {
    $command =            sanitize('html', $command);
}
if (isset($component_help)) {
    $component_help =     sanitize('range', $component_help, 0, 1, 0);
}
if (isset($DD)) {
    $DD =                 sanitize('range', $DD, 1, 31, date('d'));
}
if (isset($filterExact)) {
    $filterExact =        sanitize('html', $filterExact);
}
if (isset($filterField)) {
    $filterField =        sanitize('html', $filterField);
}
if (isset($filterValue)) {
    $filterValue =        sanitize('html', $filterValue);
}
if (isset($goto)) {
    $goto =               sanitize('html', $goto);
}
if (isset($limit)) {
    $limit =              sanitize('html', $limit);
}
if (isset($memberID)) {
    $memberID =           sanitize('range', $memberID, 0, 'n', '');
}
if (isset($MM)) {
    $MM =                 sanitize('range', $MM, 1, 12, date('m'));
}
if (isset($mode)) {
    $mode =               sanitize('html', $mode);
}
if (isset($offset)) {
    $offset =             sanitize('html', $offset);
}
if (isset($page)) {
    $page =               sanitize('html', (substr($page, -1)=='\\' ? '' : $page));
}
if (isset($print)) {
    $print =              sanitize('html', $print);
}
if (isset($report_name)) {
    $report_name =        sanitize('html', $report_name);
}
if (isset($search_categories)) {
    $search_categories =  sanitize('html', $search_categories);
}
if (isset($search_date_end)) {
    $search_date_end =    sanitize('date-stamp', $search_date_end);
}
if (isset($search_date_start)) {
    $search_date_start =  sanitize('date-stamp', $search_date_start);
}
if (isset($search_keywords)) {
    $search_keywords =    sanitize('html', $search_keywords);
}
if (isset($search_name)) {
    $search_name =        sanitize('html', $search_name);
}
if (isset($search_offset)) {
    $search_offset =      sanitize('range', $search_offset, 0, 65535, 0);
}
if (isset($search_text)) {
    $search_text =        sanitize('html', $search_text);
}
if (isset($search_type)) {
    $search_type =        sanitize('enum', $search_type, array(
        '*','article','event','gallery-image','news-item','job-posting','page','podcast','product'
    ));
}
if (isset($selectID)) {
    $selectID =           sanitize('ID', $selectID);
}
if (isset($selected_section)) {
    $selected_section =   sanitize('html', $selected_section);
}
if (isset($sortBy)) {
    $sortBy =             sanitize('html', $sortBy);
}
if (isset($source)) {
    $source =             sanitize('html', $source);
}
if (isset($submode)) {
    $submode =            sanitize('html', $submode);
}
if (isset($targetID)) {
    $targetID =           sanitize('ID', $targetID);
}
if (isset($targetField)) {
    $targetField =        sanitize('html', $targetField);
}
if (isset($targetFieldID)) {
    $targetFieldID =      sanitize('ID', $targetFieldID);
}
if (isset($targetReportID)) {
    $targetReportID =     sanitize('ID', $targetReportID);
}
if (isset($targetValue)) {
    $targetValue =        sanitize('html', $targetValue);
}
if (isset($topbar_search)) {
    $topbar_search =      sanitize('html', $topbar_search);
}
if (isset($YYYY)) {
    $YYYY =               sanitize('range', $YYYY, 100, 3000, date('Y'));
}

 mem('in codebase-3');
//$memberID=564563;

// ************************************
// * Set Global Variables             *
// ************************************
global $anchor,$YYYY,$MM,$DD,$mode,$page,$goto,$img,$color,$command,$username,$password,$bulk_update,$system_vars;

$now =          time();
$YYYY =         ($YYYY=="" ? adodb_date('Y', $now) : $YYYY);
$MM =           ($MM==""   ? adodb_date('m', $now) : $MM);
$MM =           (strlen($MM)==1 ? "0".$MM : $MM);
$DD =           ($DD==""   ? adodb_date('d', $now) : $DD);
$DD =           (strlen($DD)==1 ? "0".$DD : $DD);

$Obj = new System(SYS_ID);
$Obj->define_URL_params();
$system_vars = get_system_vars();
date_default_timezone_set($system_vars['timezone']);
Base::registerModules();
Portal::parse_request();
mem('in codebase-4');

function absolute_path($html, $host_url)
{
    $html = str_replace('popWin(\'/', 'popWin(\''.$host_url, $html);
    $html = str_replace('/resource/', $host_url.'resource/', $html);
    $html = str_replace('&amp;soundFile=', '&amp;soundFile='.urlencode($host_url), $html);
    return preg_replace(
        "#(\/resource\/|action='|action=\"|href='|href=\"|src='|src=\"|background='|background=\"|url\()(\/)#",
        "$1".$host_url,
        $html
    );
}

function array_remove_value(&$array, $val = '')
{
    if (empty($array) || !is_array($array)) {
        return;
    }
    if (!in_array($val, $array)) {
        return;
    }
    $arr = $array;
    foreach ($arr as $key => $value) {
        if ($value == $val) {
            unset($arr[$key]);
        }
    }
    $array = array_values($arr);
}

function component_result($what, $supress_error = false)
{
    global $component_result;
    if (isset($component_result[$what])) {
        return $component_result[$what];
    }
    if ($supress_error) {
        return false;
    }
    return "<span style='color:#ff0000;font-weight:bold;'>".$what."</span>";
}

function component_result_set($var, $what)
{
    global $component_result;
    $component_result[$var] = $what;
}

function context($text, $search, $limit = -1)
{
    $text =   trim($text);
    $search = trim($search);
    $length = strlen($text);
    if ($search=='') {
        if ($limit==-1) {
            return $text;
        }
        $context =  substr($text, 0, $limit);
        return $context.($text==$context ? "" : "&hellip;");
    }
    $text = convert_html_to_safe_view($text);
    if ($limit==-1) {
        return preg_replace("/($search)/i", "<span class='highlight'>\\1</span>", $text);
    }
    $pos =    strpos(strtolower($text), strtolower($search));
    if ($pos>($limit/2)) {
        $pos =    strpos($text, " ", ($pos-($limit/2)));
    } else {
        $pos = 0;
    }
    $search_preg_safe =
    str_replace(
        array(
        '[',']','(',')','*'
        ),
        array(
        '\[','\]','\(','\)','\*'
        ),
        $search
    );
    $out =
    preg_replace(
        "/($search_preg_safe)/i",
        "<span class='highlight' style='color:red'>\\1</span>",
        substr($text, $pos, $limit)
    );
    if (strip_tags($out)!=$text) {
        if ($length> ($limit/2)+1+($pos>$limit? $pos : 0)) {
            $out .= "&hellip;";
        }
        if ($pos>0) {
            $out =    "&hellip;".$out;
        }
    }
    return $out;
}

function convert_a2r($num, $lower = false)
{
  // Converts arabic to roman numerals
  // Not optimised - 999 = CMXCIX not IM
  // Only goes up to 1000
  // To improve see http://www.howtocreate.co.uk/php/dnld.php?file=2&action=1
    $a2r[1] =       "i";
    $a2r[5] =       "v";
    $a2r[10] =      "x";
    $a2r[50] =      "l";
    $a2r[100] =     "c";
    $a2r[500] =     "d";
    $a2r[1000] =    "m";
    $out = "";
    for ($n = 1000; $n >= 1; $n=$n/10) {
        switch(floor($num / $n)) {
            case 1:
                $out .= $a2r[$n];
                break;
            case 2:
                $out .= $a2r[$n].$a2r[$n];
                break;
            case 3:
                $out .= $a2r[$n].$a2r[$n].$a2r[$n];
                break;
            case 4:
                $out .= $a2r[$n].$a2r[5 * $n];
                break;
            case 5:
                $out .= $a2r[5 * $n];
                break;
            case 6:
                $out .= $a2r[5 * $n].$a2r[$n];
                break;
            case 7:
                $out .= $a2r[5 * $n].$a2r[$n].$a2r[$n];
                break;
            case 8:
                $out .= $a2r[5 * $n].$a2r[$n].$a2r[$n].$a2r[$n];
                break;
            case 9:
                $out .= $a2r[$n].$a2r[10 * $n];
                break;
        }
      //Repeat with what is left
        $num = $num % $n;
    }
    return $lower ? $out : strToUpper($out);
}


function convert_embedded_audio($string)
{
//  return $string;
//  Replaces [audio: file.mp3|param-1|param-n]
    $pagebits = preg_split("/\[audio:/", $string);
    if (count($pagebits)<=1) {
        return $string;
    }
    $out = "";
    $plaintext = true;
    foreach ($pagebits as $bit) {
        if ($plaintext) {
            $out.= $bit;
        } else {
            $bit_arr =    preg_split("/\]/", $bit);
            $params =     trim(urldecode(array_shift($bit_arr)), ": {}");
            $Obj_AP =     new Media_Audioplayer($params);
            $out .=       $Obj_AP->draw_clip().implode("]", $bit_arr);
        }
        $plaintext = false;
    }
    return $out;
}

function convert_embedded_video($string)
{
//  Replaces [video: file.flv|file.jpg|width|height]
    $pagebits = preg_split("/\[video:/", $string);
    if (count($pagebits)<=1) {
        return $string;
    }
    $out = "";
    $plaintext = true;
    foreach ($pagebits as $bit) {
        if ($plaintext) {
            $out.= $bit;
        } else {
            $bit_arr =    preg_split("/\]/", $bit);
            $params =     trim(urldecode(array_shift($bit_arr)), ": {}");
            $params_arr = explode('|', $params);
            $Obj_VP =     new Component_Video_Player;
            $params = array(
            'path_flv' =>   (isset($params_arr[0]) ? $params_arr[0] : ''),
            'path_jpg' =>   (isset($params_arr[1]) ? $params_arr[1] : ''),
            'width' =>      (isset($params_arr[2]) ? $params_arr[2] : ''),
            'height' =>     (isset($params_arr[3]) ? $params_arr[3] : '')
            );
            $out.= $Obj_VP->draw('', $params, true).implode("]", $bit_arr);
        }
        $plaintext = false;
    }
    return $out;
}

function convert_embedded_youtube($string)
{
//  Replaces [youtube: URL|width|height|start]
    $pagebits = preg_split("/\[youtube/", $string);
    if (count($pagebits)<=1) {
        return $string;
    }
    $out = "";
    $plaintext = true;
    foreach ($pagebits as $bit) {
        if ($plaintext) {
            $out.= $bit;
        } else {
            $bit_arr =    preg_split("/\]/", $bit);
            $params =     trim(urldecode(array_shift($bit_arr)), ": {}");
            $params_arr = explode('|', $params);
            $url =        (isset($params_arr[0]) ? $params_arr[0] : '');
            $width =      (isset($params_arr[1]) ? $params_arr[1] : 240);
            $height =     (isset($params_arr[2]) ? $params_arr[2] : 180);
            $start =      (isset($params_arr[3]) ? $params_arr[3] : 0);
            $Obj_YT =     new Media_Youtube($url, $width, $height, $start);
            $out .=       $Obj_YT->draw_clip().implode("]", $bit_arr);
        }
        $plaintext = false;
    }
    return $out;
}


function convert_html_to_plaintext($html)
{
    return nl2br(strip_tags(preg_replace('`<br(?: /)?>([\\n\\r])`', '$1', $html)));
}

function convert_html_to_safe_view($value, $limit = 500)
{
    $value =    preg_replace('/\[LANG\]([^\|]*)\|/i', '&#91;LANG&#93;${1}|', $value);
    $value =    preg_replace('/\[\/LANG\]/i', '&#91;/LANG&#93;', $value);
    $value =    preg_replace('/\[ECL\]option_separator\[\/ECL\]/i', '[br]', $value);
    $value =    preg_replace('/<img[^>]*>/', '&lt;img&gt; ', $value);
    $value =    preg_replace('/<h([1-6])[^>]*>([^<]*)<\/h([1-6])>/', '[h${1}]${2}[/h${3}]', $value);
    $value =    preg_replace('/\[ECL\]field_([^\[]*)\[\/ECL\]/is', '&#91;FIELD&#93;${1}&#91;/FIELD&#93;', $value);
    $value =    preg_replace('/\[ECL\]([^\[]*)\[\/ECL\]/is', '&#91;ECL&#93;${1}&#91;/ECL&#93;', $value);
    $value =    preg_replace('/(\[ICON\]([^\[]*)\[\/ICON\])/is', '${1} &#91;ICON&#93;${2}&#91;/ICON&#93;', $value);
    $value =    preg_replace(
        '/(\[TRANSFORM\]([^\[]*)\[\/TRANSFORM\])/is',
        '&#91;TRANSFORM&#93;',
        $value
    ); // Strip Transformer tags
    $value =    preg_replace(
        '/(\[audio([^\[]*)\])/is',
        '&#91;AUDIO&#93;',
        $value
    ); // Strip Audio
    $value =    preg_replace(
        '/(\[youtube([^\[]*)\])/is',
        '&#91;YOUTUBE&#93;',
        $value
    ); // Strip Audio
    $value =    preg_replace(
        '/\s\s+/',
        ' ',
        strip_tags($value)
    );
    $value =    preg_replace(
        '/(&#91;LANG&#93;)([^\|]*)\|/i',
        "<span style='background-color:#ff8080;font-weight:bold;'>\${1}\${2}</span>",
        $value
    ); // Represent Lang tags
    $value =    preg_replace(
        '/(&#91;\/LANG&#93;)/i',
        "",
        $value
    ); // Represent Lang tags
    $value =    preg_replace(
        '/&lt;img&gt;/',
        "<span style='background-color:#c0ffc0;font-weight:bold;'>&lt;img&gt;</span>",
        $value
    );
    $value =    preg_replace(
        '/\[h([1-6]*)\]([^\[]*)\[\/h([1-6])\]/',
        "<span style='background-color:#c0c0ff;font-weight:bold;'>&lt;h\${1}&gt;\${2}&lt;/h\${3}&gt;</span>",
        $value
    );
    $value =    preg_replace(
        '/(&#91;FIELD&#93;)([^&]*)(&#91;\/FIELD&#93;)/is',
        "<span style='background-color:#ffe0e0;color:#ff0000;font-weight:bold;'>\${2}</span>",
        $value
    ); // Represent ECL Script tags
    $value =    preg_replace(
        '/(&#91;ECL&#93;)([^&]*)(&#91;\/ECL&#93;)/is',
        "<span style='background-color:#ffff00;font-weight:bold;'>\${1}\${2}\${3}</span>",
        $value
    ); // Represent ECL Script tags
    $value =    preg_replace(
        '/(&#91;ICON&#93;)([^\[]*)(&#91;\/ICON&#93;)/is',
        "<span style='background-color:#ffc0ff;font-weight:bold;'>\${1}\${2}\${3}</span>",
        $value
    ); // Represent ICON tags
    $value =    preg_replace(
        '/(&#91;AUDIO&#93;)/is',
        "<span style='background-color:#ffc0ff;font-weight:bold;'>\${1}</span>",
        $value
    ); // Display AUDIO tags
    $value =    preg_replace(
        '/(&#91;YOUTUBE&#93;)/is',
        "<span style='background-color:#ffc0ff;font-weight:bold;'>\${1}</span>",
        $value
    ); // Display YOUTUBE tags
    $value =    preg_replace('/\[br\]/is', "<br />", $value); // Strip ICON tags
    if ($limit) {
        $value =    (strlen($value) > $limit ? substr($value, 0, $limit)."..." : substr($value, 0, $limit));
    }
    return $value;
}


function convert_icons($string)
{
//  Replaces [ICON]aa bb cc title[/ICON]
//    aa=width
//    bb=padded width
//    cc=source image offset
//    title=title
    $pagebits = preg_split("/\[ICON\]|\[\/ICON\]/", $string);
    if (count($pagebits)<=1) {
        return $string;
    }
    $checksum = System::get_item_version('icons');
    $renderedbit = array();
    $out = "";
    $plaintext = true;
    foreach ($pagebits as $bit) {
        if ($plaintext) {
            $out.= $bit;
        } else {
            if (in_array($bit, $renderedbit)) {
                $out.= $renderedbit[$bit];
            } else {
                $bit_arr =      explode(' ', $bit);
                $width =        array_shift($bit_arr);
                $padded_width = array_shift($bit_arr);
                $padding =      $padded_width-$width;
                $offset =       array_shift($bit_arr);
                $text =         implode(' ', $bit_arr);
                $out.=
                "<img class=\"toolbar_icon\" src=\"".BASE_PATH."img/spacer\""
                ." alt=\"".$bit."\" title=\"".$text."\" width=\"".$width."\" height=\"16\""
                ." style=\"background-position:-".$offset."px 0px\" />"
                .($padding>0 ?
                    "<img class='b fl' style=\"border:none;\" src=\"".BASE_PATH."img/spacer\""
                    ." width=\"".$padding."\" height=\"16\" alt=\"\"/>"
                 :
                    ""
                 );
            }
        }
        $plaintext = !$plaintext;
    }
    return $out;
}

function convert_labels($string)
{
//  Replaces [LBL]aa|bb|title[/LBL]
//    aa =    classname
//    bb =    height (blank if not forced)
//    title = title
    $pagebits = preg_split("/\[LBL\]|\[\/LBL\]/", $string);
    if (count($pagebits)<=1) {
        return $string;
    }
    $out = "";
    $renderedbit = array();
    $plaintext = true;
    $checksum = System::get_item_version('labels');
    foreach ($pagebits as $bit) {
        if ($plaintext) {
            $out.= $bit;
        } else {
            if (in_array($bit, $renderedbit)) {
                $out.= $renderedbit[$bit];
            } else {
                $bit_arr =  explode('|', $bit);
                $class =    array_shift($bit_arr);
                $height =   array_shift($bit_arr);
                $text =     str_replace("\\n", "\n", implode(' ', $bit_arr));
                $out.=
                 "<img class='label lbl_".$class."'"
                .($height ? " style=\"height:".$height."px\"" : "")
                ." src=\"".BASE_PATH."img/spacer\""
                ." title=\"".$text."\""
                ." alt=\"".$class."\" />";
            }
        }
        $plaintext = !$plaintext;
    }
    return $out;
}

function convert_language_tags($string)
{
    return Language::convert_tags($string);
}

function convert_ssi_tokens($string)
{
//  Replaces [ssi:ID|PUsername] with SSI link
    $pagebits = preg_split("/\[ssi:/", $string);
    if (count($pagebits)<=1) {
        return $string;
    }
    $out = "";
    $plaintext = true;
    foreach ($pagebits as $bit) {
        if ($plaintext) {
            $out.= $bit;
        } else {
            $bit_arr =    preg_split("/\]/", $bit);
            $params =     explode('|', array_shift($bit_arr));
            if (count($params)==2) {
                $credentials = serialize(
                    array(
                    'i' =>  sanitize('html', $params[0]),
                    'p' =>  sanitize('html', $params[1])
                    )
                );
                $out.=
                BASE_PATH
                ."?command=ssi&amp;token="
                .XOREncrypt($credentials)
                .implode("]", $bit_arr);
            }
        }
        $plaintext = false;
    }
    return $out;
}

function convert_transforms($string)
{
  // J.F. - added in version 1.9.17
  // replaces [TRANSFORM]JSON Parameters[/TRANSFORM]
  // with its output as we want it to be seen
    $pagebits = preg_split("/\[TRANSFORM\]|\[\/TRANSFORM\]/", $string);
    if (count($pagebits)<=1) {
        return $string;
    }
    $out = "";
    $plaintext = true;
  // set up a variable to contain and keep track of any javascript that needs added
    $transformJS = array();
    foreach ($pagebits as $bit) {
        if ($plaintext) {
            $out.= $bit;
        } else {
          // we have to keep transforms in single-quoted strings or FCKEditor messes them up:
          // [TRANSFORM]'method':'fieldtype','name':'customButton','data':"
          // {'text':'Smiles','width':'70','style':'Main','height':'26','cssclass':'','url':'http://www.cicbv.ca'}
          // [/TRANSFORM]
          // Problem is, this is not valid JSON - see Example 3 at http://php.net/manual/en/function.json-decode.php
          // So we have to play this game to convert them here before attempting to JSON decode
            $bit = str_replace("'", '"', $bit);
            $params = json_decode("{".$bit."}", true);
            if ($params['method'] == 'fieldtype') {
             // we use the fieldtype class to transform this thing
                $context = isset($params['data']['context']) ? $params['data']['context'] : 'page';
                $ft = new Transformer($context, $params['name'], $params['data']);
                if ($ft->error) {
                    $out .= "Transform error: " . $ft->error;
                } else {
                    if (!array_key_exists($params['name'], $transformJS)) {
                        $out .= $ft->JS;
                        $transformJS[$params['name']] = 'done';
                    }
                    $out .= $ft->HTML;
                }
            }
        }
        $plaintext = !$plaintext;
    }
    return $out;
}

function convert_safe_to_php($string)
{
  // May occur more than once - ECL tags can recurse
    $string = convert_icons($string);
    $string = convert_labels($string);
    $string = convert_embedded_audio($string);
    $string = convert_embedded_youtube($string);
    $string = convert_embedded_video($string);
    $string = convert_ssi_tokens($string);
    $string = convert_transforms($string);
    $string = convert_ecl_tags($string);
    $string = convert_language_tags($string);
    return $string;
}

function convert_ecl_tags($string)
{
  // Thanks Vic for the great ideas here!
    static $global_ECL_arr;
  // If first pass, load ECL tags and PHP code into $global_ECL_arr array:
    if (!isset($global_ECL_arr)) {
        $Obj =              new ECL_Tag;
        $global_ECL_arr =   $Obj->get_all();
    }
  // Split on any remaining unconverted ECL tags
  // Exit if none found
    $pagebits =       preg_split("/\[ECL\]|\[\/ECL\]/", $string);
    if (count($pagebits)<=1) {
        return $string;
    }
    $out =            "";
    $renderedbit =    array();
    $plaintext =      true;   // Assume we are starting as plain text
    foreach ($pagebits as $bit) {
        if ($plaintext) {
            $out.= $bit;
        } else {
            $instance_name = '';
            $_bit =       $bit;
            $bit_arr =    explode(":", $bit);
            if (count($bit_arr)>1) {
                $bit =              array_shift($bit_arr);
                $instance_name =    implode(":", $bit_arr);
            }
            $bit = stripslashes(strToLower($bit));
            $idx = array_search($bit, $global_ECL_arr['tag']);
            if ($idx !== false) {
                $expr =
                 "\$instance_name = \"".$instance_name."\";\n"
                .$global_ECL_arr['php'][$idx];
                $out.= convert_safe_to_php(eval($expr));
            } else {
                $out.=
                 "<span style=\"background-color:#ffe0e0;color:#ff0000\""
                ." title=\"This ECL tag is not defined: [ECL]".$bit."[/ECL]\">"
                ."[ECL]".$_bit."[/ECL]</span>";
            }
        }
        $plaintext = !$plaintext;
    }
    return $out;
}

function deprecated($max_depth = 20)
{
    global $system_vars;
    $trace =      debug_backtrace();
    $message =    "";
    for ($i=1; $i<count($trace)&&$i<=$max_depth; $i++) {
        $message .=
         ($i>1 ? ", " : "")
        ."[".(count($trace)-$i-1)."] "
        .(isset($trace[$i]['class']) ? $trace[$i]['class']."::" : "")
        .$trace[$i]['function']."()"
        ." via ".$trace[$i]['file']
        ." line ".$trace[$i]['line'];
    }
    $level =      3;
    $operation =  'DEPRECATED';
    $source =     trim($system_vars['URL'], '/');
    do_log($level, $source, $operation, $message);
}

function do_log($level, $source, $operation, $message)
{
    global $system_vars;
    if (isset($system_vars)) {
        $build =        System::get_item_version('build');
        $URL =          $system_vars['URL'];
    } else {
// Only if failed even to read system
        $build =        CODEBASE_VERSION;
        $URL =          "";
    }
    switch ($level) {
        case 0:
        case 1:
        case 2:
            $log_prefix = "log_";
            break;
        case 3:
            $log_prefix = "error_";
            break;
    }
    mkdirs(SYS_LOGS, 0777);
    if (!file_exists(SYS_LOGS.".htaccess")) {
        $handle = fopen(SYS_LOGS.".htaccess", 'w');
        fwrite($handle, "order deny,allow\ndeny from all");
        fclose($handle);
    }
    $log_date =   date('Y-m-d');
    $log_file =   $log_prefix.$log_date.".txt";
    if (!file_exists(SYS_LOGS.$log_file)) {
        $header =
         "**********************************************************\r\n"
        .($URL ? "* ".pad($URL, 55)."*\r\n" : "")
        ."* ".pad($log_file, 55)."*\r\n"
        ."* Severity levels 0=info, 1=warning, 2=error, 3=critical *\r\n"
        ."**********************************************************\r\n"
        ."\r\n"
        ."Codes 0, 1 and 2 in log file\r\n"
        ."Code 3 in error file\r\n"
        ."\r\n"
        ."-------------------------------------------------------------------------------------------------------------"
        ."--------------------------------------------------------------------\r\n"
        ."YYYY-MM-DD hh:mm:ss SystemID     Version:        Lvl IP                PersonID     Source                   "
        ."               Operation            Message \r\n"
        ."-------------------------------------------------------------------------------------------------------------"
        ."--------------------------------------------------------------------\r\n";
        $handle = fopen(SYS_LOGS.$log_file, 'wa');
        fwrite($handle, $header);
        fclose($handle);
    }
    $now =        get_timestamp();
    $IP =         (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "No remote IP");
    $personID =   get_userID();
    $line =
     $now." "
    .pad("S:".SYS_ID, 13)
    ."V:".pad($build, 14)
    ."L:".$level." "
    ."I:".pad($IP, 16)
    ."P:".pad($personID, 11)
    .pad($source, 40)
    .pad($operation, 20)
    ." ".$message
    ." URL:".$_SERVER["REQUEST_URI"]
    .(isset($_SERVER["HTTP_REFERER"]) ? " Referer:".$_SERVER["HTTP_REFERER"] : "")
    ."\r\n"
    .($level==3 ? strip_tags(str_replace('<br />', "\r\n  ", x())) : "")
    ."\r\n";
    if (is_writable(SYS_LOGS.$log_file)) {
        if (!$handle = fopen(SYS_LOGS.$log_file, 'a')) {
            return false;
        } else {
            if (fwrite($handle, $line) === false) {
                echo "Cannot write to file ".SYS_LOGS.$log_file;
            }
            fclose($handle);
            return false;
        }
        return true;
    } else {
        echo "The file ".SYS_LOGS.$log_file." is not writable";
        return false;
    }
}

function do_sql_query($sql)
{
    deprecated();
    $Obj = new Record;
    return $Obj->do_sql_query($sql);
}

function do_tracking($status, $allow_redirect = true)
{
    return System::do_tracking();
}

function do_trace_log($operation = 'TRACE', $max_depth = 20)
{
    $trace = debug_backtrace();
    $message =    "";
    for ($i=1; $i<count($trace)&&$i<=$max_depth; $i++) {
        $message .=
         ($i>1 ? ", " : "")
        ."[".(count($trace)-$i-1)."] "
        .(isset($trace[$i]['class']) ? $trace[$i]['class']."::" : "")
        .$trace[$i]['function']."()"
        ." via ".$trace[$i]['file']
        ." line ".$trace[$i]['line'];
    }
    $level =      3;
    $source =     trim($system_vars['URL'], '/');
    do_log($level, $source, $operation, $message);
}

function draw_auto_form($report_name, $controls = 1, $alt_controls = '', $show_header = true)
{
    $Obj = new Report_Form;
    return $Obj->draw($report_name, $controls, $alt_controls, $show_header);
}

function draw_auto_form_inpage($report_name, $for_person = false)
{
    global $ID, $page_vars;
    if ($for_person) {
        $personID = get_userID();
        if ($personID == 0) {
            header("Location: ".BASE_PATH."signin");
        }
        $oldID = $ID;
        $ID =    $personID;
    }
    $out =
     $page_vars['content']
    ."<table width='400' class='minimal'>\n"
    ."  <tr>\n"
    ."    <td>".draw_auto_form($report_name, 0)."</td>\n"
    ."  </tr>\n"
    ."  <tr>\n"
    ."    <td class='table_admin_h txt_c'>\n"
    ."<input type='button' value='Submit' "
    ."onclick=\"geid('submode').value='save';geid('form').submit();\" class='formbutton' style='width: 60px;'/>\n"
    ."</td>\n"
    ."  </tr>\n"
    ."</table>\n";
    if ($for_person) {
        $ID = $oldID;
    }
    return $out;
}

function draw_auto_report($report_name, $toolbar = 1, $ajax_popup_url = false, $header = '')
{
    $Obj =        new Report_Report;
    $reportID =   $Obj->get_ID_by_name($report_name);
    if ($reportID) {
        $Obj->_set_ID($reportID);
        $content = $Obj->draw($report_name, $toolbar, $ajax_popup_url);
    } else {
        $content =
        ($ajax_popup_url ?
        array('html'=>'<h1>Problem:</h1><p>There is no such report as <b>'.$report_name.'</b></p>','js'=>'')
        : '<h1>Problem:</h1><p>There is no such report as <b>'.$report_name.'</b></p>'
        );
    }
    if ($ajax_popup_url) {
        return
        array(
        'html'=>
           "<div id='report_".$reportID."' style='margin-bottom: 1em;'>"
          .$header
          .$content['html']
          ."</div>",
        'js'=> $content['js']
        );
    }
    return
     "<div id='report_".$reportID."' style='margin-bottom: 1em;'>"
    .$header
    .$content
    ."</div>";
}

function draw_component($ID)
{
    if ($ID=="1") {
        return "";
    }
    $Obj_Component = new Component($ID);
    if ($Obj_Component->exists()) {
        return $Obj_Component->execute();
    }
    return "<h3>Error</h3><p>Component '".$ID."' is not currently available for use on this system:<br />\n".x();
}

function draw_component_by_name($name, $args = array())
{
    if ($name=="") {
        return "";
    }
    $Obj_Component = new Component;
    if (!$componentID = $Obj_Component->get_ID_by_name($name)) {
        return "";
    }
    $Obj_Component->_set_ID($componentID);
    $php = $Obj_Component->get_field('php');
    return eval($php);
}

function draw_date($format, $now = false)
{
    $now = ($now ? $now : time());
    switch($format) {
        case "DD MMM YYYY":
            return date("j F Y", $now);
        break;
        case "MMM DD YYYY": // Includes <sup>th</sup> etc
            return date("F j\<\s\u\p\>S\<\/\s\u\p\> Y", $now);
        break;
    }
    return "";
}

function draw_form_header($title, $help = "", $shadow = 0)
{
    global $ID,$report_name;
    return
     "<table class='minimal' style='width:100%;' summary=''>\n"
    ."  <tr>\n"
    ."    <td class='va_t' style='width:15px;'>"
    ."<img class='std_control b' src='".BASE_PATH."img/sysimg/corner_top_left.gif' width='15' height='18' alt=''/>"
    ."</td>\n"
    ."    <td class='table_admin_h txt_c'>".$title
    .($help!="" ? " ".HTML::draw_icon('help', $help) : "")
    .($report_name!='' && $ID!='' ?
        " ".HTML::draw_icon('print_form', array('report_name'=>$report_name,'ID'=>$ID))
     :
        ""
     )
    ."</td>\n"
    ."    <td class='va_t' style='width:15px;'>"
    ."<img class='std_control b' src='".BASE_PATH."img/sysimg/corner_top_right.gif' width='15' height='18' alt=''/>"
    ."</td>\n"
    .($shadow ?
       "    <td style='width:14px;'>"
       ."<img class='std_control b' src='".BASE_PATH."img/spacer' width='14' height='1' alt='' />"
       ."</td>"
     : "")
    ."  </tr>\n"
    ."</table>\n";
}

function draw_form_field(
    $field,
    $value,
    $type,
    $width = "",
    $selectorSQL = "",
    $reportID = 0,
    $jsCode = "",
    $readOnly = 0,
    $bulk_update = 0,
    $label = "",
    $formFieldSpecial = '',
    $height = ''
) {
    if ($type=='hidden') {
        return "<input type=\"hidden\" id=\"".$field."\" name=\"".$field."\" value=\"".$value."\" />";
    }
    $Obj = new Report_Column;
    $row = array();
    return $Obj->draw_form_field(
        $row,
        $field,
        $value,
        $type,
        $width,
        $selectorSQL,
        $reportID,
        $jsCode,
        $readOnly,
        $bulk_update,
        $label,
        $formFieldSpecial,
        $height
    );
}

function draw_hide_show($div, $text, $expanded = 1)
{
    return
     "<div class='clr_b'></div>\n"
    ."<div id=\"".$div."_show\" "
    .($expanded ? "style=\"display:none\" " : "")
    ."onclick=\"setDisplay('".$div."_show',0);setDisplay('".$div."_hide',1);setDisplay('".$div."_region',1);\""
    ."><h3 style='margin:0;' title='Click to show detail'>"
    ."<img src='".BASE_PATH."img/spacer' class='icons std_control'"
    ." style='margin-top:4px;margin-right:2px;height:13px;width:13px;background-position:-2209px 0px;' alt='' />"
    .$text."</h3>"
    ."</div>\n"
    ."<div id=\"".$div."_hide\" "
    .($expanded ? "" : "style=\"display:none\" ")
    ."onclick=\"setDisplay('".$div."_show',1);setDisplay('".$div."_hide',0);setDisplay('".$div."_region',0);\""
    .">\n"
    ."<h3 style='margin:0;' title='Click to hide detail'>"
    ."<img src='".BASE_PATH."img/spacer' class='icons std_control'"
    ." style='margin-top:4px;margin-right:2px;height:13px;width:13px;background-position:-2196px 0px;' alt='' />"
    .$text."</h3>"
    ."</div>"
    ."<div id=\"".$div."_region\""
    .($expanded ? "" : " style=\"display:none;width:100%; margin:auto;\"")
    .">";
}

function draw_html_content($zone = 1)
{
    return Page::draw_html_content($zone);
}

function draw_html_error_403()
{
    header("Status: 403 Unauthorised", true, 403);
    return Page::draw_http_error('403');
}

function draw_html_error_404()
{
    header("Status: 404 Not Found", true, 404);
    return Page::draw_http_error('404');
}

function draw_section_tabs($arr, $divider_prefix, $selected_section, $js = "")
{
    deprecated();
    $Obj = new HTML;
    return $Obj->draw_section_tabs($arr, $divider_prefix, $selected_section, $js);
}

function draw_section_tab_div($ID, $selected_section)
{
    $safe_ID = str_replace(array('/'), '_', $ID);
    return
    "<div id='section_".$safe_ID."' style='display: ".($selected_section==$safe_ID ? "inline" : "none").";'>";
}

function draw_select_options($sql, $value)
{
    $Obj = new Report_Column();
    return $Obj->draw_select_options($value, $sql);
}

function draw_signin_link()
{
    if (get_userID()) {
        return "";
    }
    return "<a class='fl' href='".BASE_PATH."signin'>[ICON]16 16 2611 Sign In[/ICON]</a>";
}

function draw_signup(
    $initialText = '',
    $confirmText = '',
    $failureText = '',
    $successText = '',
    $emailTo = 0,
    $report_name = 'signup',
    $mail_template = 'user_signup'
) {
    $Obj = new Person;
    return $Obj->draw_signup(
        $initialText,
        $confirmText,
        $failureText,
        $successText,
        $emailTo,
        $report_name,
        $mail_template
    );
}

function draw_sql_debug($title, $sql, $error)
{
    return
     "<table border='1' summary='SQL Error Info'>\n"
    ."  <tr>\n"
    ."    <td bgcolor='#ffffff'><h1>SQL Statement Debugger</h1>\n"
    ."    <h3 style='margin:0;'>$title</h3>\n"
    ."    <pre>".$sql."</pre>\n"
    .($error!="" ? "    <h3 style='margin:0;'>Error:</h3>\n".$error."<br /><br />" : "")
    .x(2)
    ."</td>\n"
    ."  </tr>\n"
    ."</table>\n";
}

function draw_layout($layoutID)
{
    $Obj = new layout($layoutID);
    return $Obj->prepare();
}

function draw_layout_colour($number)
{
    global $system_vars, $page_vars;
    if (isset($page_vars)) {
        return $page_vars['colours']['colour'.$number];
    }
    return $system_vars['colour'.$number];
}

function email_list_manage()
{
    return admin_manage_email_list();
}

function fix_currency_symbols($val)
{
    switch($val) {
        case "£":
            $val = "&#163;";
            break;
    }
    return $val;
}

function fix_ampersands($string)
{
    return preg_replace('/&(?!(?:[a-z][a-z\d]*|#(?:\d+|[xX][a-f\d]+));)/i', '&amp;', $string);
}

function format_bytes($bytes, $precision = 3)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

function format_date($YYYYMMDD, $format = false)
{
    global $system_vars;
    if ($YYYYMMDD=='0000-00-00') {
        return "";
    }
    $format = ($format ? $format : $system_vars['defaultDateFormat']);
    sscanf($YYYYMMDD, "%04d-%02d-%02d", $_YYYY, $_MM, $_DD);
    $_date =  mktime(0, 0, 0, $_MM, $_DD, $_YYYY);
    return date($format, $_date);
}

function format_datetime($YYYYMMDD_hhmm)
{
    global $system_vars;
    if ($YYYYMMDD_hhmm=='0000-00-00 00:00:00') {
        return "";
    }
    sscanf($YYYYMMDD_hhmm, "%04d-%02d-%02d %02d:%02d", $_YYYY, $_MM, $_DD, $_hh, $_mm);
    $_date =  mktime(0, 0, 0, $_MM, $_DD, $_YYYY);
    return
     date($system_vars['defaultDateFormat'], $_date)." "
    .hhmm_format($_hh.":".$_mm, $system_vars['defaultTimeFormat']==1 || $system_vars['defaultTimeFormat']==3);
}

function format_seconds($seconds)
{
    $hrs = 0;
    $mins = 0;
    $formatted = "";
    if ($seconds > 3600) {
        $hrs = intval($seconds / 3600);
        $seconds = $seconds % 3600;
    }
    if ($seconds >= 60) {
        $mins = intval($seconds / 60);
        $seconds = $seconds % 60;
    }
    if ($hrs > 0) {
        return sprintf("%d:%02d:%02d", $hrs, $mins, $seconds);
    } else {
        return sprintf("%d:%02d", $mins, $seconds);
    }
}

function format_time($hhmm)
{
    global $system_vars;
    if ($hhmm=='00:00:00') {
        return "";
    }
    sscanf($hhmm, "%02d:%02d", $_hh, $_mm);
    return hhmm_format($_hh.":".$_mm, $system_vars['defaultTimeFormat']==1 || $system_vars['defaultTimeFormat']==3);
}

function format_json( $json )
{
    $result = '';
    $level = 0;
    $in_quotes = false;
    $in_escape = false;
    $ends_line_level = NULL;
    $json_length = strlen( $json );

    for( $i = 0; $i < $json_length; $i++ ) {
        $char = $json[$i];
        $new_line_level = NULL;
        $post = "";
        if( $ends_line_level !== NULL ) {
            $new_line_level = $ends_line_level;
            $ends_line_level = NULL;
        }
        if ( $in_escape ) {
            $in_escape = false;
        } else if( $char === '"' ) {
            $in_quotes = !$in_quotes;
        } else if( ! $in_quotes ) {
            switch( $char ) {
                case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;

                case '{': case '[':
                    $level++;
                case ',': case ';':

                    $ends_line_level = $level;
                    break;

                case ':':
                    $post = " ";
                    break;

                case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
            }
        } else if ( $char === '\\' ) {
            $in_escape = true;
        }
        if( $new_line_level !== NULL ) {
            $result .= "\n".str_repeat( "  ", $new_line_level );
        }
        $result .= $char.$post;
    }

    return $result;
}

function format_phone($phone)
{
    $number = preg_replace("/[^0-9]/", "", $phone);
    if (strlen($number)==10) {
        return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $number);
    }
    return $phone;
}


function get_browser_safe()
{
  // Uses simple version if browscap.ini not set for system
    if (@$out = get_browser(null, true)) {
        return $out;
    }
    $out = array();
    $browsers =
    array(
      "mozilla",
      "msie",
      "gecko",
      "firefox",
      "konqueror",
      "safari",
      "netscape",
      "navigator",
      "opera",
      "mosaic",
      "lynx",
      "amaya",
      "omniweb");
    $agent = strToLower(@$_SERVER['HTTP_USER_AGENT']);
    $l = strlen($agent);
    for ($i=0; $i<count($browsers); $i++) {
        $browser = $browsers[$i];
        $n = stristr($agent, $browser);
        if (strlen($n)>0) {
            $out["browser"] = $browser;
            $j=strpos($agent, $out["browser"])+$n+strlen($out["browser"])+1;
            preg_match("/([0-9.]+)/", substr($agent, $j, $l-$j), $ver);
            $out["version"] = (isset($ver[1]) ? $ver[1] : '');
        }
    }
    $out["platform"] = "unknown";
    $out["crawler"] = "unknown";
    return $out;
}

function get_color_for_weight($weight = 100, $mincolor = '#808080', $maxcolor = '#800000')
{
    $weight = $weight/100;
    sscanf($mincolor, "#%2x%2x%2x", $minr, $ming, $minb);
    sscanf($maxcolor, "#%2x%2x%2x", $maxr, $maxg, $maxb);
    $r = dechex(intval((($maxr - $minr) * $weight) + $minr));
    $g = dechex(intval((($maxg - $ming) * $weight) + $ming));
    $b = dechex(intval((($maxb - $minb) * $weight) + $minb));
    if (strlen($r) == 1) {
        $r = "0" . $r;
    }
    if (strlen($g) == 1) {
        $g = "0" . $g;
    }
    if (strlen($b) == 1) {
        $b = "0" . $b;
    }
    return "#$r$g$b";
}


function get_dates_in_range($start, $last, $step = '+1 day', $format = 'd/m/Y')
{
  // http://stackoverflow.com/questions/4312439/php-return-all-dates-between-two-dates-in-an-array
    $out =        array();
    $current =    strtotime($start);
    $last =       strtotime($last);
    while ($current <= $last) {
        $out[] =    date($format, $current);
        $current =  strtotime($step, $current);
    }
    return $out;
}

function get_guid_from_string($string)
{
    $charid = strtoupper(md5($string));
    return
        "{"
        .substr($charid, 0, 8).'-'
        .substr($charid, 8, 4).'-'
        .substr($charid, 12, 4).'-'
        .substr($charid, 16, 4).'-'
        .substr($charid, 20, 12)
        ."}";
}

function get_iso3166_country($countryID)
{
    $Obj = new Country($countryID);
    return $Obj->get_iso3166($Obj->get_field('value'));
}

function get_emailAddressAsGif($text, $size = 8, $color = "000000")
{
    $code_arr = array();
    for ($i=0; $i<strlen($text); $i++) {
        $code = dechex(ord(substr($text, $i, 1)));
        if (strlen($code)==2) {
  // drops ascii codes like D and A
            $code_arr[] = $code;
        }
    }
    return
     "<img class='antispam' alt=\"email address\""
    ." src=\"".BASE_PATH."img/encoded/".$color."/".$size."/".implode("", array_reverse($code_arr))."\""
    ." title=\"To protect this email address against spam,\nthe address is shown as a non-machine-readable image.\""
    ." />";
}

function get_icon_for_extension($ext)
{
    switch ($ext){
        case "css":
        case "js":
        case "txt":
            $img = "iconTXT.gif";
            break;
        case "doc":
        case "docx":
        case "dot":
        case "rtf":
            $img = "iconDOC.gif";
            break;
        case "htm":
        case "html":
            $img = "iconHTM.gif";
            break;
        case "gif":
            $img = "iconGIF.gif";
            break;
        case "mp3":
            $img = "iconMP3.gif";
            break;
        case "wma":
            $img = "iconWMA.gif";
            break;
        case "jpg":
        case "jpe":
        case "jpeg":
            $img = "iconJPG.gif";
            break;
        case "php":
            $img = "iconPHP.gif";
            break;
        case "pdf":
            $img = "iconPDF.gif";
            break;
        case "sql":
            $img = "iconSQL.gif";
            break;
        case "csv":
        case "xls":
        case "xlsx":
            $img = "iconXLS.gif";
            break;
        case "zip":
            $img = "iconZIP.gif";
            break;
        default:
            $img = "iconUNKNOWN.gif";
            break;
    }
    return BASE_PATH."img/sysimg/".$img;
}

function get_image_alt($html)
{
    if (substr($html, 0, 4)=="<img") {
        $html = preg_replace('|<img.*?alt\s*=\s*\'(.*?)\'.*?>|', '\1', $html);
    }
    if (substr($html, 0, 5)=="[LBL]") {
        $html = substr($html, 5, strlen($html)-11);
        $html_bits = explode('|', $html);
        $html = array_pop($html_bits);
    }
    $html = convert_safe_to_php($html);
    return strip_tags(preg_replace('(<br />)', ' ', $html));
}

function get_mailsender_to_component_results($mailidentityID = 1)
{
    global $system_vars;
    if ($mailidentityID==1) {
        component_result_set('bounce_email', trim($system_vars['bounce_email']));
        component_result_set('from_email', trim($system_vars['adminEmail']));
        component_result_set('from_name', trim($system_vars['adminName']));
        component_result_set('smtp_authenticate', trim($system_vars['smtp_authenticate']));
        component_result_set('smtp_host', trim($system_vars['smtp_host']));
        component_result_set('smtp_password', trim($system_vars['smtp_password']));
        component_result_set('smtp_port', trim($system_vars['smtp_port']));
        component_result_set('smtp_username', trim($system_vars['smtp_username']));
        return;
    }
    $Obj_Mail_Identity = new Mail_Identity($mailidentityID);
    if (!$row = $Obj_Mail_Identity->load()) {
        return get_mailsender_to_component_results(1);
    }
    component_result_set('bounce_email', trim($row['bounce_email']));
    component_result_set('from_email', trim($row['email']));
    component_result_set('from_name', trim($row['name']));
    component_result_set('smtp_authenticate', trim($row['smtp_authenticate']));
    component_result_set('smtp_host', trim($row['smtp_host']));
    component_result_set('smtp_password', trim($row['smtp_password']));
    component_result_set('smtp_port', trim($row['smtp_port']));
    component_result_set('smtp_username', trim($row['smtp_username']));
}

function get_max_upload_size()
{
    $max_upload =     (int)(ini_get('upload_max_filesize'));
    $max_post =       (int)(ini_get('post_max_size'));
    $memory_limit =   (int)(ini_get('memory_limit'));
    return 1024 * 1024 * min($max_upload, $max_post, $memory_limit);
}

function get_number_with_ordinal($num)
{
    if (($num / 10) % 10 != 1) {
        switch($num % 10){
            case 1:
                return $num . 'st';
            case 2:
                return $num . 'nd';
            case 3:
                return $num . 'rd';
        }
    }
    return $num . 'th';
}

function get_page_vars()
{
    $Obj_Page_vars = new Page_Vars;
    return $Obj_Page_vars->get();
}

function get_person_permission($permission, $group_list = "")
{
    return Person::get_permission($permission, $group_list);
}

function get_person_to_session($username, $password_enc)
{
    $Obj_User =     new User;
    return $Obj_User->get_person_to_session($username, $password_enc);
}

function get_popup_params_for_report_form($report_name)
{
    $Obj = new Report;
    return $Obj->get_popup_params_for_report_form($report_name);
}

function get_popup_size($report_name)
{
    $Obj = new Report;
    return $Obj->get_popup_params_for_report_form($report_name);
}

function get_random_password()
{
    $out =    array();
    $salt = "abcdefghjkmnpqrstuvwxyz234578";
    srand((double)microtime()*1000000);
    $out = "";
    for ($i=0; $i<8; $i++) {
        $num = rand() % 33;
        $out.= substr($salt, $num, 1);
    }
    return $out;
}

function get_js_safe_ID($value)
{
    $value =  get_web_safe_ID($value);
    return str_replace('-', '_', $value);
}

function get_path_safe_filename($filename)
{
    $filename_arr =   explode('.', $filename);
    $ext =            strToLower(array_pop($filename_arr));
    return get_web_safe_ID(implode('.', $filename_arr)).'.'.$ext;
}

function get_title_for_path($value)
{
    $value = trim($value);
    if (strlen($value)>=10 && sanitize('date-stamp', substr($value, 0, 10))==substr($value, 0, 10)) {
        $value = substr($value, 0, 10).title_case_string(str_replace(array('-','_'), " ", substr($value, 10)));
    } else {
        $value = title_case_string(str_replace(array('-','_'), " ", $value));
    }
    $value = str_replace(
        array('1St','2Nd','3Rd','4Th','5Th','6Th','7Th','8Th','9Th','0Th'),
        array('1st','2nd','3rd','4th','5th','6th','7th','8th','9th','0th'),
        $value
    );
    return $value;
}


function get_web_safe_ID($text)
{
    return
    trim(
        preg_replace(
            "/(-)+/",
            '-',
            str_replace(
                array('$','&','%','@','/'),
                array('dollars','and','pc','at','-'),
                str_replace(
                    array('---','--'),
                    '-',
                    str_replace(
                        ' ',
                        '-',
                        str_replace(
                            array(':','!','"','\'',',','.','?','’','[',']','(',')','{','}','#'),
                            '',
                            strToLower($text)
                        )
                    )
                )
            )
        ),
        '-'
    );
}

function get_system_vars()
{
    global $page;
    $Obj_System = new System(SYS_ID);
    $row =        $Obj_System->get_record();
    if ($row===false) {
        switch ($page) {
            case "home":
                print
                     "<p><b>Configuration Issue</b><br />\n"
                    ."This system has an ID of ".SYS_ID.". "
                    ."The database does not contain an entry for that system.<br />\n"
                    ."To sign in anyway, click <a href='".BASE_PATH."signin?print=1'><b>here</b></a></p>";
                break;
            case "signed_in":
                print
                     "<p>You are signed in - click <a href='".BASE_PATH."report/system?print=1'><b>here</b></a>"
                    ." to view systems you can administer.</p>";
                break;
        }
        return false;
    }
    if ($row['db_upgrade_flag']==1) {
        Portal::portal_upgrade();
    }
    if (defined('DEBUG_NO_INTERNET')) {
        $row['akismet_key'] = '';
    }
    component_result_set('systemID', $row['ID']);
    component_result_set('system_URL', trim($row['URL'], '/'));
    component_result_set('system_title', $row['textEnglish']);
    return $row;
}

function get_sql_constants($sql)
{
    global $db, $ID,$selectID, $system_vars, $MM, $YYYY, $reportID;
    $s_arr =
    array(
      "DATABASE_NAME",
      "PERSON_ID",
      "_REPORT_ID",
      "_ID_",
      "MEMBER_ID",
      "REPORT_ID",
      "SELECT_ID",
      "SELECT_MM",
      "SELECT_YYYY",
      "SYS_ID",
      "SYS_URL"
    );
    $r_arr =
    array(
      $db,
      get_userID(),
      $reportID,
      ($ID!='' ? $ID : 0),
      (isset($_SESSION['person']) ? $_SESSION['person']['memberID'] : 0),
      $ID,
      ($selectID!='' ? $selectID : 0),
      $MM,
      $YYYY,
      SYS_ID,
      $system_vars['URL']
    );
    return str_replace($s_arr, $r_arr, $sql);
}

function get_timestamp($date = false)
{
// Don't use adodb for this - quote from lib file:
//   - 18 July 2004 0.15
//    All params in adodb_mktime were formerly compulsory.
//    Now only the hour, min, secs is compulsory.
//    This brings it more in line with mktime (still not identical).
//
// This means that unless given time defaults to 00:00:00
    if (!$date) {
        $date = time();
    }
    return date('Y-m-d H:i:s', $date);
}

function get_timestamp_extended($date = false)
{
// Don't use adodb for this - quote from lib file:
//   - 18 July 2004 0.15
//    All params in adodb_mktime were formerly compulsory.
//    Now only the hour, min, secs is compulsory.
//    This brings it more in line with mktime (still not identical).
//
// This means that unless given time defaults to 00:00:00
    if (!$date) {
        $date = time();
    }
    return date('Y-m-d H:i:s w ', $date).get_week_of_month($date);
}

function get_text_for_listdata($listtype_name, $value)
{
    $Obj = new lst_named_type(false, $listtype_name);
    return $Obj->get_text_for_value($value);
}

function get_userID()
{
    if (!isset($_SESSION['person'])) {
        return false;
    }
    return $_SESSION['person']['ID'];
}


function get_userFullName()
{
    if (!isset($_SESSION['person'])) {
        return false;
    }
    return $_SESSION['person']['NFull'];
}

function get_userPUsername()
{
    if (!isset($_SESSION['person'])) {
        return false;
    }
    return $_SESSION['person']['PUsername'];
}

function get_user_status()
{
    return Person::get_user_status();
}

function get_user_status_text()
{
    return Person::get_user_status_text();
}

function get_uuid()
{
    $chars = md5(uniqid(mt_rand(), true));
    return
     substr($chars, 0, 8).'-'
    .substr($chars, 8, 4).'-'
    .substr($chars, 12, 4).'-'
    .substr($chars, 16, 4).'-'
    .substr($chars, 20, 12);
}

function get_var($key, $default = false)
{
    return (isset($_GET[$key]) ? $_GET[$key] : (isset($_POST[$key]) ? $_POST[$key] : $default));
}

function set_var($key, $value)
{
    $_GET[$key] =     $value;
    $_POST[$key] =    $value;
}

function get_week_of_month($dateTimestamp)
{
    $d = date('j', $dateTimestamp);
    $w = date('w', $dateTimestamp)+1; //add 1 because date returns value between 0 to 6
    $dt= (floor($d % 7)!=0) ? floor($d % 7) : 7;
    $k = ($w-$dt);
    $W= ceil(($d+$k)/7);
    return $W ;
}

function hhmm_format($hhmm, $use_am_pm = false)
{
    if ($hhmm=="") {
        return "";
    }
    $hhmm_arr = explode(':', $hhmm);
    $hh = (int)$hhmm_arr[0];
    $mm = (isset($hhmm_arr[1]) ? lead_zero($hhmm_arr[1], 2) : "00");
    if (!$use_am_pm) {
        return lead_zero($hh, 2).":".$mm;
    }
    if ($hhmm=='12:00') {
        return 'Noon';
    }
    if ($hhmm=='00:00') {
        return 'Midnight';
    }
    if ($hh<12) {
        $ampm = 'am';
    } else {
        if ($hh!=12) {
            $hh = $hh-12;
        }
        $ampm = 'pm';
    }
    return $hh.":".$mm.$ampm;
}

function hhmmss_to_seconds($time)
{
    $timeArr = array_reverse(explode(":", $time));
    $seconds = 0;
    foreach ($timeArr as $key => $value) {
        if ($key > 2) {
            break;
        }
        $seconds += pow(60, $key) * $value;
    }
    return $seconds;
}

function highlight($string, $find)
{
    $find = str_replace("_", "[A-Z0-9]", $find);
    return ($find ? (preg_replace("/(".$find.")/i", "<span class='search_match'>\\1</span>", $string)): $string);
}

function img($submode, $ID, $no_show = 0)
{
    switch ($submode) {
        case "btn_style":
            header("Content-type: image/gif");
            $Obj = new Navbutton_Style;
            $Obj->_set_ID($ID);
            $Obj->sample();
            die;
        break;
    }
}

function img_button($ID, $no_show = 0)
{
    $Obj = new Navbutton($ID);
    return $Obj->Image($no_show);
}

function img_button_sample($ID)
{
    $Obj = new Navbutton_style($ID);
    return $Obj->make_images(false);
}

function lead_zero($text, $places)
{
    return (substr("0000", 0, $places-strlen($text)).$text);
}


function mailto($data)
{
    global $system_vars;
    $mail = new PHPMailer(true);  // We want to throw exceptions
    try {
        $mail->IsSMTP();
        $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
        $mail->IsHtml(true);
        $mail->CharSet =    (ini_get('default_charset') ? ini_get('default_charset') : "UTF-8");
        $mail->Host =        component_result('smtp_host');
        $mail->SMTPAuth =   component_result('smtp_authenticate');
        $mail->Username =   component_result('smtp_username');
        $mail->Password =   component_result('smtp_password');
        $mail->SetFrom(component_result('from_email'), component_result('from_name'), false);
        if (component_result('bounce_email')!='') {
            $mail->Sender =   component_result('bounce_email');
            $mail->AddCustomHeader('Errors-To:'.component_result('bounce_email'));
        }
        if (isset($data['replyto_email'])) {
            $mail->AddReplyTo(
                $data['replyto_email'],
                (isset($data['replyto_name']) ? $data['replyto_name']: $data['replyto_email'])
            );
        } else {
            $mail->AddReplyTo(
                component_result('from_email'),
                component_result('from_name')
            );
        }
        $mail->AddAddress($data['PEmail'], $data['NName']);
        if (isset($data['cc_email'])) {
            $mail->AddCC($data['cc_email'], $data['cc_name']);
        }
        if (isset($data['bcc_email'])) {
            $mail->AddBCC($data['bcc_email'], $data['bcc_name']);
        }
        $subject =         convert_safe_to_php(str_replace("<br />", "\n", $data['subject']));
        $mail->Subject =     utf8_encode(html_entity_decode($subject));
        $html =
         "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\""
        ." \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n"
        ."<html xmlns=\"http://www.w3.org/1999/xhtml\""
        ." lang=\"".$system_vars['defaultLanguage']."\" xml:lang=\"".$system_vars['defaultLanguage']."\">\n"
        ."<head>\n"
        ."  <title>".$subject."</title>\n"
        ."  <meta http-equiv=\"Content-Type\" content=\"text/html;"
        ." charset=".(ini_get('default_charset') ? ini_get('default_charset') : "UTF-8")."\"/>\n"
        ."  <meta http-equiv=\"Generator\" content=\""
        .System::get_item_version('system_family')." "
        .System::get_item_version('codebase').".".$system_vars['db_version']
        ."\"/>\r\n"
        .(isset($data['style']) ? "  <style type=\"text/css\">".$data['style']."</style>\n" : "")
        ."</head>\n"
        ."<body>\n"
        .$data['html']."\n"
        ."</body>\n"
        ."</html>\n";
        $mail->Body =         convert_safe_to_php($html);
        if (isset($data['text'])) {
            $mail->AltBody =    convert_safe_to_php(str_replace("<br />", "\n", $data['text']));
        }
        $mail->Send();
        return "Message-ID: ".$mail->MessageID;
    } catch (phpmailerException $e) {
        return $e->getMessage();
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function header_mimetype_for_extension($ext)
{
    switch ($ext){
        case "doc":
            header("Content-type: application/msword");
            break;
        case "mp3":
            header("Content-type: audio/mpeg");
            break;
        case "pdf":
            header("Content-type: application/pdf");
            break;
        case "ppt":
            header("Content-type: application/vnd.ms-powerpoint");
            break;
        case "xls":
            header("Content-type: application/vnd.ms-excel");
            break;
    }
}

// ************************************
// * mkdirs()                         *
// ************************************
// Thanks to baldurien@club-internet.fr for example contributed to
// http://ca.php.net/manual/en/function.mkdir.php
function mkdirs($dir, $mode = 0755)
{
    if (is_dir($dir)) {
        return true;
    }
    $stack = array(basename($dir));
    $path = null;
    while (($d = dirname($dir) )) {
        if (!is_dir($d)) {
            $stack[] = basename($d);
            $dir = $d;
        } else {
            $path = $d;
            break;
        }
    }
    if (( $path = realpath($path) ) === false) {
        return false;
    }

    $created = array();
    for ($n = count($stack) - 1; $n >= 0; $n--) {
        $s = $path . '/'. $stack[$n];
        if (@!mkdir($s, $mode)) {
            for ($m = count($created) - 1; $m >= 0; $m--) {
                rmdir($created[$m]);
            }
            return false;
        }
        $created[] = $s;
        $path = $s;
    }
    return true;
}


// ************************************
// * MM_to_MMM()                      *
// ************************************
function MM_to_MMM($MM)
{
    switch ($MM) {
        case "01":
            return "Jan";
        break;
        case "02":
            return "Feb";
        break;
        case "03":
            return "Mar";
        break;
        case "04":
            return "Apr";
        break;
        case "05":
            return "May";
        break;
        case "06":
            return "Jun";
        break;
        case "07":
            return "Jul";
        break;
        case "08":
            return "Aug";
        break;
        case "09":
            return "Sep";
        break;
        case "10":
            return "Oct";
        break;
        case "11":
            return "Nov";
        break;
        case "12":
            return "Dec";
        break;
    }
}


// ************************************
// * MM_to_MMMM()                     *
// ************************************
function MM_to_MMMM($MM)
{
    switch ($MM) {
        case "01":
            return "January";
        break;
        case "02":
            return "February";
        break;
        case "03":
            return "March";
        break;
        case "04":
            return "April";
        break;
        case "05":
            return "May";
        break;
        case "06":
            return "June";
        break;
        case "07":
            return "July";
        break;
        case "08":
            return "August";
        break;
        case "09":
            return "September";
        break;
        case "10":
            return "October";
        break;
        case "11":
            return "November";
        break;
        case "12":
            return "December";
        break;
    }
}

function pad($text, $places)
{
    $padding = (strLen($text)>$places ?
        " "
     :
        (substr(str_repeat(" ", 120), 0, $places-strLen($text)))
    );
    return $text.$padding;
}

/**
 * A safe empowered glob().
 *
 * Function glob() is prohibited on some server (probably in safe mode)
 * (Message "Warning: glob() has been disabled for security reasons in
 * (script) on line (line)") for security reasons as stated on:
 * http://seclists.org/fulldisclosure/2005/Sep/0001.html
 *
 * safe_glob() intends to replace glob() using readdir() & fnmatch() instead.
 * Supported flags: GLOB_MARK, GLOB_NOSORT, GLOB_ONLYDIR
 * Additional flags: GLOB_NODIR, GLOB_PATH, GLOB_NODOTS, GLOB_RECURSE
 * (not original glob() flags)
 * @author BigueNique AT yahoo DOT ca
 * @updates
 * - 080324 Added support for additional flags: GLOB_NODIR, GLOB_PATH,
 *   GLOB_NODOTS, GLOB_RECURSE
 */
define('GLOB_NODIR', 256);
define('GLOB_PATH', 512);
define('GLOB_NODOTS', 1024);
define('GLOB_RECURSE', 2048);
function safe_glob($pattern, $flags = 0)
{
    $split=explode('/', str_replace('\\', '/', $pattern));
    $mask=array_pop($split);
    $path=implode('/', $split);
    if (($dir=opendir($path))!==false) {
        $glob=array();
        while (($file=readdir($dir))!==false) {
          // Recurse subdirectories (GLOB_RECURSE)
            if (($flags&GLOB_RECURSE) && is_dir($file) && (!in_array($file, array('.','..')))) {
                $glob = array_merge(
                    $glob,
                    array_prepend(
                        safe_glob($path.'/'.$file.'/'.$mask, $flags),
                        ($flags&GLOB_PATH?'':$file.'/')
                    )
                );
            }
          // Match file mask
            if (fnmatch($mask, $file)) {
                if (
                ((!($flags&GLOB_ONLYDIR)) || is_dir($path.'/'.$file)) &&
                ((!($flags&GLOB_NODIR)) || (!is_dir($path.'/'.$file))) &&
                ((!($flags&GLOB_NODOTS)) || (!in_array($file, array('.','..'))))
                ) {
                    $glob[] = ($flags&GLOB_PATH?$path.'/':'') . $file . ($flags&GLOB_MARK?'/':'');
                }
            }
        }
        closedir($dir);
        if (!($flags&GLOB_NOSORT)) {
            sort($glob);
        }
        return $glob;
    } else {
        return false;
    }
}

function scan_Dir($dir, $pattern) {
    $current_dir = getcwd();
    $dir = trim($dir, '/');
    $split=explode('/', str_replace('\\', '/', $pattern));
    $mask=array_pop($split);

    $arrfiles = array();
    if (is_dir($dir)) {
        if ($handle = opendir($dir)) {
            chdir($dir);
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if (is_dir($file)) {
                        $arr = scan_Dir($file, $pattern);
                        foreach ($arr as $value) {
                            $arrfiles[] = $dir."/".$value;
                        }
                    } else {
                        if (fnmatch($mask, $file)) {
                            $arrfiles[] = $dir."/".$file;
                        }
                    }
                }
            }
            chdir("../");
        }
        closedir($handle);
    }
    chdir($current_dir);
    sort($arrfiles);
    return $arrfiles;
}


function sanitize()
{
    $args = func_get_args();
    if (count($args)<2) {
        die ("sanitize requires at least 2 arguments - type and first parameter");
    }
    switch ($args[0]){
        case "ID":
            if (count($args)!=2) {
                die ("Syntax: sanitize('".$args[0]."',\$input)");
            }
            $value_arr = explode(",", str_replace(' ', '', $args[1]));
            foreach ($value_arr as &$value) {
                if ($value>2147483647) {
                    print "Sanitize Error: ID ".$value." is too high - value was set to zero\n\n";
                    $value = 0;
                }
                if ((int)$value<0) {
                    print "Sanitize Error: ID ".$value." is too low - value was set to zero\n\n";
                    $value = 0;
                }
                $value = (int)$value;
            }
            return implode(",", array_unique($value_arr));
        break;
        case "date-format":
            if (count($args)!=3) {
                die ("Syntax: sanitize('".$args[0]."','\$input','\$default')");
            }
            switch($args[1]){
                case "MM DD YYYY":
                case "MM DD, YYYY":
                case "MM DD YYYY h:mmXM":
                case "MM DDD YYYY":
                case "MM DDD YYYY hh:mm":
                case "MM DDD YYYY h:mmXM":
                case "MMM DD, YYYY":
                case "MMM DDD YYYY":
                    return $args[1];
                break;
            }
            return $args[2];
        break;
        case "date-stamp":
            if (count($args)!=2) {
                die ("Syntax: sanitize('".$args[0]."',\$input)");
            }
            $string = trim($args[1], '-');
            if (!$string) {
                return "";
            }
            $bits_arr = explode("-", $string);
            $YYYY  = sanitize('range', $bits_arr[0], 100, 3000, false);
            if (!$YYYY) {
                return '';
            }
            $out = lead_zero($YYYY, 4);
            if (count($bits_arr)==1) {
                return $out;
            }
            $MM = sanitize('range', $bits_arr[1], 1, 12, false);
            if (!$MM) {
                return '';
            }
            $out.= '-'.lead_zero($MM, 2);
            if (count($bits_arr)==2) {
                return $out;
            }
            $DD = sanitize('range', $bits_arr[2], 1, 31, false);
            if (!$DD) {
                return '';
            }
            $out.= '-'.lead_zero($DD, 2);
            if (count($bits_arr)==3) {
                return $out;
            }
            return '';
        break;
        case "enum":
            if (count($args)!=3) {
                die ("Syntax: sanitize('".$args[0]."',\$find,\$array)");
            }
            if (in_array($args[1], $args[2])) {
                return $args[1];
            }
            return $args[2][0];
        break;
        case "enum_csv":
            if (count($args)!=3) {
                die ("Syntax: sanitize('".$args[0]."',\$find,\$array)");
            }
            $value_arr = explode(",", str_replace(' ', '', $args[1]));
            $out = array();
            foreach ($value_arr as &$value) {
                if (in_array($value, $args[2])) {
                    $out[] = $value;
                }
            }
            $result =(count($out) ? implode(",", array_unique($out)) : '');
            return $result;
        break;
        case "hex3":
            if (count($args)!=3) {
                die ("Syntax: sanitize('".$args[0]."','\$input','\$default')");
            }
            return (preg_match('/^(#)?[a-f0-9]{6}$/i', $args[1]) ?  $args[1] :  $args[2]);
        break;
        case "html":
            if (count($args)!=2) {
                die ("Syntax: sanitize('".$args[0]."',\$input)");
            }
            $charset = (ini_get('default_charset') ? ini_get('default_charset') : "UTF-8");
            return htmlentities($args[1], ENT_COMPAT, $charset);
        break;
        case "range":
            if (count($args)!=5) {
                die ("Syntax: sanitize('".$args[0]."',\$input,\$min,\$max,\$default) - \$max may be given as 'n'");
            }
            if (!is_numeric($args[1]) || $args[1]<$args[2] || ($args[3]!='n' && $args[1]>$args[3])) {
                return $args[4];
            }
            return $args[1];
        break;
        case "rss":
            $allowable_tags =
            "<br /><a><b><i><u><strong><em><p><img>";
            $find =
            array(
            "& ",
            "\n",
            "<br />",
            "<br/>",
            "&bull;",
            "•",
            "&copy;",
            "&ccedil;",
            "&eacute;",
            "&ecirc;",
            "&egrave;",
            "&Eacute",
            "&frac12;",
            "&hellip;",
            "&ldquo;",
            "&lsquo;",
            "&mdash;",
            "&ensp;",
            "&nbsp;",
            "&ndash;",
            "&Otilde;",
            "&ocirc;",
            "&rdquo;",
            "&rsquo;",
            "&upsilon;",
            "&mu;",
            "&nu;",
            "&iota;",
            "&sigma;",
            "&sigmaf;",
            );
            $repl =
            array(
            "&amp; ",
            "<br />\n",
            "<br />\n",
            "<br />\n",
            "*",
            "*",
            "<br />\n",
            "(c)",
            "c",
            "e",
            "e",
            "e",
            "E",
            "1/2",
            "...",
            "'",
            "'",
            " ",
            " ",
            " ",
            "-",
            "O",
            "o",
            "'",
            "#",
            "#",
            "#",
            "#",
            "#",
            "#",
            );
            return str_replace($find, $repl, strip_tags($args[1], $allowable_tags));
        break;
        default:
            die("sanitize doesn't recocognise mode of ".$args[0]);
        break;
    }
}

function SXML_attribute(SimpleXMLElement $object, $attribute)
{
    if (isset($object[$attribute])) {
        return (string)$object[$attribute];
    }
}

function seconds_format($seconds)
{
    $h = floor($seconds/3600);
    $m = floor(($seconds-($h*3600))/60);
    $s = $seconds -($h*3600)-($m*60);
    if ($h==0) {
        return $m.':'.lead_zero($s, 2);
    }
    return $h.':'.lead_zero($m, 2).':'.lead_zero($s, 2);
}

function seconds_to_hhmmss($seconds)
{
    return gmdate("H:i:s", $seconds);
}

function set_cache($expires, $useFile = false, $useDate = false)
{
    $exp_gmt = gmdate("D, d M Y H:i:s", time() + $expires)." GMT";  // don't refresh until expires
    $mod_gmt = gmdate("D, d M Y H:i:s", time() - 3600*10)." GMT";   // Modified 10 hours ago
    if ($useDate) {
     // use the date we were given
        $mod_gmt = gmdate("D, d M Y H:i:s", strtotime($useDate)) . " GMT";
    } elseif ($useFile && file_exists($useFile)) {
      // get the file modified date
        $mod_gmt = gmdate("D, d M Y H:i:s", filemtime($useFile)) . " GMT";
    }
  // get the "If-Modified-Since" REQUEST header
    $clientHeaders = apache_request_headers();
    $if_modified_since =
        array_key_exists('If-Modified-Since', $clientHeaders) ? $clientHeaders['If-Modified-Since'] : '';
    if ($if_modified_since == $mod_gmt) {
   // files are the same, send 304
        header("HTTP/1.0 304 Not Modified");
        exit;
    }
    @header("Expires: $exp_gmt");
    @header("Last-Modified: $mod_gmt");
    @header("Cache-Control: public, max-age=$expires");
    @header("Pragma: !invalid");
}


function setColourIndex(&$image, $i, $string)
{
    sscanf($string, "%2x%2x%2x", $r, $g, $b);
    return Imagecolorset($image, $i, $r, $g, $b);
}

function status_message($status, $html_format, $object, $extras, $operation, $targetID)
{
    $out = "";
    $qty =    count(explode(",", $targetID));
    if ($qty==0 || $targetID=="") {
        $thisthese =    "No";
        $plural =       "s";
        $havehas =      "have";
    } elseif ($qty==1) {
        $thisthese =    "The";
        $plural =       "";
        $havehas =      "has";
    } else {
        $thisthese =    "The ".$qty;
        $plural =       "s";
        $havehas =      "have";
    }
    if ($html_format) {
        switch ($status) {
            case 0:
                $out.=    "<span style='background-color:#e0ffe0; color:#008000; border: solid 1px #008000;'>&nbsp;<b>";
                break;
            case 1:
                $out.=    "<span style='background-color:#FFEACD; color:#c07000; border: solid 1px #c07000;'>&nbsp;<b>";
                break;
            case 2:
                $out.=    "<span style='background-color:#FFE1E1; color:#ff0000; border: solid 1px #ff0000;'>&nbsp;<b>";
                break;
        }
    }
    switch ($status) {
        case 0:
            $out.=    "Success:";
            break;
        case 1:
            $out.=    "Warning:";
            break;
        case 2:
            $out.=    "Error:";
            break;
    }
    $out.=
     ($html_format ? "</b>" : "")
    ." ".$thisthese." ".$object.$plural." "
    .($status==0 && $extras=="" ? $havehas : $extras)
    ." ".$operation
    .($html_format ? "&nbsp;</span>" : "")
    ;
    return $out;
}

function strip_only($str, $tags_arr)
{
    if (!is_array($tags_arr)) {
        $tags_arr = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags_arr)) : array($tags_arr));
        if (end($tags_arr) == '') {
            array_pop($tags_arr);
        }
    }
    foreach ($tags_arr as $tag) {
        $str = preg_replace('#</?'.$tag.'[^>]*>#is', '', $str);
    }
    return $str;
}

function table_uniqID($table, $db = '')
{
    $Obj = new Record($table);
    if ($db!='') {
        $Obj->set_db_name($db);
    }
    return $Obj->uniqID();
}

function title_case_string($text)
{
    // Ref: http://www.sitepoint.com/blogs/2005/03/15/title-case-in-php
    // Our array of 'small words' which shouldn't be capitalised if
    // they aren't the first word. Add your own words to taste.
    $smallwordsarray =
    array(
      'of','a','the','and','an','or','nor','but','is','if','then','else','when',
      'at','from','by','on','off','for','in','out','over','to','into','with'
    );
    // Split the string into separate words
    $words = explode(' ', $text);
    foreach ($words as $key => $word) {
        // If this word is the first, or it's not one of our small words, capitalise it
        if ($key == 0 or !in_array($word, $smallwordsarray)) {
            $words[$key] = mb_convert_case($word, MB_CASE_TITLE);
        }
    }
    return implode(' ', $words);
}

function x($shift = 0)
{
    $trace = debug_backtrace();
    if (!isset($trace[1])) {
        return "";
    }
    for ($i=0; $i<$shift; $i++) {
        array_shift($trace);
    }
    $message =    "<pre><b>Call stack trace:</b>\n";
    for ($i=1; $i<count($trace)&&$i<=50; $i++) {
        $message.=
         "[".lead_zero(count($trace)-$i-1, 2)."] "
        ."<span style='color:#ff0000'><b>"
        .(isset($trace[$i]['class']) ? $trace[$i]['class']."::" : "")
        .$trace[$i]['function']."()"
        ."</b></span>"
        .(isset($trace[$i]['file']) ? " via <span style='color:#008080'><b>".$trace[$i]['file']."</b></span>" : '')
        .(isset($trace[$i]['line']) ? " line <span style='color:#000080'><b>".$trace[$i]['line']."</b></span>" : '')
        ."\n";
    }
    $message.= "</pre>";
    return $message;
}

function y()
{
    $args = func_get_args();
    $out = "";
    foreach ($args as $var) {
        $out.="<pre>".print_r($var, true)."</pre>\n";
    }
    print $out;
    return $out;
}

function z($sql)
{
    print "<pre>".$sql."</pre>";
}

function two_dp($value)
{
    return number_format($value, 2, '.', '');
}

function three_dp($value)
{
    return number_format($value, 3, '.', '');
}

function XOREncryption($input, $key)
{
    $keyLen = strlen($key);
    for ($i = 0; $i < strlen($input); $i++) {
        $rPos = $i % $keyLen;
        $r = ord($input[$i]) ^ ord($key[$rPos]);
        $input[$i] = chr($r);
    }
    return $input;
}

function XOREncrypt($input)
{
    $key = md5(component_result('systemID'));
    $output = XOREncryption($input, $key);
    $output = base64_encode($output);
    return $output;
}

function XORDecrypt($input)
{
    $key = md5(component_result('systemID'));
    $output = base64_decode($input);
    $output = XOREncryption($output, $key);
    return $output;
}
