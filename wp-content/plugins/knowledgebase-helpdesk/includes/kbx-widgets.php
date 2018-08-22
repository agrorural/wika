<?php

/**
 * 1. Adds KbxKnowledgebase_Widget widget.
 */
class KbxKnowledgebase_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'KbxKnowledgebase_Widget',
			esc_html__( 'Knowledgebase Articles', 'kbx-qc' ),
			array( 
				'description' => esc_html__( 'Widget to display most pupular, latest and most viewd knowledgebase articles.', 'kbx-qc' ),
			)
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) 
	{
		
		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		$limit = 5;
		$sort_by = "";

		if ( ! empty( $instance['limit'] ) ) {
			$limit = $instance['limit'];
		}

		if ( ! empty( $instance['sort_by'] ) ) {
			$sort_by = $instance['sort_by'];
		}

		echo kbx_get_widget_display( $sort_by, $limit );

		echo $args['after_widget'];

	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Recent Articles', 'kbx-qc' );
		$limit = ! empty( $instance['limit'] ) ? $instance['limit'] : esc_html__( '5', 'kbx-qc' );
		$sort_by = ! empty( $instance['sort_by'] ) ? $instance['sort_by'] : esc_html__( 'date', 'kbx-qc' );

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_attr_e( 'Title:', 'kbx-qc' ); ?>
			</label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'sort_by' ) ); ?>">
				<?php esc_attr_e( 'Sort By:', 'kbx-qc' ); ?>
				<select class='widefat' id="<?php echo $this->get_field_id('sort_by'); ?>"
                name="<?php echo $this->get_field_name('sort_by'); ?>" type="text">
		          <option value='date' <?php echo ($sort_by=='date')?'selected':''; ?>>
		            <?php esc_attr_e( 'Date', 'kbx-qc' ); ?>
		          </option>
		          <option value='popularity' <?php echo ($sort_by=='popularity')?'selected':''; ?>>
		            <?php esc_attr_e( 'Pupularity', 'kbx-qc' ); ?>
		          </option> 
		          <option value='views' <?php echo ($sort_by=='views')?'selected':''; ?>>
		            <?php esc_attr_e( 'Views', 'kbx-qc' ); ?>
		          </option> 
		        </select> 
			</label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>">
				<?php esc_attr_e( 'Limit:', 'kbx-qc' ); ?>
			</label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="text" value="<?php echo esc_attr( $limit ); ?>">
		</p>
		<?php 
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
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['limit'] = ( ! empty( $new_instance['limit'] ) ) ? strip_tags( $new_instance['limit'] ) : '';
		$instance['sort_by'] = ( ! empty( $new_instance['sort_by'] ) ) ? strip_tags( $new_instance['sort_by'] ) : '';

		return $instance;
	}

} // class KbxKnowledgebase_Widget


/**
 * 2. Adds KbxKnowledgebaseTagCloud widget.
 */
class KbxKnowledgebaseTagCloud extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'KbxKnowledgebaseTagCloud',
			esc_html__( 'Knowledgebase Tag Cloud', 'kbx-qc' ),
			array( 
				'description' => esc_html__( 'Widget to display tag cloud for Knowledgebase Tags.', 'kbx-qc' ),
			)
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) 
	{
		
		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		echo '<div class="kbx-tag-cloud">';

		wp_tag_cloud( array(
		   'smallest' => 8, // size of least used tag
		   'largest'  => 22, // size of most used tag
		   'unit'     => 'px', // unit for sizing the tags
		   'number'   => 45, // displays at most 45 tags
		   'orderby'  => 'name', // order tags alphabetically
		   'order'    => 'ASC', // order tags by ascending order
		   'taxonomy' => 'kbx_tag' // you can even make tags for custom taxonomies
		) );

		echo '</div>';

		echo $args['after_widget'];

	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Tag Cloud', 'kbx-qc' );

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_attr_e( 'Title:', 'kbx-qc' ); ?>
			</label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
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
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

} // class KbxKnowledgebaseTagCloud

// Register Widgets
function kbx_register_custom_widgets() {
    register_widget( 'KbxKnowledgebase_Widget' );
    register_widget( 'KbxKnowledgebaseTagCloud' );
}

add_action( 'widgets_init', 'kbx_register_custom_widgets' );