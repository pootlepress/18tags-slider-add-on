<?php
/**
 * Plugin Name: Slider - 18tags
 * Description: Adds a cool slider showing sticky posts on blog page
 * Version: 	1.0.0
 * Author: 		pootlepress
 * Author URI: 	http://www.pootlepress.com/
 * @developer Shramee <shramee.srivastav@gmail.com>
 * @package slider-18tags
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Pootle_Customisations {

	public function __construct () {
		add_action( 'wp', array( $this, 'wp' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		//exclude/include products in search
		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
	}

	public function wp() {
		if ( is_home() ) {
			add_action( 'eighteen_tags_before_content', array( $this, 'homepage_slider' ) );
		}
	}

	public function scripts() {
		if ( is_home() ) {
			wp_enqueue_style( 'slider-18tags-styles', plugin_dir_url( __FILE__ ) . '/assets/css.css', '', '4.2.5' );
			wp_enqueue_script( 'slider-18tags', plugin_dir_url( __FILE__ ) . '/assets/jquery.bxslider.min.js', array( 'jquery' ), '4.2.5', true );
		}
	}

	/**
	 * Removes or adds products CPT in search
	 * @param WP_Query $query
	 */
	public function pre_get_posts( $query ) {
		if ( is_home() && $query->is_main_query() ) {
			$query->set( 'post__not_in', get_option('sticky_posts') );
		}
	}

	function homepage_slider() {
		query_posts( array(
				'posts_per_page' => -1,
				'post__in'       => get_option('sticky_posts'),
				'meta_key'       => '_thumbnail_id',
			) ); ?>
		<?php if ( have_posts() ) { ?>
			<ul class="bxslider">
				<?php
				while ( have_posts() ) {
					the_post();
					echo '<li><a href="' . get_permalink() . '">';
					the_post_thumbnail( 'full', 'title="' . get_the_title() . '"' );
					echo '</a></li>';
				} ?>
			</ul>
			<?php
		}
		wp_reset_query();
	}
} // End Class

new Pootle_Customisations();