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
		<div class="left-col content-<?php echo esc_attr( $header_left_column_content ); ?>">
			<?php
			if ( $header_left_column_content == 'switchers' ) {
				echo Learts_Templates::language_switcher();
				echo Learts_Templates::currency_switcher();
			} elseif ( $header_left_column_content == 'social' ) {
				echo Learts_Templates::social_links();
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
		<?php echo Learts_Templates::header_block_logo(); ?>
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
			?>
		</div>
	</div>
</div>
<div class="site-menu-wrap">
	<div class="<?php echo esc_attr( implode( ' ', $container_classes ) ); ?>">
		<?php

		if ( $offcanvas_position == 'left' ) {
			echo Learts_Templates::header_offcanvas_btn();
		}

		echo Learts_Templates::header_block_site_menu();

		if ( $offcanvas_position == 'right' ) {
			echo Learts_Templates::header_offcanvas_btn();
		}
		?>
	</div>
</div>
