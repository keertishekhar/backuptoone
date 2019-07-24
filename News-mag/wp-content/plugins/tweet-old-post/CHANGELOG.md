
 ### v8.3.2 - 2019-05-27 
 **Changes:** 
 * Fix: Log would some times show the wrong status message for the share
* Change: Use button to show app credential fields on new installs
 
 ### v8.3.1 - 2019-05-24 
 **Changes:** 
 * Fix: Use wp_remote_request functions in favor of guzzle which was causing issues on some websites
* Fix: Posting to Pinterest board names with commas
 
 ### v8.3.0 - 2019-05-24 
 **Changes:** 
 * New: Made connecting Facebook pages to plugin much simpler.
* Fix: When using publish now feature, all services would be checked after page reload even though only one was selected.
 
 ### v8.2.5 - 2019-05-17 
 **Changes:** 
 * New: Show admin notice when WP Cron is turned off, which can cause posting issues with ROP
* Fix: LinkedIn Image posts were not going through
* Fix: Posting to some Pinterest boards with special characters was not working
* Info: Tested on WP 5.2
 
 ### v8.2.4 - 2019-04-15 
 **Changes:** 
 * Fix: Minor bugs
 
 ### v8.2.3 - 2019-04-10 
 **Changes:** 
 * New: Filter introduced for Post Title & Content separator (check revive.social docs)
* New: Known errors will now show a link to the fix in the log area
* Fix: Twitter images would not share for sites which moved to a different protocol but didn't update their image links in the database
* PRO Fix: Moved to LinkedIn API v2 (check revive.social docs)
 
 ### v8.2.2 - 2019-03-20 
 **Changes:** 
 * New: Feedback button on plugin dashboard. Help us make ROP better by filling out the form!
* Fix: Minor typos
* PRO: You can now share custom messages/share variations in the order they were added.
* PRO Change: Updated custom messages/share variations metabox design
 
 ### v8.2.1 - 2019-03-01 
 **Changes:** 
 * Fix: Sharing queue issue with sites running WPML plugin
 
 ### v8.2.0 - 2019-02-09 
 **Changes:** 
 * New: The share post on publish feature is now in the lite version of the plugin. This should help with Facebook app review process (see revive.social docs)
 
 ### v8.1.8 - 2019-01-29 
 **Changes:** 
 * Fix: Minor bugs
 
 ### v8.1.7 - 2019-01-18 
 **Changes:** 
 * New: Adds basic support for WPML content sharing(see revive.social docs)
* Fix: Low PHP version notice was not showing the right text
* Fix: Minor bugs
 
 ### v8.1.6 - 2018-12-13 
 **Changes:** 
 * Fixed undefined variable error
 
 ### v8.1.5 - 2018-12-13 
 **Changes:** 
 * New: Made post share content filterable, you can now use post excerpt field (see docs)
* New: Pinterest shares will now link to the post on your website
* Changed: Bit.ly authentication method, old method will be deprecated in the future
* Changed: Custom message labels
* Fix: Pointer JavaScript error
* PRO Fix: Publish now feature not always showing
 
 ### v8.1.4 - 2018-12-03 
 **Changes:** 
 * New: Admin pointers for new plugin installs
* Change: Rename custom messages to "Share Variations"
* Fix: Automatically remove whitespace when adding credentials
* Fix: Excess blank space in shares caused by Gutenberg Editor
* PRO Fix: Publish now not showing on custom post types edit screens
 
 ### v8.1.3 - 2018-11-01 
 **Changes:** 
 * - Adds: Option to delete plugin settings on uninstall
* - Fix: Change twitter credential labels to match that on developer.twitter.com apps
* - Fix: Various typos
* - Fix: Issue with media library not loading when PRO plugin is installed in some cases
* - Fix: Error when other plugins also try to authenticate with Facebook
* - PRO: Adds support for magic tags for Custom Share Messages and Additional Text
* - PRO: Adds support for custom post type taxonomy hashtags
* - PRO: Adds Option to make share instantly option checked by default
 
 ### v8.1.2 - 2018-10-08 
 **Changes:** 
 * Fixed issue with hashtags in content
* Adds notice for PHP versions lower than 5.6
* Replaced goo.gl shortener with firebase dynamic links
 
 ### v8.1.1 - 2018-09-22 
 **Changes:** 
 * Fix rebrandly shortner missing feature.
* Adds option to disable the instant sharing feature.
 
 ### v8.1.0 - 2018-09-04 
 **Changes:** 
 * Adds support for Pinterest sharing feature
* Adds support for library media sharing feature
* Adds support for immediate post sharing feature
* Changed hashtags placement for Twitter
* Fixed hashtags for Tumblr
* Fixed Jetpack staging mode check
 
 ### v8.0.9 - 2018-06-18 
 **Changes:** 
 * Fix issue with Exclude posts blank page on non-English websites.
* Adds dedicated app workflow for Twitter authentication. 
* Adds tweet intent and review buttons in the header.
* Adds filter for content before sharing.
 
 ### v8.0.8 - 2018-05-25 
 **Changes:** 
 * Prevent sharing when the website is in the staging environment.
* Improve UI accessibility. 
* Adds possibility to fetch more post types.  
* Strip redundant shortcodes on post content sharing.
 
 ### v8.0.7 - 2018-05-10 
 **Changes:** 
 * Fix status migration issue from v7.
* Fix compatibility with the PRO version for the linkedin sharing on company pages.
* Fix compatibility with the PRO version for the thumblr sharing issues.
* Fix small typos in the plugin settings. 
 
 ### v8.0.6 - 2018-05-08 
 **Changes:** 
 * Fix hashtags issue when using post content as a source.
* Fix LinkedIn broken link when no image is used.
* Fix issue with sharing when multiple accounts are used with different custom schedules.
* Adds link only in the preview, remove from facebook message content.
* Adds limit for the number of logs.
 
 ### v8.0.5 - 2018-05-04 
 **Changes:** 
 * Fix issue with common hashtags using post content.
* Fix issue with add service when an account was removed from the list.
* Fix issue with cron lag between shares
* Improve disable state for pro services.
* Fix exclude posts inconsistency. 
* Fix incomplete UTM tags on certain shortners. 
* Fix refresh queue on start sharing. 
* Fix freezing message in frontend when the sharing is happening. 
* Fix Facebook limits regarding the number of accounts fetched. 
* Fix compatibility with PRO version regarding sharing on LinkedIn. 
 
 ### v8.0.4 - 2018-05-02 
 **Changes:** 
 * Fix issue with UTM tags and shortner consistency.
* Adds Exclude Posts as a separate page. 
* Fix issue with sharing stopped after the first share. 
* Fix timeline events refresh when the min interval changes. 
* Fix Facebook page accounts not showing in certain environments.
* Adds remove account feature for permanently delete an account from the list.
 
 ### v8.0.3 - 2018-04-28 
 **Changes:** 
 * Fix schedule synchronization issues.
* Fix LinkedIn authentication with the wrong redirect_url.
 
 ### v8.0.2 - 2018-04-27 
 **Changes:** 
 * Fix issue with old Facebook applications and strict OAuth urls settings.
* Fix issue taxonomies filter setting. 
* Fix filter by excluded posts issue.
* Fix issue when LinkedIn exceptions on login.
* Adds more exceptions handling for Facebook authentications.
* Fix compatibility with pro version for post_types and custom share messages.
 
 ### v8.0.1 - 2018-04-26 
 **Changes:** 
 * Fix Linkedin error on loading SDK class.
* Fix multiple twitter accounts warning message.
* Fix foreach loop on the services model.
* Fix Facebook authentication issues with application url.
* Adds notice when using an old Pro version.
 
 ### v8.0.0 - 2018-04-26 
 **Changes:** 
 * Major improvements to the codebase.
* Adds schedule and format per accounts, not per networks as it was before.
* Improve settings UI as well as the accounts authentication workflow.
* Improve posts selections per accounts.
* Improves logs reporting and messages.
* Adds major improvements to schedules trigger, implementing a new way of using wp-cron events for the plugin sharing.
 



























































