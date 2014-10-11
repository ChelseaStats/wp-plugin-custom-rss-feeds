<?php
/*
Template Name: Feed
*/
 
function lastbuilddate( $timestamp = null ) {
  $timestamp = ($timestamp==null) ? time() : $timestamp;
  echo date(DATE_RSS, $timestamp);
}

$lastpost = $numposts - 1;

header("Content-Type: application/rss+xml; charset=UTF-8");
echo '<?xml version="1.0"?>';
?><rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	>

<channel>
		<title><?php bloginfo('name'); ?></title>
		<atom:link href="<?php bloginfo('url'); ?>/feed/" rel="self" type="application/rss+xml" />
		<link><?php bloginfo('url'); ?></link>
		<description><?php bloginfo('description'); ?></description>
		<lastBuildDate><?php lastbuilddate(strtotime($ps[$lastpost]->post_date_gmt) ); ?></lastBuildDate>	
		<generator>http://wordpress.org/?v=<?php bloginfo('version'); ?></generator>
		<language>en</language>
		<sy:updatePeriod>hourly</sy:updatePeriod>
		<sy:updateFrequency>1</sy:updateFrequency>

<?php if (have_posts()) : ?>
<?php query_posts('showposts=25'); ?>
	<?php while (have_posts()) : the_post(); update_post_caches($posts); ?>
		
			<item>
			<title><?php the_title(); ?></title>
			<link><?php permalink_single_rss(); ?></link>
			<comments><?php permalink_single_rss(); ?>#comments</comments>
			<pubDate><?php the_time('D, d M o G:i:s O') ?></pubDate>
			<dc:creator><?php the_author(); ?></dc:creator>
			<?php the_category_rss() ?>
			<guid isPermaLink="false"><?php echo get_permalink($post->ID); ?></guid>
			<description><![CDATA[<?php the_excerpt(); ?>]]></description>
			<content:encoded><![CDATA[<?php the_content('Read more...'); ?>]]></content:encoded>
			<wfw:commentRss><?php post_comments_feed_link() ?></wfw:commentRss>
			<slash:comments><?php comments_number('zero', 'one', 'more'); ?></slash:comments>
			</item>
			
	<?php endwhile; ?>
<?php else : ?>

<?php endif; ?>		
	</channel>
</rss>
