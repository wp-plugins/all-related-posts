<?php
/*
Plugin Name: All Related Posts
Plugin URI: http://blog.bigcircle.nl/about/wordpress-plugins
Description: Provides useful related links based on the visitor's browsing behavior
Author: Maarten Swemmer
Version: 1.0.4
Author URI: http://blog.bigcircle.nl
*/

// taking care of translations
$plugin_dir = plugin_basename( dirname( __FILE__ ) .'/languages' );
load_plugin_textdomain( 'all-related-posts', null, $plugin_dir );

function load_arp_related_posts() 
{
	register_widget( 'arp_related_posts' );
}
add_action( 'widgets_init', 'load_arp_related_posts' );

function arp_set_cookie()
{
	$domain = $_SERVER['HTTP_HOST'];
	$request = $_SERVER['REQUEST_URI'];
	$strippeddomain = '.'.str_replace('www.','',$domain);
	$uri='http://'.$domain.$request;
	
	if (!isset($_COOKIE['arp_ts'])) // then we have a new session and we can lookup some user history from cookies
	{
		setcookie("arp_ts", md5(time()), 0 , "/", $strippeddomain ); // identify this new session
		if (isset($_COOKIE['arp_ts_fp']))
		{
			setcookie("arp_ls_fp", $_COOKIE['arp_ts_fp'], 0 , "/", $strippeddomain ); // this session
		}
		if ($request != '/') // if a user is coming to the home page, don't overwrite the previous cookie
		{
			setcookie("arp_ts_fp", $uri, time()+15756926 , "/", $strippeddomain ); // the first page is only stored if the session cookie was not yet stored (which means it's the first page)
		}
	}
}
add_action( 'send_headers', 'arp_set_cookie' );

class arp_related_posts extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() 
	{
		/* Widget settings. */
		$widget_ops = array( 'description' => __('A widget that displays related posts.', 'arp_related_posts') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 400, 'height' => 350, 'id_base' => 'arp_related_posts' );

		/* Create the widget. */
		parent::__construct( 'arp_related_posts', __('All Related Posts', 'arp_related_posts'), $widget_ops, $control_ops );
	}
	
	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) 
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['postcount'] = strip_tags( $new_instance['postcount'] );
		$instance['excludewords'] = strip_tags( $new_instance['excludewords'] );
		$instance['incprevisit'] = strip_tags( $new_instance['incprevisit'] );
		$instance['incsefull'] = strip_tags( $new_instance['incsefull'] );
		$instance['incsetags'] = strip_tags( $new_instance['incsetags'] );
		$instance['incpostrel'] = strip_tags( $new_instance['incpostrel'] );
		$instance['type'] = strip_tags( $new_instance['type']['select_value'] );
		
		return $instance;
	}
	
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) 
	{

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('You might be interested in this', 'all-related-posts'), 'postcount' => '5', 'type' => '*', 'excludewords'=>$_SERVER['HTTP_HOST'],'incprevisit'=>'true', 'incpostrel'=>'true','incsefull'=>'true','incsetags'=>'true');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'all-related-posts'); ?></label><br />
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" size="30" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e('Search Pages or posts:', 'all-related-posts'); ?></label><br />
			<select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>[select_value]">
      			<option value="*" <?php if (empty($instance['type'])||$instance['type']=='*') echo "selected";   ?> ><?php _e('Posts and pages', 'all-related-posts'); ?></option>
      			<option value="post" <?php if ($instance['type'] == 'post') echo 'selected'; ?>><?php _e('Posts only', 'all-related-posts'); ?></option>
				<option value="page" <?php if ($instance['type'] == 'page') echo 'selected'; ?>><?php _e('Pages only', 'all-related-posts'); ?></option>
    		</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'postcount' ); ?>"><?php _e('Number of links to show:', 'all-related-posts'); ?></label><br />
			<input id="<?php echo $this->get_field_id( 'postcount' ); ?>" name="<?php echo $this->get_field_name( 'postcount' ); ?>" value="<?php echo $instance['postcount']; ?>" size="30" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'excludewords' ); ?>"><?php _e('Words to exclude in full search, separated by space. Put for example your blog\'s domain here:', 'all-related-posts'); ?></label><br />
			<input id="<?php echo $this->get_field_id( 'excludewords' ); ?>" name="<?php echo $this->get_field_name( 'excludewords' ); ?>" value="<?php echo $instance['excludewords']; ?>" size="30" />
		</p>
		<p><?php _e('Include:', 'all-related-posts'); ?>
		<br />
			<input class="checkbox" type="checkbox" <?php if ($instance['incprevisit']) echo 'checked="'.$instance['incprevisit'].'"'; ?> id="<?php echo $this->get_field_id('incprevisit'); ?>" name="<?php echo $this->get_field_name('incprevisit'); ?>" />
			<label for="<?php echo $this->get_field_id('incprevisit'); ?>"><?php _e('the first post a visitor came to on his previous visit', 'all-related-posts'); ?></label>
		<br />
			<input class="checkbox" type="checkbox" <?php if ($instance['incpostrel']) echo 'checked="'.$instance['incpostrel'].'"'; ?> id="<?php echo $this->get_field_id('incpostrel'); ?>" name="<?php echo $this->get_field_name('incpostrel'); ?>" />
			<label for="<?php echo $this->get_field_id('incpostrel'); ?>"><?php _e('posts related to the shown post', 'all-related-posts'); ?></label>
		<br />
			<input class="checkbox" type="checkbox" <?php if ($instance['incsetags']) echo 'checked="'.$instance['incsetags'].'"'; ?> id="<?php echo $this->get_field_id('incsetags'); ?>" name="<?php echo $this->get_field_name('incsetags'); ?>" />
			<label for="<?php echo $this->get_field_id('incsetags'); ?>"><?php _e('posts related to seach engine terms (tags and categories)', 'all-related-posts'); ?></label>
		<br />
			<input class="checkbox" type="checkbox" <?php if ($instance['incsefull']) echo 'checked="'.$instance['incsefull'].'"'; ?> id="<?php echo $this->get_field_id('incsefull'); ?>" name="<?php echo $this->get_field_name('incsefull'); ?>" />
			<label for="<?php echo $this->get_field_id('incsefull'); ?>"><?php _e('posts related to seach engine terms (full post content)', 'all-related-posts'); ?></label>
		</p>
		<p>Note: If no related posts are found for a post or page, no widget will be displayed.</p>
		<hr>
		<div style="text-align:right;font-size:0.8em"><?php _e('Like this plugin? A', 'all-related-posts'); ?> <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=79AKXNVRT8YSQ&lc=NL&item_name=All%20Related%20Posts%20plugin%20by%20Maarten&item_number=All%20Related%20Posts%20plugin&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted" target="_blank"><?php _e('small donation', 'all-related-posts'); ?></a> <?php _e('is highly appreciated.', 'all-related-posts'); ?><p></div>
		<?php
	}
	
	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) 
	{
		extract( $args );
		$posts = array();
		
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$type = $instance['type'];
		$postcount = $instance['postcount'];
		$excludewords = $instance['excludewords'];
	
		// if a user get's here by search engine, show posts with tags and categories similar to keywords
		if (count($posts) < 5 && $instance['incsetags'])
		{
			$posts = array_merge ($posts, $this->related_search_tag_posts($type));
			$posts = array_unique ($posts);
		}
		
		// if a user returned from a previous visit, show the first page he viewed before, assuming that that is a relevant page he either bookmarked before or found by searchengine, deep link, etc.
		if (count($posts) < 5 && $instance['incprevisit']) 
		{
			$posts = array_merge ($posts, $this->previous_post($type));
			$posts = array_unique ($posts);
		}
		
		// if on a single post, show related similarly tagged posts
		if (count($posts) < 5 && $instance['incpostrel'])
		{
			$posts = array_merge ($posts, $this->related_tag_posts($type));
			$posts = array_unique ($posts);
		}
				
		if (count($posts) < 5 && $instance['incsefull'])
		{
			// if a user get's here by search engine, show related posts based on whole database search
			$posts = array_merge ($posts, $this->related_search_posts($type,$excludewords));
			$posts = array_unique ($posts);
		}
		
		// if results, start echoing the widget
		if ($posts==null||!is_array($posts)||count($posts)==0) $widgetmain = null;
		else
		{
			array_splice($posts,$postcount,count($posts));
			$widgetmain = '<ul><li>'.join('</li><li>', $posts).'</li></ul>';
		}
		
		if ($widgetmain != null)
		{
			/* Before widget (defined by themes). */
			echo $before_widget;

			/* Display the widget title if one was input (before and after defined by themes). */
			if ( $title )
				echo $before_title . $title . $after_title;

			/* Display name from widget settings if one was input. */
			
			echo $widgetmain;
				
			/* After widget (defined by themes). */
			echo $after_widget;
		}
		
	}
	
	function previous_post($type)
	{
		$ret = array();
		if (!isset($_COOKIE['arp_ts']) && isset($_COOKIE['arp_ts_fp'])) // then we have a new session and we can lookup some user history from cookies
		{
			$url = $_COOKIE['arp_ts_fp']; // take the url from previous session
		}
		else if (isset($_COOKIE['arp_ls_fp']))
		{
			$url = $_COOKIE['arp_ls_fp']; // take the url from previous session
		}
		else return $ret; // no post found
		
		if ($url == get_option('home')) return $ret; // stored url is homepage, no need to show that as a relevant link
		
		$postid = url_to_postid( $url ); 
		if ($postid != 0 && $postid != get_the_ID())
		{
			$relpost = new arp_related_post($postid);
			$ret[]= $relpost->link;
		}
		return $ret;
		
	}
	
	function related_tag_posts($type)
	{
		$tags = get_the_tags(get_the_ID());
		$cats = get_the_category(get_the_ID());
		$ret = array();
		if (!is_single()) return $ret; // Only relevant when a single post or page is requested
		if (!is_array($tags) || count($tags) == 0) return $ret; 
		global $wpdb;
		global $wp_query;		
		
		// typical query:
		// SELECT wp_posts.ID, wp_terms.name from wp_posts, wp_terms, wp_term_relationships where (wp_posts.ID = wp_term_relationships.object_id) AND (wp_term_relationships.term_taxonomy_id = wp_terms.term_id ) AND (wp_posts.post_type='post' OR wp_posts.post_type='page') AND wp_posts.post_status = 'publish' AND wp_posts.ID <> 532 AND LCASE(wp_terms.name) = 'psychology' ORDER BY ID DESC 
		$query = "SELECT ".$wpdb->posts.".ID AS postid, count(".$wpdb->terms.".name) FROM ".$wpdb->posts.", ".$wpdb->terms.", ".$wpdb->term_relationships ." where (".$wpdb->posts.".ID = ".$wpdb->term_relationships .".object_id) AND (".$wpdb->term_relationships .".term_taxonomy_id = ".$wpdb->terms.".term_id ) AND (".$wpdb->posts.".post_type='post' OR ".$wpdb->posts.".post_type='page') AND ".$wpdb->posts.".post_status = 'publish' "; 
		$tags_where = array();
		foreach ($tags as $k=>$v)
		{
			$tags_where[] = "(".$wpdb->terms.".name = '".$v->name."') ";
		}
		foreach ($cats as $k=>$v)
		{
			$tags_where[] = "(".$wpdb->terms.".name = '".$v->cat_name."') ";
		}
		
		$tags_where_txt = join(' OR ', $tags_where);
		if ($tags_where_txt != '') { $query .= ' AND ('.$tags_where_txt.') '; } else return $ret;
		$thisid = get_the_ID();
		$query .= "AND ".$wpdb->posts.".ID <> ".$thisid." group by ".$wpdb->posts.".ID,".$wpdb->posts.".post_title ORDER BY count(".$wpdb->terms.".name) DESC";
		
		// time to execute the select to get a list of 
		$pageposts = $wpdb->get_col($query,0);
		
		//$ret[]=$query;
		
		if ($pageposts) {
			foreach ($pageposts as $ID) { 
				$relpost = new arp_related_post($ID);
				$ret[]= $relpost->link;
			}
		} 
		return $ret;
	}
	
	function related_search_tag_posts($type)
	{
		global $wpdb;
		global $wp_query;		
		$qs = $this->related_search_terms();
		$ret = array();
		if (!is_array($qs) || count($qs) == 0) return $ret; 
		
		// typical query:
		// SELECT wp_posts.ID, wp_terms.name from wp_posts, wp_terms, wp_term_relationships where (wp_posts.ID = wp_term_relationships.object_id) AND (wp_term_relationships.term_taxonomy_id = wp_terms.term_id ) AND (wp_posts.post_type='post' OR wp_posts.post_type='page') AND wp_posts.post_status = 'publish' AND wp_posts.ID <> 532 AND LCASE(wp_terms.name) = 'psychology' ORDER BY ID DESC 
		$query = "SELECT ".$wpdb->posts.".ID from ".$wpdb->posts.", ".$wpdb->terms.", ".$wpdb->term_relationships .", ".$wpdb->term_taxonomy." where (".$wpdb->posts.".ID = ".$wpdb->term_relationships .".object_id) AND (".$wpdb->term_relationships.".term_taxonomy_id = ".$wpdb->terms.".term_id ) AND (".$wpdb->terms.".term_id = ".$wpdb->term_taxonomy.".term_id) AND (".$wpdb->posts.".post_type='post' OR ".$wpdb->posts.".post_type='page') AND ".$wpdb->posts.".post_status = 'publish' "; 
		$tags_where = array();
		foreach ($qs as $k=>$v)
		{
			$tags_where[] = "(LCASE(".$wpdb->terms.".name) = '".mysql_real_escape_string($v)."') ";
		}
		$tags_where_txt = join(' OR ', $tags_where);
		if ($tags_where_txt != '') { $query .= ' AND ('.$tags_where_txt.') '; } else return $ret;
		$thisid = get_the_ID();
		$query .= "AND ".$wpdb->posts.".ID <> ".$thisid." ORDER BY ".$wpdb->term_taxonomy.".taxonomy DESC, ID DESC";
		
		//echo $query;
		
		// time to execute the select to get a list of 
		$pageposts = $wpdb->get_col($query,0);
		
		if ($pageposts) {
			foreach ($pageposts as $ID) { 
				$relpost = new arp_related_post($ID);
				$ret[]= $relpost->link;
			}
		} 
		return $ret;
	}
	

	function related_search_posts($type, $excludewords='') {
		global $wpdb;
		global $wp_query;
		$ret = array();

		$qs = $this->related_search_terms();
		
		// get rid of common words - don'd need to search for these:
		$common="  x 0 1 2 3 4 5 6 7 8 9 10 a able about act add after again air all also am an and animal answer any are as ask at back bad be been before being between big boy build but by call came can case cause change child city close come company could country cover cross day did differ different do does don't down draw each early earth end even every eye fact far farm father feel few find first follow food for form found four from get give go good government great group grow had hand hard has have he head help her here high him his home hot house how i if important in into is it its just keep kind know land large last late learn leave left let life light like line little live long look low made make man many may me mean men might more most mother move mr mrs much must my name near need never new next night no north not now number of off office old on one only or other our out over own page part people person picture place plant play point port press problem public put read real right round run said same saw say school sea see seem self sentence set she should show side small so some sound spell stand start state still story study such sun take tell than that the their them then there these they thing think this thought three through time to too tree try turn two under up upon us use very want was water way we week well went were what when where which while who why will with woman word work world would write year you young your ";
		$common .= ' '.$excludewords.' ';
		$good=0;
		$clean_qs = array();
		foreach ($qs as $k=>$v) 
		{
			if (strlen($v) >=3 && !(strpos($common,' '.$v.' ') > 0)) {
				$clean_qs[]=$v;
				$good++;
			}
		}
		if ($good==0) return $ret;
		array_splice($clean_qs,5,count($posts)); // only use first 5 words of search
		$sql= "SELECT ID from ".$wpdb->posts." where "; //  return most recent posts if nothing is found
		$where = array();
		foreach ($clean_qs as $k=>$v)
		{
			$where[] = "(concat('',LCASE(post_content),'') LIKE '%".mysql_real_escape_string($v)."%') ";
			$where[] = "(concat('',LCASE(post_title),'') LIKE '%".mysql_real_escape_string($v)."%') ";
			
		}
		$where_txt = join(' OR ', $where);
		if ($where_txt != '') { $where_txt = ' ('.$where_txt.') '; } else return $ret;
		
		$orderby = "ORDER BY ID DESC "; // order by most popular and on a tie the newest first
		
		// finish up with the paperwork:
		$thisid=get_the_ID();
		if ($type=='*') {
			$where_txt .= " AND (post_type='post' OR post_type='page') ";
		} else {
			$where_txt .= " AND post_type='$type' ";
		}
		$where_txt .= " AND post_status = 'publish' ";
		$where_txt .= " AND ID <> $thisid ";
		$sql=$sql.$where_txt.$orderby;
		//$ret[] = $sql;
		// time to execute the select to get a list of 
		$pageposts = $wpdb->get_col($sql,0);
		
		//$ret[] = $sql;
		if ($pageposts) {
			foreach ($pageposts as $ID) { 
				$relpost = new arp_related_post($ID);
				$ret[]= $relpost->link;
			}
		} 
		
		return $ret;
	}
	
	function related_search_terms()
	{
		$qs = array();
		// let's see if we are in a page referred by google or such
		$ref=urldecode($_SERVER['HTTP_REFERER']);
		// if no referer, no work to be done
		if ($ref == '') return $qs;
		$q='';
		$sep='';
		if ((strpos($ref,'google')>0||strpos($ref,'bing')>0||strpos($ref,'ask.com')>0||strpos($ref,'search.aol.')>0 )) {
			if (strpos($ref,'&q=')>0) $sep='&q=';
			else if (strpos($ref,'?q=')>0) $sep='?q=';
		} else if (strpos($ref,'yahoo')>0&&strpos($ref,'&p=')>0) {
			if (strpos($ref,'&p=')>0) $sep='&p=';
			else if (strpos($ref,'?p=')>0) $sep='?p=';
		} else if (strpos($ref,'baidu')>0) {
			if (strpos($ref,'?wd=')>0) $sep='?wd=';
		} else 
			return $qs; // not a search engine - get out of here.
		if ($sep==null) return $qs; // no search parameter found	
		$q=substr($ref,strpos($ref,$sep)+strlen($sep));
		$n=strpos($q,'&');
		if ($n>0) $q=substr($q,0,$n);
		if (empty($q)) return $qs; // not a query let's leave
		$q=trim($q);
		if (empty($q)) return $qs; 
		
		$q=str_replace('&quote',' ',$q); 
		$q=str_replace('%20',' ',$q); 
		$q=str_replace('_',' ',$q); 
		$q=str_replace('.',' ',$q); 
		$q=str_replace('-',' ',$q); 
		$q=str_replace('+',' ',$q); 
		$q=str_replace('"',' ',$q); 
		$q=str_replace("\'",' ',$q); 
		$q=str_replace('`',' ',$q); 
		$q=str_replace('  ',' ',$q);
		$q=str_replace('  ',' ',$q);
		$q=str_replace('"','',$q);
		$q=str_replace('`','',$q);
		$q=str_replace("'",'',$q);
		$q=trim($q);
		// put it into an array
		$qs=explode(' ',$q);
		
		return $qs;
	}
		
}

class arp_related_post
{	
	public $id;
	public $title;
	public $permalink;
	
	function __construct($id)
	{
		$post_data = get_post(intval($id)); 
		$this->title = $post_data->post_title;
		$this->permalink = get_permalink($id); 
		$this->link = $this->link();
	}
	
	function link()
	{
		return '<a href="'.$this->permalink.'">'.$this->title.'</a>';
	}

}

function arp_add_donate_link($links, $file) 
{
	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
	if ($file == $this_plugin)
	{
		$donate_link = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=79AKXNVRT8YSQ&lc=NL&item_name=All%20Related%20Posts%20plugin%20by%20Maarten&item_number=All%20Related%20Posts%20plugin&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted" target="_blank">'.__('Donate', 'all-related-posts').'</a>';
		$links[] = $donate_link;
	}
	return $links;
}

add_filter('plugin_row_meta', 'arp_add_donate_link', 10, 2 );

?>