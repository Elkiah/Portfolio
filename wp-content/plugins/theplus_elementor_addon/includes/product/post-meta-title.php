<?php if(!isset($post_title_tag) && empty($post_title_tag)){
	$post_title_tag='h3';
} ?>
<<?php echo $post_title_tag; ?> class="post-title">
	<a href="<?php echo esc_url(get_the_permalink()); ?>" title="<?php echo esc_attr(get_the_title()); ?>"><?php echo esc_html(get_the_title()); ?></a>
</<?php echo $post_title_tag; ?>>

<?php 
	if ( class_exists( 'WC_Product_Subtitle' ) ) {
		do_action( 'theplus_after_product_title' );
	}
?>