=== WPCheckin Widget and Shortcode ===
Contributors: Adam Sommer
Tags: widget, shortcode, API, Google Places, location, social
Requires at least: 3.0.1
Tested up to: 3.6
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Post checkins to your WordPress site, and using Google Places choose a location closest to your geo location.

== Description ==

Why not be able to checkin on your blog so that your readers know where you are and have been?  Sure there are other checkin sites on the Internet, but now you can count your blog as one of them.

WPCheckin allows location checkins to be displayed in a widget or on any page via shortcode.  Using Google Places, choose a location closest to your geo location as determined by your browser's HTML5 location information.

This plugin requires a Google API key to be able to retrieve locations nearby, and stores checkins in a custom post type.

== Installation ==

There are a few options for installing and setting up this plugin.

= Upload Manually =

1. Download and unzip the plugin
2. Upload the 'wpcheckin' folder into the '/wp-content/plugins/' directory
3. Go to the Plugins admin page and activate the plugin

= Install Via Admin Area =

1. In the admin area go to Plugins > Add New and search for "WPCheckin"
2. Click install and then click activate

= To Setup The Plugin =

1. If you haven't created a key for the Google API follow these instructions: https://developers.google.com/console/help/#generatingdevkeys.
2. In the WordPress admin area go to Settings > Checkins and then enter in your Google API key.
3. Test that you are able to retrieve information from Google Places by clicking the "Get Places!" button.

= How to Use the Widget =

1. Setup the Plugin (refer to above)
2. Go to Appearance > Widgets and drag the 'WPCheckin' to your sidebar, or other widget area.
3. Enter in a Title to appear above the checkin widget.  For example "Here I am!"

= How to Use the Shortcode =

1. Navigate to the post or page you would like to add the badges to
2. Enter in the shortcode [wpcheckin]

== Frequently Asked Questions ==

= How does WPCheckin know where I am? =

WPCheckin uses the JavaScript navigator.geolocation.getCurrentLocation() function includedd in most modern browsers.

= Why use the Google Places API? =

The Google Places API provides a list of nearby places of interest making it easy to choose the one your are located at, or close by to.

== Screenshots ==

1. Once you have installed the plugin, navigate to Settings > Checkin in the admin area.
2. To add a widget to your site go to Appearance > Widgets.  Look for the 'WPCheckin' widget and drag to the appropraite widget area.  Enter in a title to appear above the widget and click save.
3. To add WPCheckin using a shortcode use [checkin] on any post or page.
4. View individual checkin posts on the Checkins type page.
5. WPCheckin in action with the twenty thirteen theme.

== Changelog ==

= 1.0.1 =

* Updated 'user' option to update when a new user is logged in.

= 1.0 =

* Changed Google Places radius to 50 for dev_mode and display_json.
* Set default title for widget.
* Added 'establishment' to Google Places types parameter.
* Better parsing and error checking of Json returned by Google Places API.

= 0.9 =

* This is the first version of the plugin.  No updates available yet.
