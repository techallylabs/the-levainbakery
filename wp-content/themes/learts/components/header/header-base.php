<?php
$container_classes     = array( '' );
$container_classes[]   = 'container ' . learts_get_option( 'header_width' );
$header_login_position = learts_get_option( 'header_login_position' );
$offcanvas_position    = learts_get_option( 'offcanvas_position' );

?>
<?php Learts_Helper::slider(); ?>

<div class="<?php echo esc_attr( implode( ' ', $container_classes ) ); ?>">
	<div class="row">
		<?php
		if ( $offcanvas_position == 'left' ) {
			echo Learts_Templates::header_offcanvas_btn();
		}
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
