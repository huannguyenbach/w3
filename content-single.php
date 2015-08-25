<?php
/**
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;

// thumbnail visibility
$hide_thumbnail = (bool) get_post_meta($post->ID, '_dt_post_options_hide_thumbnail', true);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php do_action('presscore_before_post_content'); ?>

	<?php if ( !post_password_required() ) : ?>

	<?php

	$img_class = 'alignleft';
	$img_options = array( 'w' => 270, 'z' => 1 );

	$post_format = get_post_format();

	switch ( $post_format ) {

		case 'video':

			// thumbnail
			if ( has_post_thumbnail() && ( $video_url = esc_url( get_post_meta( get_post_thumbnail_id(), 'dt-video-url', true ) ) ) ) {
				echo '<div class="post-video alignnone">' . dt_get_embed( $video_url ) . '</div>';
			}

			// post content
			the_content();

			break;

		case 'gallery':

			// post content
			the_content();

			break;

		case 'aside':
		case 'link':
		case 'quote':
		case 'status':

			// post content
			dt_get_template_part( 'blog/blog-post-content-part', $post_format );
			break;

		case 'image':
		default:
			$img_class = 'alignnone';
			$img_options = false;

			// thumbnail
			if ( has_post_thumbnail() && !$hide_thumbnail ) {
				$thumb_id = get_post_thumbnail_id();
				$thumb_meta = wp_get_attachment_image_src( $thumb_id, 'full' );

				dt_get_thumb_img( array(
					'class'		=> $img_class . ' rollover rollover-zoom dt-single-mfp-popup dt-mfp-item mfp-image',
					'img_meta' 	=> $thumb_meta,
					'img_id'	=> $thumb_id,
					'options' 	=> $img_options,
					'wrap'		=> '<a %HREF% %CLASS% %CUSTOM% title="%RAW_ALT%" data-dt-img-description="%RAW_TITLE%"><img %IMG_CLASS% %SRC% %SIZE% %IMG_TITLE% %ALT% /></a>',
				) );
			}

			// post content
			the_content();

	}
	?>

	<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', LANGUAGE_ZONE ), 'after' => '</div>' ) ); ?>

	<?php
	$post_tags = '';
	$config = presscore_get_config();
	if ( $config->get( 'post.meta.fields.tags' ) ) {
		$post_tags = presscore_get_post_tags_html();
	}

	$share_buttons = presscore_display_share_buttons('post', array('echo' => false));

	if ( $share_buttons || $post_tags ) {
		printf( '<div class="post-meta wf-mobile-collapsed">%s</div>', $post_tags . $share_buttons );
	}
	?>
<!--social-->
		<div class="content-social">
		<ul class="share-button">
				<li><div class="g-plus" data-action="share" data-annotation="none" data-height="20"></div></li>
		    <li><div class="fb-share-button" data-layout="button"></div></li>
		    <li><a href="https://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a></li>
				<li><a href="//www.pinterest.com/pin/create/button/" data-pin-do="buttonBookmark"  data-pin-color="red" class="pinterest-share"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_red_20.png" /></a></li>
			</ul>
		</div>
		<style>
			.content-social {
				width: 100%;
				position: relative;
				margin: 30px 0px;
			}
		</style>
		<!-- Pinterest-->
		    <script type="text/javascript">
		      (function(d){
		          var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
		          p.type = 'text/javascript';
		          p.async = true;
		          p.src = '//assets.pinterest.com/js/pinit.js';
		          f.parentNode.insertBefore(p, f);
		      }(document));
		    </script>
		    <!--google plus-->
		    <script >
		        window.___gcfg = {
		          lang: 'en-US',
		          parsetags: 'onload'
		        };
		    </script>
		    <script src="https://apis.google.com/js/platform.js" async defer></script>
		    <!--Facebook -->
		   <div id="fb-root"></div>
		      <script>(function(d, s, id) {
		        var js, fjs = d.getElementsByTagName(s)[0];
		        if (d.getElementById(id)) return;
		        js = d.createElement(s); js.id = id;
		        js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.0";
		        fjs.parentNode.insertBefore(js, fjs);
		      }(document, 'script', 'facebook-jssdk'));</script>
		    <!--TWISTER-->
		    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
<!--end social-->
	<?php
	// 'theme options' -> 'general' -> 'show author info on blog post pages'
	if ( $config->get( 'post.author_block' ) ) {
		presscore_display_post_author();
	}
	?>
	
	<?php presscore_display_related_posts(); ?>

	<?php else: ?>

		<?php the_content(); ?>

	<?php endif; // !post_password_required ?>

	<?php do_action('presscore_after_post_content'); ?>

</article><!-- #post-<?php the_ID(); ?> -->
