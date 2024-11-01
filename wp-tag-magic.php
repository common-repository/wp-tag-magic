<?php
/*
Plugin Name: Wp Tag Magic
Plugin URI: http://www.guidingwp.com
Description: Adds a sidebar widget or shortcode to display selected categories as a tag cloud. 
Author: Ramachandran Mariappan
Author URI: http://www.shoutmyblog.com
Version: 1.0
License: GPLv2
*/

class widget_wp_tag_magic extends WP_Widget
{
	// declares the widget_wp_tag_magic class
	function widget_wp_tag_magic(){
		$widget_ops = array('classname' => 'widget_wp_tag_magic', 'description' => __( "Displays selected categories or tags as a tag cloud") );
		$this->WP_Widget('tagcloud', __('Tag Cloud (Wp Tag Magic)'), $widget_ops);
	}
	
	// widget output
	function widget($args, $instance){
		extract($args);
	
		echo $before_widget;
		
		// omit title if not specified
		if ($instance['title'] != '')
			echo $before_title . $instance['title'] . $after_title;
		
		// build query
		$query = 'show_option_all=1&style=cloud&show_count=1&use_desc_for_title=0&hierarchical=0';
		$query .= '&order=' . $instance['order'];
		$query .= '&orderby=' . $instance['orderby'];		
		if($instance['min_count'] > 0) { $query .= '&hide_empty=1';}				
		
		// specified categories
		$inc_cats = array(); $exc_cats = array();
		foreach (explode("," ,$instance['cats_inc_exc']) as $spec_cat) {
			 if ($spec_cat < 0) { $exc_cats[] = abs($spec_cat); }
			 elseif ( $spec_cat > 0) { $inc_cats[] = abs($spec_cat); }
		}
		if(count($inc_cats) > 0) { $query .= '&include=' . implode(",", $inc_cats); }
		if(count($exc_cats) > 0) { $query .= '&exclude=' . implode(",", $exc_cats); }
		if ($instance['type'] == "Category") {
			// ensure minimum post count
			$cats = get_categories($query);		
			foreach ($cats as $cat)
			{
				if($instance['cats_inc_parent'] && $cat->parent != $instance['cats_inc_parent']) {
					continue;
				}			
				$catlink = get_category_link( $cat->cat_ID );
				$catname = $cat->cat_name;
				$count = $cat->category_count;
				if ($count >= $instance['min_count'])
				{
					$counts{$catname} = $count;
					$catlinks{$catname} = $catlink;
				}
			}
		} else {
			$tags = get_tags( $query );
			foreach ($tags as $tag) {
				$catlink = get_tag_link($tag->term_id);
				$catname = $tag->name;
				$count = $tag->count;
				if ($count >= $instance['min_count'])
				{
					$counts{$catname} = $count;
					$catlinks{$catname} = $catlink;
				}
			}
		}
		
		if (empty($counts)) {
			return;
		}
		echo '<div class="tagmagic-container">';
				
		foreach ($counts as $catname => $count) {
			$catlink = $catlinks{$catname};
			echo '<span class="tagmagic-box">' .
			'<a href="' .$catlink .'" title="see ' . $count . ' posts in ' . $catname . '">'.
			'<span class="tagmagic-name-group white">' .$catname. '</span>'.
			'<span class="tagmagic-count-group '.$instance['color'].'">' .$count. '</span></a></span>';
		}
		
		echo '</div>' . $after_widget;
	}
	
	
	// Creates the edit form for the widget.
	function form($instance){

		//Defaults
		$instance = wp_parse_args( (array) $instance, array('orderby' => 'name', 'order' => 'ASC', 'min' => 1, 'exclude'=>'') );
		
		?>
		<p>
			<label><?php echo __('Color: ') ?>
			<select id="<?php echo $this->get_field_id( 'color' ); ?>" name="<?php echo $this->get_field_name( 'color' ); ?>">		
				<option <?php if ( 'red' == $instance['color'] ) echo 'selected="selected"'; ?>>red</option>
				<option <?php if ( 'orange' == $instance['color'] ) echo 'selected="selected"'; ?>>orange</option>
				<option <?php if ( 'blue' == $instance['color'] ) echo 'selected="selected"'; ?>>blue</option>
				<option <?php if ( 'pink' == $instance['color'] ) echo 'selected="selected"'; ?>>pink</option>
				<option <?php if ( 'black' == $instance['color'] ) echo 'selected="selected"'; ?>>black</option>
				<option <?php if ( 'white' == $instance['color'] ) echo 'selected="selected"'; ?>>white</option>
				<option <?php if ( 'green' == $instance['color'] ) echo 'selected="selected"'; ?>>green</option>
			</select>
		</p>	
		<p>
			<label><?php echo __('Title:') ?>
				<input class="widefat" id="<?php echo $this->get_field_id('title') ?>" name="<?php echo $this->get_field_name('title') ?>" type="text" value="<?php echo htmlspecialchars($instance['title']) ?>" />
			</label>
		</p>
		<p>
			<label><?php echo __('Type: ') ?>
			<select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>">		
				<option <?php if ( 'Category' == $instance['type'] ) echo 'selected="selected"'; ?>>Category</option>
				<option <?php if ( 'Tag' == $instance['type'] ) echo 'selected="selected"'; ?>>Tag</option>
			</select>
		</p>			
		<p><?php echo __('Order by: ');	?>
			<label><input class="radio" type="radio" <?php if ( 'count' == $instance['orderby'] ) echo 'checked'; ?> name="<?php echo $this->get_field_name('orderby') ?>" id="<?php echo $this->get_field_id('orderby') ?>" value="count">&thinsp;<?php echo __('Count') ?></label>
			<label><input class="radio" type="radio" <?php if ( 'name' == $instance['orderby'] ) echo 'checked'; ?> name="<?php echo $this->get_field_name('orderby') ?>" id="<?php echo $this->get_field_id('orderby') ?>" value="name">&thinsp;<?php echo __('Name') ?></label>
		</p>		
		<p><?php echo __('Show by: ') ?>
			<label><input class="radio" type="radio" <?php if ( 'ASC' == $instance['order'] ) echo 'checked'; ?> name="<?php echo $this->get_field_name('order') ?>" id="<?php echo $this->get_field_id('order') ?>" value="ASC">&thinsp;<?php echo __('Acending') ?></label>
			<label><input class="radio" type="radio" <?php if ( 'DESC' == $instance['order'] ) echo 'checked'; ?> name="<?php echo $this->get_field_name('order') ?>" id="<?php echo $this->get_field_id('order') ?>" value="DESC">&thinsp;<?php echo __('Decending') ?></label>
		</p>
		<p><label for="<?php echo $this->get_field_name('min_count') ?>"><?php echo __('Minimum number of posts:') ?><input size="3" id="<?php echo $this->get_field_id('min_count') ?>" name="<?php echo $this->get_field_name('min_count') ?>" type="text" value="<?php echo htmlspecialchars($instance['min_count']) ?>" /></label></p>		
				
		<p>
			<label>
				<?php echo __('Comma separated category IDs (leave blank for all, to exclude a category use a negative categoryID numbers):') ?>
				<input class="widefat" id="<?php echo $this->get_field_id('cats_inc_exc') ?>" name="<?php echo $this->get_field_name('cats_inc_exc') ?>" type="text" value="<?php echo htmlspecialchars($instance['cats_inc_exc']) ?>" />
			</label>
		</p>
		<p>
			<label>
				<?php echo __('Parent category ID (leave blank for all):') ?>
				<input class="widefat" id="<?php echo $this->get_field_id('cats_inc_parent') ?>" name="<?php echo $this->get_field_name('cats_inc_parent') ?>" type="text" value="<?php echo htmlspecialchars($instance['cats_inc_parent']) ?>" />
			</label>
		</p>		
		
	<?php
	}
	
	
	// Saves the widgets settings.
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['color'] = strip_tags(stripslashes($new_instance['color']));
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['type'] = ($new_instance['type'] != '') ? $new_instance['type'] : 'tag';
		$instance['orderby'] = ($new_instance['orderby'] != '') ? $new_instance['orderby'] : 'name';
		$instance['order'] = ($new_instance['order'] != '') ? $new_instance['order'] : 'ASC';
		$instance['min_count'] = ($new_instance['min_count'] != '') ? (int) $new_instance['min_count'] : 1;
		$instance['cats_inc_exc'] = strip_tags(stripslashes($new_instance['cats_inc_exc']));		
		$instance['cats_inc_parent'] = strip_tags(stripslashes($new_instance['cats_inc_parent']));				
		return $instance;
	}
} // end class

// Register widget. Calls 'widgets_init' action after the widget has been registered.
function widget_wp_tag_magic_init() {
	register_widget('widget_wp_tag_magic');
}	
function widget_wp_tag_magic_load_resources()
{
	wp_enqueue_style(
		'style',
		plugins_url('css/style.css', __FILE__));
}


add_action('widgets_init', 'widget_wp_tag_magic_init');
add_action('wp_enqueue_scripts','widget_wp_tag_magic_load_resources');

// shortcode for use outside widgets
// TODO: refactor so it doesn't repeat the above
function tagmagic_tagcloud_func( $atts ) {
	
	// defaults
	extract( shortcode_atts( array(
		'color' => 'red',
		'type' => 'tag',
		'orderby' => 'name',
		'order' => 'ASC',
		'min_count' => 1,
		'cats_inc_exc' => '',
		'cats_inc_parent' => '',
	), $atts ) );

	// holder
	$holder = '';
	
	// build query
	$query = 'show_option_all=1&style=cloud&show_count=1&use_desc_for_title=0&hierarchical=0';
	$query .= '&order=' . $order;
	$query .= '&orderby=' . $orderby;		
	
	
	// specified categories
	$inc_cats = array(); $exc_cats = array();
	foreach (explode("," ,$cats_inc_exc) as $spec_cat) {
		if ($spec_cat < 0) { $exc_cats[] = abs($spec_cat); }
		elseif ( $spec_cat > 0) { $inc_cats[] = abs($spec_cat); }
	}
	if(count($inc_cats) > 0) { $query .= '&include=' . implode(",", $inc_cats); }
	if(count($exc_cats) > 0) { $query .= '&exclude=' . implode(",", $exc_cats); }
	if($min_count > 0) { $query .= '&hide_empty=1';}				
	if ($type == "Category") {
		// ensure minimum post count
		$cats = get_categories($query);		
		foreach ($cats as $cat)
		{
			if($instance['cats_inc_parent'] && $cat->parent != $instance['cats_inc_parent']) {
				continue;
			}				
			$catlink = get_category_link( $cat->cat_ID );
			$catname = $cat->cat_name;
			$count = $cat->category_count;
			if ($count >= $instance['min_count'])
			{
				$counts{$catname} = $count;
				$catlinks{$catname} = $catlink;
			}
		}
	} else {
		$tags = get_tags( $query );
		foreach ($tags as $tag) {
			$catlink = get_tag_link($tag->term_id);
			$catname = $tag->name;
			$count = $tag->count;
			if ($count >= $instance['min_count'])
			{
				$counts{$catname} = $count;
				$catlinks{$catname} = $catlink;
			}
		}
	}
	if (empty($counts)) {
		return;
	}	
	$holder .='<div class="tagmagic-container">';
			
	foreach ($counts as $catname => $count) {
		$catlink = $catlinks{$catname};
		$holder .= '<span class="tagmagic-box">' .
			'<a href="' .$catlink .'" title="see ' . $count . ' posts in ' . $catname . '">'.
			'<span class="tagmagic-name-group white">' .$catname. '</span>'.
			'<span class="tagmagic-count-group '.$color.'">' .$count. '</span></a></span>';
	}
	
	$holder .= '</div>';
	
	return $holder;
}

add_shortcode( 'tagmagic', 'tagmagic_tagcloud_func' );
?>