<?php
	/*
	Plugin Name: TCR Custom Feeds & Custom Post Types
	License: GPL
	Version: 1.0.0
	Plugin URI: http://thecellarroom.net
	Author: The Cellar Room Limited
	Author URI: http://www.thecellarroom.net
	Copyright (c) 2014 The Cellar Room Limited
	Description: WordPress feeds & Custom Post Types
	*/

	/*
	 * ACTIONS:
	 *
	 * customise the text below *between these* to suit your needs
	 *
	 * Perhaps also rename that custom feed and maybe add some more
	 *
	 * Once content put this in your mu-plugins folder
	 */


	defined( 'ABSPATH' ) or die();

	###################################################################################

	/*-----------------------------------------------------------------------------------*/
	/* Add custom feed content footer
	/*-----------------------------------------------------------------------------------*/

	function add_feed_content($content) {

		if(is_feed()) {

			$content .= '<p>*This post was first published on...*</p>';
			$content .= '<footer><p>*Add some Content here*</p></footer>';
		}

		return $content;
	}

	add_filter('the_excerpt_rss', 'add_feed_content');
	add_filter('the_content', 'add_feed_content');

	/*-----------------------------------------------------------------------------------*/
	/* Tidy up the feeds generator add our own
	/*-----------------------------------------------------------------------------------*/

	// Remove Generator, add our own
	function remove_wp_version_rss() {

		return '<generator>*Your Name*</generator>';
	}

	add_filter('the_generator','remove_wp_version_rss');


	/*-----------------------------------------------------------------------------------*/
	/* Set default feed
	/*-----------------------------------------------------------------------------------*/


	function atom_default_feed() {

		return 'atom';
	}

	add_filter('default_feed','atom_default_feed');

	/*-----------------------------------------------------------------------------------*/
	/* Tidy up the feeds
	/*-----------------------------------------------------------------------------------*/

	// remove the rdf and rss 0.92 feeds (nobody ever needs these)
	remove_action( 'do_feed_rdf', 'do_feed_rdf', 10, 1 );
	remove_action( 'do_feed_rss', 'do_feed_rss', 10, 1 );

	// point those feeds at rss 2 (it is backwards compatible with both of them)
	add_action( 'do_feed_rdf', 'do_feed_rss2', 10, 1 );
	add_action( 'do_feed_rss', 'do_feed_rss2', 10, 1 );

	// remove links
	remove_action( 'wp_head', 'feed_links', 2 );

	// add them back in along with any custom feeds
	function addBackPostFeed() {

		echo "<link rel='alternate' type='application/rss+xml' title='Main Site Atom Feed' href='/feed/' />".PHP_EOL;
		echo "<link rel='alternate' type='application/rss+xml' title='Main Site RSS 2.0 Feed' href='/feed/rss2/' />".PHP_EOL;
		echo "<link rel='alternate' type='application/rss+xml' title='Custom Feed' href='/feed/custom' />".PHP_EOL;

	}

	add_action('wp_head', 'addBackPostFeed');

	/*-----------------------------------------------------------------------------------*/
	/* Add custom feed
	*-----------------------------------------------------------------------------------*/

	function feed_custom( $comment ) {
		//get_template_part('feed', 'custom');
		assert( "locate_template( array('feeds/feed-custom.php'), true, false )" );
	}

	function add_my_feeds() {

		add_feed('custom','feed_custom');

	}

	add_action( 'init', 'add_my_feeds' );

	/*-----------------------------------------------------------------------------------*/
	/* Add custom post type to RSS Feed
	/*-----------------------------------------------------------------------------------*/

	function add_post_types_to_rss_feed( $args ) {

		if ( isset( $args['feed'] ) && !isset( $args['post_type'] ) )
			$args['post_type'] = array('post', '*custom post type name*');
		return $args;
	}

	add_filter( 'request', 'add_post_types_to_rss_feed' );

	// ends