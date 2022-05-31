<?php
$container_classes          = array( '' );
$container_classes[]        = 'container ' . learts_get_option( 'header_width' );
$offcanvas_position         = learts_get_option( 'offcanvas_position' );
$header_left_column_content = learts_get_option( 'header_left_column_content' );
$left_sidebar               = learts_get_option( 'header_left_sidebar' );
$header_login_position      = learts_get_option( 'header_login_position' );

?>
<?php Learts_Helper::slider(); ?>

<div class="<?php echo esc_attr( implode( ' ', $container_classes ) ); ?>">
	<div class="row">
		<?php echo Learts_Templates::header_block_logo(); ?>
		<div class="search-col">
			<?php
			if ( learts_get_option( 'search_on' ) ) {
				echo Learts_Templates::search_form( 'inline-search' );
			} ?>
		</div>
		<div class="right-col header-tools">
			<?php
			if ( $header_login_position == 'left' ) {
				echo Learts_Templates::header_block_header_login();
			}
			if ( $header_login_position == 'between' ) {
				echo Learts_Templates::header_block_header_login();
			}

			echo Learts_Templates::header_block_wishlist();
			echo Learts_Templates::header_block_cart();
			echo Learts_Templates::header_block_mobile_btn();
			?>
		</div>
	</div>
</div>
<div class="site-menu-wrap">
	<div class="<?php echo esc_attr( implode( ' ', $container_classes ) ); ?>">
		<div class="row">
			<div class="col-md-3 hidden-lg-down">
				<?php if ( is_active_sidebar('sidebar-menu') ) {
					dynamic_sidebar( 'sidebar-menu' );
				}
				?>
			</div>
			<div class="col-md-7 hidden-lg-down">
				<?php echo Learts_Templates::header_block_site_menu(); ?>
			</div>
			<div class="col-md-2 hidden-lg-down">
				<?php if ( $header_left_column_content == 'widget' && $left_sidebar ) {
					dynamic_sidebar( $left_sidebar );
				}
				?>
			</div>
		</div>
	</div>
</div>
