<?php
/**
 * This is the class that creates and handles the related articles widget
 */

// Exit if called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Basepress_Related_Articles_Widget extends WP_Widget {

	/**
	 * Basepress_Related_Articles_Widget constructor.
	 *
	 * @since 1.0.0
	 */
	function __construct() {
		parent::__construct(
			'basepress_related_articles_widget', // Base ID
			esc_html__( 'knowledge Base - Related Articles', 'basepress' ), // Name
			array( 'description' => esc_html__( 'Lists articles related to the current one in the Knowledge Base', 'basepress' ) ) // Args
		);
	}




	/**
	 * Front-end display of widget.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		//If the widget is called outside of BasePress pages return
		if ( get_post_type() != 'knowledgebase' ) {
			return;
		}

		//If is not a single post page return
		if ( ! is_single() ) {
			return;
		}

		//Fetch the list of popular articles
		$articles = $this->get_articles_list( $instance );

		//Render the widget only if we have some articles
		if ( '' != $articles ) {
			echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'];
				echo apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance );
				echo $args['after_title'];
			}

			echo $articles;

			echo $args['after_widget'];
		}
	}




	/**
	 * Back-end widget form.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance
	 * @return string|void
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$count = ! empty( $instance['count'] ) ? $instance['count'] : '';
		$order_by = ! empty( $instance['order-by'] ) ? $instance['order-by'] : 'custom';

		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'basepress' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_attr_e( 'Number of articles to show:', 'basepress' ); ?></label> 
		<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" value="<?php echo $count; ?>" size="3">
		</p>
		
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'order-by' ) ); ?>"><?php esc_attr_e( 'Order by:', 'basepress' ); ?></label> 
		<select class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'order-by' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order-by' ) ); ?>" type="number" value="<?php echo $order_by; ?>">
			<option value="custom"<?php echo ( 'custom' == $order_by ? ' selected' : '' ); ?>><?php echo esc_attr_e( 'Custom', 'basepress' ); ?></option>
			<option value="date-asc"<?php echo ( 'date-asc' == $order_by ? ' selected' : '' ); ?>><?php echo esc_attr_e( 'Date Ascending', 'basepress' ); ?></option>
			<option value="date-desc"<?php echo ( 'date-desc' == $order_by ? ' selected' : '' ); ?>><?php echo esc_attr_e( 'Date Descending', 'basepress' ); ?></option>
		</select>
		</p>
		
		<?php
	}




	/**
	 * Sanitizes widget form values as they are saved.
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['count'] = ( ! empty( $new_instance['count'] ) ) ? strip_tags( $new_instance['count'] ) : '';
		$instance['order-by'] = ( ! empty( $new_instance['order-by'] ) ) ? strip_tags( $new_instance['order-by'] ) : 'custom';

		return $instance;
	}




	/**
	 * Generates the related articles list
	 *
	 * @since 1.0.0
	 *
	 * @param $instance
	 * @return string
	 */
	private function get_articles_list( $instance ) {

		global $basepress_utils, $post;
		$options = $basepress_utils->get_options();
		$post_icons = $basepress_utils->icons->post;
		$show_icons = basepress_show_post_icon();
		$classes = '';

		switch ( $instance['order-by'] ) {
			case 'date-asc':
				$orderby = 'date';
				$order   = 'ASC';
				break;

			case 'date-desc':
				$orderby = 'date';
				$order   = 'DESC';
				break;

			case 'custom':
			default:
				$orderby = 'menu_order';
				$order   = 'ASC';
				break;
		}

		// get the current article's taxonomy terms
		$custom_taxterms = wp_get_object_terms( $post->ID, 'knowledgebase_cat', array( 'fields' => 'ids' ) );

		// arguments
		$args = array(
			'post_type'      => 'knowledgebase',
			'post_status'    => 'publish',
			'posts_per_page' => $instance['count'],
			'orderby'        => $orderby,
			'order'          => $order,
			'tax_query'      => array(
				array(
					'taxonomy' => 'knowledgebase_cat',
					'field'    => 'id',
					'terms'    => $custom_taxterms[0],
				),
			),
			'post__not_in' => array( $post->ID ),
		);

		/**
		 * Filter to modify the args to query the post to show on related articles widget
		 */
		$args = apply_filters( 'basepress_related_articles_args', $args, $instance );

		$related_items = new WP_Query( $args );

		$output = '';

		if ( $related_items->have_posts() ) {

			$output .= '<ul class="bpress-widget-list">';
			while ( $related_items->have_posts() ) {
				$related_items->the_post();

				$post_icon = '';
				if ( $show_icons ) {
					$post_icon = get_post_meta( get_the_ID(), 'basepress_post_icon', true );
					$post_icon = $post_icon ? $post_icon : $post_icons->default;
					$classes = ' show-icon';
				}

				$output .= '<li class="bpress-widget-item related-article' . $classes . '">';
				if ( isset( $options['show_post_icon'] ) ) {
					$output .= '<span aria-hidden="true" class="' . $post_icon . ' bpress-widget-icon"></span>';
				}
				$output .= '<a href="' . get_the_permalink() . '" title="' . the_title_attribute( array('echo' => false) ) . '">';
				$output .= get_the_title();
				$output .= '</a>';
				$output .= '</li>';

			}
			$output .= '</ul>';
		}
		return $output;

		// Reset Post Data
		wp_reset_postdata();
	}
}
?>