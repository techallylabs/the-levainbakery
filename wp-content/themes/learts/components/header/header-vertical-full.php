<?php
$container_classes = array( 'container' );
$header_left_column_content = learts_get_option( 'header_left_column_content' );
$left_sidebar      = learts_get_option( 'header_left_sidebar' );

?>
<div class="<?php echo esc_attr( implode( ' ', $container_classes ) ); ?>">
	<div class="row">
		<?php echo Learts_Templates::header_block_logo(); ?>
		<div class="menu-wrapper">
			<?php echo Learts_Templates::header_block_site_menu(); ?>
		</div>
		<div class="header-tools layout-base">
			<?php echo Learts_Templates::header_block_header_login(); ?>
			<?php echo Learts_Templates::header_block_wishlist(); ?>
			<?php echo Learts_Templates::header_block_cart(); ?>
			<?php echo Learts_Templates::header_block_mobile_btn(); ?>
			<?php if ( $header_left_column_content == 'widget' ) { ?>
				<div class="search-col">
					<?php
					if ( $left_sidebar ) {
						dynamic_sidebar( $left_sidebar );
					} ?>
				</div>
			<?php } ?>
			<?php if ( $header_left_column_content == 'search' ) { ?>
				<div class="search-col">
					<?php
					if ( $left_sidebar ) {
						dynamic_sidebar( 'sidebar-search' );
					} ?>
				</div>
			<?php } ?>
			<?php echo Learts_Templates::header_block_search(); ?>
		</div>
		<?php
		if ( Learts_get_option( 'mobile_menu_social' ) ) {
			echo Learts_Templates::social_links();
		}
		?>
	</div>
</div>
