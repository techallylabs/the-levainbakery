<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package learts
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>

	<?php if ( function_exists( 'wp_site_icon' ) ) {
		wp_site_icon();
	} ?>

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php echo Learts_Templates::mobile_menu(); ?>
<?php
if ( learts_get_option( 'offcanvas_button_on' ) ) {
	echo Learts_Templates::header_offcanvas();
}
?>

<?php do_action( 'learts_site_before' ); ?>

<div id="page-container">

	<?php do_action( 'learts_page_container_top' ); ?>

	<?php

	if ( learts_get_option( 'topbar_on' ) ) {
		get_template_part( 'components/topbar/topbar-' . learts_get_option( 'topbar' ) );
	}

	$header = apply_filters( 'learts_header_layout', learts_get_option( 'header' ) );

	if ( learts_get_option( 'search_on' ) && $header !== 'sub-menu-bottom' ) {
		echo Learts_Templates::search_form();
	}

	$header_classes   = array( 'site-header' );
	$header_classes[] = 'header-' . $header;
	$header_classes[] = 'header-scheme--' . learts_get_option( 'header_scheme' );

	if ( isset ($_GET['header-split'])){
		$header_classes[] = 'header-custom-height';
	}

	if ( ! learts_get_option( 'breadcrumbs' ) && ! learts_get_option( 'page_title_on' ) ) {
		$header_classes[] = 'has-margin-bottom';
	}

	?>

	<!-- Header -->
	<header class="<?php echo implode( ' ', $header_classes ); ?>">
		<?php get_template_part( 'components/header/header-' . learts_get_option( 'header' ) )?>
	</header>
	<!-- End Header -->
	<?php
	$remove_whitespace = learts_get_option( 'remove_whitespace' );
	$page_title_on     = learts_get_option( 'page_title_on' );

	$container_class = array( 'main-container' );

	if ( $remove_whitespace && ! $page_title_on ) {
		$container_class[] = 'no-whitespace';
	}

	?>

	<div class="<?php echo implode( ' ', $container_class ); ?>">

		<?php
		do_action( 'learts_main_container_top' );
		echo Learts_Templates::page_title();
		?>
