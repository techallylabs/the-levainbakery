window.mooPopUp = {

    init: function() {

    },
    open: function( title, content ) {

        var header = '<div class="mooModalHeader">';
        header += '<div><h4>'+ title +'</h4></div>';
        header += '<span class="icon-close" onclick="window.mooPopUp.close()">X</span>';
        header += '</div>';

        modal = '<div id="mooModalWindow">';
        modal += '<div id="mooModal">';
        modal += header;
        modal += '<div class="mooModalContent">';
        modal += '<div>' + content + '</div>';
        modal += '</div>';
        modal += '</div>';
        modal += '</div>';

        //Before opening a new model, close the previous modal
        this.close();
        //Open the modal
        jQuery('body').addClass('mooModalBlurBackground');
        jQuery('body').append(modal);
    },
    close: function() {
        jQuery('#mooModalWindow').remove();
        jQuery('body').removeClass('mooModalBlurBackground');
    }
}