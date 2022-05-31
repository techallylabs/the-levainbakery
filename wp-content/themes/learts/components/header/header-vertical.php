<?php
$container_classes          = array( '' );
$container_classes[]        = 'container ' . learts_get_option( 'header_width' );
$offcanvas_position         = learts_get_option( 'offcanvas_position' );
$header_left_column_content = learts_get_option( 'header_left_column_content' );
$header_text                = learts_get_option( 'header_text' );
?>
<?php Learts_Helper::slider(); ?>

<div class="<?php echo esc_attr( implode( ' ', $container_classes ) ); ?>">
	<div class="row">
		<?php echo Learts_Templates::header_block_logo(); ?>
		<div class="menu-wrapper">
			<?php echo Learts_Templates::header_block_site_menu(); ?>
		</div>
		<div class="header-tools">
			<?php
			echo Learts_Templates::header_block_search();
			echo Learts_Templates::header_block_wishlist();
			echo Learts_Templates::header_block_cart();
			echo Learts_Templates::header_block_mobile_btn();
			?>
		</div>
		<?php if ( $header_text ) { ?>
			<p class="header-text">
				<?php echo learts_get_option( 'header_text' ); ?>
			</p>
		<?php } ?>
	</div>
</div>
