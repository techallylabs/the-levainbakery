'use strict';

jQuery( document ).ready( function ( $ ) {

    $( '#insight-core-sidebars-table' ).on( 'click', '.insight-core-remove-sidebar', function () {

        var row = $( this ).parents( 'tr' ).first();
        var sidebar_name = row.find( 'td' ).eq( 0 ).text();
        var sidebar_class = row.find( 'td' ).eq( 1 ).text();
        var answer = confirm( "Are you sure you want to remove \"" + sidebar_name + "\" ?\nThis will remove any widgets you have assigned to this sidebar." );

        if ( answer ) {

            var data = {
                action: 'remove_sidebar',
                sidebar_class: sidebar_class
            };

            $.ajax( {
                type: 'POST',
                url: ajaxurl,
                data: data,
                success: function ( data ) {

                    if ( data.status ) {
                        row.remove();
                    } else {
                        alert( data.messages );
                    }
                }
            } );
        } else {
            return false;
        }
    } );

    $( '#insight-core-sidebars-form' ).on( 'submit', function ( e ) {

        e.preventDefault();

        var sidebar_name_input = $( '#sidebar_name' );
        var sidebar_name = sidebar_name_input.val();

        var data = {
            action: 'add_sidebar',
            sidebar_name: sidebar_name
        };

        $.ajax( {
            type: 'POST',
            url: ajaxurl,
            data: data,
            success: function ( data ) {

                if ( data.status ) {
                    $( '#insight-core-sidebars-table' ).append( '<tr><td>' + sidebar_name + '</td><td>' + data.messages + '</td><td><a href="javascript:void(0);" class="insight-core-remove-sidebar"><i class="dashicons dashicons-trash"></i>Remove</a></td></tr>' );
                    sidebar_name_input.val( '' );
                } else {
                    alert( data.messages );
                }
            }
        } );
    } );

} );