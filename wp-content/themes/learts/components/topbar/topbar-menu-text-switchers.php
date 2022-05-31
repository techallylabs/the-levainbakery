<?php
$container_classes   = array( '' );
$container_classes[] = 'container ' . learts_get_option( 'topbar_width' );

$topbar         = learts_get_option( 'topbar' );
$topbar_bgcolor = learts_get_option( 'topbar_bgcolor' );
$topbar_scheme  = learts_get_option( 'topbar_scheme' );

if ( is_page() ) {
	$topbar_bgcolor = get_post_meta( Learts_Helper::get_the_ID(), 'learts_topbar_bgcolor' );
}

$topbar_classes   = array( 'topbar' );
$topbar_classes[] = 'topbar-' . $topbar;
$topbar_classes[] = 'topbar-scheme--' . $topbar_scheme;

if ( $topbar_scheme == 'custom' && ( $topbar_bgcolor == 'transparent' || empty( $topbar_bgcolor ) ) ) {
	$topbar_classes[] = 'topbar-transparent';
}
?>
<!-- Top bar -->
<div class="<?php echo esc_attr( implode( ' ', $topbar_classes ) ); ?>">
	<div class="<?php echo implode( ' ', $container_classes ) ?>">
		<div class="row">
			<div class="topbar-left col-xs-12 col-lg-3 hidden-lg-down">
				<?php
				if ( learts_get_option( 'topbar_menu' ) ) {
					// Top bar menu.
					$args = array(
						'theme_location'  => 'top_bar',
						'menu_id'         => 'topbar-menu',
						'container_class' => 'topbar-menu',
						'fallback_cb'     => false,
						'walker'          => new learts_Walker_Nav_Menu(),
					);

					if ( is_user_logged_in() ) {
						$args['menu'] = learts_get_option( 'topbar_logged_in_menu' );
					}

					wp_nav_menu( $args );
				}
				?>
			</div>
			<div class="topbar-center col-xs-12 col-lg-6 text-xs-center">
				<div class="topbar-text">
					<?php echo learts_get_option( 'topbar_text' ); ?>
				</div>
			</div>
			<div class="topbar-right col-xs-12 col-lg-3 hidden-lg-down">
				<?php
				echo Learts_Templates::currency_switcher();
				echo Learts_Templates::language_switcher();
				?>
			</div>
		</div>
	</div>
	<?php if ( learts_get_option( 'topbar_can_close' ) ) { ?>
		<a href="#" class="topbar-close-btn"><?php echo esc_html_e( 'Close', 'learts' ); ?></a>
	<?php } ?>
</div>
<?php if ( learts_get_option( 'topbar_can_close' ) ) { ?>
	<a href="#" class="hint--left hint--bounce topbar-open-btn"
	   aria-label="<?php echo esc_html_e( 'Open the top bar', 'learts' ); ?>"><i
			class="ti-angle-up"></i><?php echo esc_html_e( 'Open the top bar', 'learts' ); ?></a>
<?php } ?>
<!-- / End top bar -->
