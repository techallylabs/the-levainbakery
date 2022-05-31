<?php
$container_classes     = array( '' );
$container_classes[]   = 'container ' . learts_get_option( 'header_width' );
$header_left_column_content = learts_get_option( 'header_left_column_content' );
$header_login_position = learts_get_option( 'header_login_position' );
$offcanvas_position    = learts_get_option( 'offcanvas_position' );
$left_sidebar               = learts_get_option( 'header_left_sidebar' );
?>
<?php Learts_Helper::slider(); ?>

<div class="<?php echo esc_attr( implode( ' ', $container_classes ) ); ?>">
	<div class="row">
		<div class="left-col">
			<?php
			if ( $offcanvas_position == 'left' ) {
				echo Learts_Templates::header_offcanvas_btn();
			} ?>
			<?php
			if ( $header_left_column_content == 'switchers' ) {
				echo Learts_Templates::language_switcher();
				echo Learts_Templates::currency_switcher();
			} elseif ( $header_left_column_content == 'social' ) {
				echo Learts_Templates::social_links();
			} elseif ( $header_left_column_content == 'search' ) {
				if ( $left_sidebar ) {
					dynamic_sidebar( 'sidebar-search' );
				}
			} elseif ( $header_left_column_content == 'widget' ) { ?>
				<div class="header-widget header-widget-left">
					<?php
					if ( $left_sidebar ) {
						dynamic_sidebar( $left_sidebar );
					} ?>
				</div>
			<?php } else {
				echo '';
			} ?>
		</div>
		<?php
		echo Learts_Templates::header_block_logo();
		echo Learts_Templates::header_block_site_menu();
		?>
		<div class="right-col header-tools">
			<?php
			if ( $header_login_position == 'left' ) {
				echo Learts_Templates::header_block_header_login();
			}
			echo Learts_Templates::header_block_search();

			if ( $header_login_position == 'between' ) {
				echo Learts_Templates::header_block_header_login();
			}

			echo Learts_Templates::header_block_wishlist();
			echo Learts_Templates::header_block_cart();
			echo Learts_Templates::header_block_mobile_btn();

			if ( $offcanvas_position == 'right' ) {
				echo Learts_Templates::header_offcanvas_btn();
			}
			?>
		</div>
	</div>
</div>
