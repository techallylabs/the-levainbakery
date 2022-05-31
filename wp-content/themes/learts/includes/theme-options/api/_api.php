<?php

Redux::setSection( learts_Redux::$opt_name,

	array(
		'title'  => esc_html__( 'API Integrations', 'learts' ),
		'id'     => 'panel_api',
		'icon'   => 'fa fa-cogs',
		'fields' => array(

			array(
				'id'       => 'mailchimp_api_key',
				'type'     => 'text',
				'title'    => esc_html__( 'MailChimp API Key', 'learts' ),
				'subtitle' => wp_kses( sprintf( __( 'The API key for connecting with your MailChimp account. <a href="%s" target="_blank">Get your API key here</a>.',
					'learts' ),
					'https://admin.mailchimp.com/account/api' ),
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
						),
					) ),
			),

			array(
				'id'       => 'gmaps_api_key',
				'type'     => 'text',
				'title'    => esc_html__( 'Google Maps API Key', 'learts' ),
				'subtitle' => sprintf( wp_kses( __( 'Follow <a href="%s" target="_blank">this link</a> and click <strong>GET A KEY</strong> button.', 'learts' ),
					array(
						'a'      => array(
							'href'   => array(),
							'target' => array(),
						),
						'strong' => array()
					) ), esc_url( 'https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key' )
				),
				'default'  => 'AIzaSyAtDP8prBDw5K5nKJqHImggyL9UCq8m8zo',
			),
		),
	) );
