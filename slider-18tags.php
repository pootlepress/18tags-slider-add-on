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
	}

	public function wp() {
		if ( is_front_page() ) {
			add_action( 'eighteen_tags_before_content', array( $this, 'homepage_slider' ) );
		}
	}

	public function scripts() {
		if ( is_front_page() ) {
			wp_enqueue_style( 'slider-18tags-styles', plugin_dir_url( __FILE__ ) . '/assets/css.css', '', '4.2.5' );
			wp_enqueue_script( 'slider-18tags', plugin_dir_url( __FILE__ ) . '/assets/jquery.bxslider.min.js', array( 'jquery' ), '4.2.5', true );
		}
	}

	function homepage_slider() {
		query_posts(array(
				'posts_per_page' => 3,
				'post__in'       => get_option('sticky_posts'),
				'meta_key' => '_thumbnail_id',
			)
		); ?>
		<?php if ( have_posts() ) { ?>
			<ul class="bxslider">
				<?php
				while ( have_posts() ) : the_post(); ?>
					<?php
					if ( has_post_thumbnail() ) {
						echo '<li>';
						the_post_thumbnail( 'full' );
						echo '</li>';
					}
					?>
				<?php endwhile; ?>
			</ul>
			<script>
				jQuery( document ).ready( function ( $ ) {
					$( '.bxslider' ).bxSlider( {
						paged : false
					} );
				} );
			</script>
			<?php
		}
		wp_reset_query();
	}
} // End Class

new Pootle_Customisations();