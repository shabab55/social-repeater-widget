<?php

// Exit if Accessed Directly
if(!defined('ABSPATH')){
	exit;
}

/**
* Social Repeater widget class.
* Class Name: SRW_Social_Widget
*/

class SRW_Social_Widget extends WP_Widget{
    /**
    * Register widget with WordPress.
    */
    function __construct()
    {
        parent::__construct(
            'srw_social_repeater_widget', // Base ID
            __( 'Social repeater Widget', 'srw-widget' )
		);
	}

    /**
    * Front-end display of widget.
    *
    * @see WP_Widget::widget()
    *
    * @param array $args     Widget arguments.
	* @param array $instance Saved values from database.
	* widget function Show the social Buttons to Front Page
    */
    public function widget($args, $instance)
    {
		$max_entries = esc_attr( get_option( 'max_entries' ) );
		$max_entries = (empty($max_entries)) ? '5' : $max_entries;
		
		$srw_style = esc_attr( get_option( 'srw_style' ) );
        $srw_style = (empty($srw_style)) ? 'circle' : $srw_style;

		$srw_link_target = esc_attr( get_option( 'srw_link_target' ) );
		$srw_link_target = (empty($srw_link_target)) ? '_blank' : $srw_link_target;
		
		/* class to add for before widget */
		$classe_to_add_before_widget = 'social-widget-wrapper ';
		$classe_to_add_before_widget = 'class=" '.$classe_to_add_before_widget;
		$args['before_widget'] = str_replace('class="',$classe_to_add_before_widget,$args['before_widget']);
		
		/* class to add for before title*/
		$classe_to_add_before_title = '<div class="effect"><h2>';
		$args['before_title'] = str_replace('<h2 class="widget-title">',$classe_to_add_before_title,$args['before_title']);
		
		/* class to add for after title*/
		$classe_to_add_after_title = '</h2>';
		$args['after_title'] = str_replace('</h2></div>',$classe_to_add_after_title,$args['after_title']);

		echo $args['before_widget'];
        echo $args['before_title'];
        if(!empty($instance['title'])){
            echo $instance['title'];
        }
		echo $args['after_title'];
		echo "<div class='{$srw_style}'>
			  <div class='buttons'>";
        for($i=0; $i<$max_entries; $i++)
		{
			$block = $instance['block-' . $i];
			if(isset($block) && $block != "")
			{
				$social_platform_link = esc_url($instance['social_platform_link-' . $i]);

				$url=parse_url($social_platform_link, PHP_URL_HOST);
				$host=strstr($url, '.', true);
				?>

					<?php if(strpos($social_platform_link,(string)$host)){ ?>
						<a href="<?php echo esc_url($social_platform_link)?>" target="<?php echo $srw_link_target;?>" class="<?php echo $host;?>" title="Join us on <?php echo ucfirst($host);?>"><i class="fa fa-<?php echo $host;?>" aria-hidden="true"></i></a>
					<?php } ?>

				<?php
			}
		}
		echo "</div>
				</div>";
        echo $args['after_widget'];
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
		$max_entries = get_option( 'max_entries');
		$max_entries = (empty($max_entries)) ? '5' : $max_entries;
		$instance['title'] = strip_tags($new_instance['title']);
		for($i=0; $i<$max_entries; $i++){
			$block = $new_instance['block-' . $i];
			if($block == 0 || $block == "")
			{
				$instance['block-' . $i] = $new_instance['block-' . $i];
				$instance['social_platform-' . $i] = strip_tags($new_instance['social_platform-' . $i]);
				$instance['social_platform_link-' . $i] = strip_tags($new_instance['social_platform_link-' . $i]);

			} else  {
				$count = $block - 1;
				$instance['block-' . $count] = $new_instance['block-' . $i];
				$instance['social_platform-' . $count] = strip_tags($new_instance['social_platform-' . $i]);
				$instance['social_platform_link-' . $count] = strip_tags($new_instance['social_platform_link-' . $i]);

			}
		}
		return $instance;
	}
	

	/**
    * Back-end widget form.
    *
    * @see WP_Widget::form()
    *
    * @param array $instance Previously saved values from database.
    */

    public function form($instance)
    {
        $max_entries = get_option( 'max_entries' );
		$max_entries = (empty($max_entries)) ? '5' : $max_entries;
		$widget_add_id = $this->id . "-add";
        $title = !empty($instance['title']) ? $instance['title'] : __('Social Repeater Widget', 'srw-widget');
        
        $srw_html = '<p>';
        $srw_html .= '<label for="'.$this->get_field_id('title').'"> '. __( 'Widget Title', 'srw-widget' ) .' :</label>';
        $srw_html .= '<input id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.$title.'" />';
        $srw_html .= '<div class="'.$widget_add_id.'-input-containers"><div id="entries">';
        for( $i =0; $i<$max_entries; $i++){
			
            if(isset($instance['block-' . $i]) || isset($instance['social_platform-' . $i]))
            {
				$srw_tab_title = !empty($instance['social_platform-' . $i]) ? $instance['social_platform-' . $i] : __( 'Add Social Profile Details', 'srw-widget' );

                $display = (!isset($instance['block-' . $i]) || ($instance['block-' . $i] == "")) ? 'style="display:none;"' : '';
                $srw_html .= '<div id="entry'.($i+1).'" '.$display.' class="entrys"><span class="entry-title" onclick = "slider(this);"> '.$srw_tab_title.' </span>';
                $srw_html .= '<div class="entry-desc cf">';
                $srw_html .= '<input id="'.$this->get_field_id('block-' . $i ).'" name="'.$this->get_field_name('block-' . $i ).'" type="hidden" value="'.$instance['block-' . $i].'">';

                $social_platform    = esc_attr( $instance['social_platform-' . $i] );
                $social_platform_link    = esc_attr( $instance['social_platform_link-' . $i] );

                $srw_html .= '<p class="last desc">';
				$srw_html .= '<label for="'.$this->get_field_id('social_platform-' . $i).'"> '. __( 'Social Platform', 'srw-widget' ) .' :</label>';
				$srw_html .= '<input class="widefat" id="'.$this->get_field_id('social_platform-' . $i).'" name="'.$this->get_field_name('social_platform-' . $i).'" type="text" value="'.$social_platform.'" placeholder="'.__( 'Enter Social Platform name', 'srw-widget' ).'" />';
				$srw_html .= '</p><p>';
				$srw_html .= '<label for="'.$this->get_field_id('social_platform_link-' . $i).'"> '. __('Social platform Link', 'srw-widget' ) .' :</label>';
				$srw_html .= '<input class="widefat" id="'.$this->get_field_id('social_platform_link-' . $i).'" name="'.$this->get_field_name('social_platform_link-' . $i).'" type="url" value="'.$social_platform_link.'" placeholder="'.__( 'Enter Social Platform Link', 'srw-widget' ).'"/>';
                $srw_html .= '</p>';
                /* end wrapper with delete entry option */
                $srw_html .= '<p><a href="#delete"><span class="delete-row">'. __( 'Delete Row', 'srw-widget' ) .'</span></a></p>';
                $srw_html .= '</div></div>';
            }
        }
        $srw_html .= '</div></div>';
        $srw_html .= '<div id="message">'. __( 'Sorry, you reached to the limit of','srw-widget') .' "'.$max_entries.'" '. __( 'maximum entries', 'srw-widget' ) .'.</div>'  ;
        $srw_html .= '<div class="'.$widget_add_id.'" style="display:none;">' . __('Add New Platform', 'srw-widget') . '</div>';
        ?>
        <script>
		  jQuery(document).ready(function(e) {
			jQuery.each(jQuery(".<?php echo $widget_add_id; ?>-input-containers #entries").children(), function(){
				if(jQuery(this).find('input').val() != ''){
					jQuery(this).show();
				}
			});
			jQuery(".<?php echo $widget_add_id; ?>" ).bind('click', function(e) {
				var rows = 0;
				jQuery.each(jQuery(".<?php echo $widget_add_id; ?>-input-containers #entries").children(), function(){
					if(jQuery(this).find('input').val() == ''){
						jQuery(this).find(".entry-title").addClass("active");
						jQuery(this).find(".entry-desc").slideDown();
						jQuery(this).find('input').first().val('0');
						jQuery(this).show();
						return false;
					}
					else{
					  rows++;
					  jQuery(this).show();
					  jQuery(this).find(".entry-title").removeClass("active");
					  jQuery(this).find(".entry-desc").slideUp();
					}
				});
				if(rows == '<?php echo $max_entries;?>')
				{
					jQuery("#rew_container #message").show();
				}
			});
			jQuery(".delete-row" ).bind('click', function(e) {
				var count = 1;
				var current = jQuery(this).closest('.entrys').attr('id');
				jQuery.each(jQuery("#entries #"+current+" .entry-desc").children(), function(){
					jQuery(this).val('');
				});
				jQuery.each(jQuery("#entries #"+current+" .entry-desc p").children(), function(){
					jQuery(this).val('');
				});
				jQuery('#entries #'+current+" .entry-title").removeClass('active');
				jQuery('#entries #'+current+" .entry-desc").hide();
				jQuery('#entries #'+current).remove();
				jQuery.each(jQuery(".<?php echo $widget_add_id; ?>-input-containers #entries").children(), function(){
					if(jQuery(this).find('input').val() != ''){
						jQuery(this).find('input').first().val(count);
					}
					count++;
				});
			});
		});
		</script>
        <style>
			.cf:before, .cf:after { content: ""; display: table; }
			.cf:after { clear: both; }
			.cf { zoom: 1; }
			.clear { clear: both; }
			.clearfix:after { content: "."; display: block; height: 0; clear: both; visibility: hidden; }
			.clearfix { display: inline-block; }
			* html .clearfix { height: 1%; }
			.clearfix { display: block;}

			#rew_container input,select,textarea{ float: right;width: 60%;}
			#rew_container label{width:40%;}
			<?php echo '.'.$widget_add_id; ?>{
			background: #ccc none repeat scroll 0 0;font-weight: bold;margin: 20px 0px 9px;padding: 6px;text-align: center;display:block !important; cursor:pointer;
			}
			.desc{height:55px;}
			#entries{ padding:10px 0 0;}
			#entries .entrys{ padding:0; border:1px solid #e5e5e5; margin:10px 0 0; clear:both;}
			#entries .entrys:first-child{ margin:0;}
			#entries .delete-row{margin-top:20px;float:right;text-decoration: underline;color:red;}
			#entries .entry-title{ display:block; font-size:14px; line-height:18px; font-weight:600; background:#f1f1f1; padding:7px 5px; position:relative;}
			#entries .entry-title:after{ content: '\f140'; font: 400 20px/1 dashicons; position:absolute; right:10px; top:6px; color:#a0a5aa;}
			#entries .entry-title.active:after{ content: '\f142';}
			#entries .entry-desc{ display:none; padding:0 10px 10px; border-top:1px solid #e5e5e5;}
			#rew_container #entries p.last label{ white-space: pre-line; float:left; width:39%;}
			#message{padding:6px;display:none;color:red;font-weight:bold;}
		</style>
        <div id="rew_container">
		  <?php echo $srw_html;?>
		</div>
        <?php
    }
}