jQuery( function( $ ) {
    'use strict';
    window.mooGlobalParams = {
        "allowScOrders" : false, // Allow Schedule orders
        "isDeliveryOrder" : false, // Is a delivery Order
        "doNotVerifyPhone" : false, // Do not verify customer phone
        "selectedOrderTypeIsTaxable" : true,
    };
    window.mooCheckout = {
        /**
         * Initialize event handlers and UI state.
         */
        init: function() {

            $('.moo-checkout-form-ordertypes-input').on('click', this.orderTypeChanged);

            $('.moo-checkout-form-payments-input').on('click', this.paymentMethodChanged);
        },
        /**
         * Check whether a customer is logged.
         *
         * @return {boolean}
         */
        isCustomerLoggedIn() {
            return (MooCustomer !== null && MooCustomer[0] !== null);
        },
        /**
         * Check whether a customer is logged.
         *
         * @return {boolean}
         */
        getLoggedInCustomer() {
            if(MooCustomer !== null && MooCustomer[0] !== null) {
                return MooCustomer[0];
            }
            return null;
        },
        /**
         * Check whether a mobile device is being used.
         *
         * @return {boolean}
         */
        isMobile: function() {
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test( navigator.userAgent ) ) {
                return true;
            }

            return false;
        },

        /**
         * Returns the selected payment method HTML element.
         *
         * @return {string|null}
         */
        getSelectedPaymentMethod: function() {
            var elm = $( '#moo-checkout-form-payments input[name="payments"]:checked' );
            if(elm.length > 0){
                return elm.val();
            }
            return null;
        },
        /**
         * Returns the selected ordering method HTML element.
         *
         * @return {string|null}
         */
        getSelectedOrderingMethod: function() {
            var elm = $( '#moo-checkout-form-ordertypes input[name="ordertype"]:checked' );
            if(elm.length){
                return elm.val();
            }
            return null;
        },
        /**
         * Returns the customer data.
         *
         * @return {Object}
         */
        getCustomer: function() {
            var customer = {};
            if( this.isCustomerLoggedIn() ) {
                var loggedInCustomer = this.getLoggedInCustomer();
                customer.id    =  loggedInCustomer.id;
                customer.uuid  =  loggedInCustomer.uuid;
                customer.name    = loggedInCustomer.fullname;
                customer.email   =  loggedInCustomer.email;
                customer.phone   =  loggedInCustomer.phone;
            } else {
                customer.id    =  null;
                customer.uuid  =  null;
                customer.name  =  $('#MooContactName').val().trim();
                customer.email =  $('#MooContactEmail').val().trim();
                customer.phone =  $('#MooContactPhone').val().trim();
            }
            customer.phone_verified = MooPhoneIsVerified;
            customer.address = this.getSelectedAddress();
            customer.full_address = this.getSelectedAddressAsString();
            return customer;
        },
        /**
         * Returns the tip amount
         *
         * @return integer
         */
        getTipAmount: function() {
            var tip = $('#moo_tips').val();
            tip = parseFloat(tip);
            if(tip > 0){
                tip = tip * 100;
                return Math.round(parseInt(tip));
            } else {
                return 0;
            }
        },
        /**
         * Returns the selected Address.
         *
         * @return {Object}
         */
        getSelectedAddress: function() {
            return MooCustomerChoosenAddress;
           // return JSON.parse(localStorage.getItem("selectedAddress"));
        },
        /**
         * Returns the selected Address as a text.
         *
         * @return {Object}
         */
        getSelectedAddressAsString: function() {
            var address_string = "";
            if(MooCustomerChoosenAddress){
                if(MooCustomerChoosenAddress.address !== '')
                    address_string += MooCustomerChoosenAddress.address+' ';
                if(MooCustomerChoosenAddress.line2 !== '')
                    address_string += MooCustomerChoosenAddress.line2+' ';
                if(MooCustomerChoosenAddress.city !== '')
                    address_string += MooCustomerChoosenAddress.city+', ';
                if(MooCustomerChoosenAddress.state !== '')
                    address_string += MooCustomerChoosenAddress.state+' ';
                if(MooCustomerChoosenAddress.zipcode !== '')
                    address_string += MooCustomerChoosenAddress.zipcode;
            }
            return address_string;
           // return JSON.parse(localStorage.getItem("selectedAddress"));
        },
        /**
         * Returns the selected Address.
         *
         * @return {Object}
         */
        setAddress: function(address) {
            localStorage.setItem("selectedAddress",JSON.stringify(address));
        },
        /**
         * Check if the merchant enabled the phone verification.
         *
         * @return {boolean}
         */
        isPhoneVerificationActivated: function() {
            if(typeof mooCheckoutOptions.moo_use_sms_verification !== 'undefined' && mooCheckoutOptions.moo_use_sms_verification === 'disabled') {
                return false;
            }
            return true;
        },
        /**
         * Check if the merchant enabled the Login&register feature.
         *
         * @return {boolean}
         */
        isLoginFeatureEnabled: function() {
            if(typeof mooCheckoutOptions.moo_checkout_login !== undefined) {
                return (mooCheckoutOptions.moo_checkout_login === "disabled");
            }
            return true;
        },
        /**
         * Check if the merchant enabled the Facebook Login&register feature.
         *
         * @return {boolean}
         */
        isFacebookLoginFeatureEnabled: function() {
            if(typeof mooCheckoutOptions.moo_fb_app_id !== undefined && mooCheckoutOptions.moo_fb_app_id !== null) {
                return (mooCheckoutOptions.moo_fb_app_id !== "");
            }
            return false;
        },
        /**
         * Init facebook SDK.
         *
         * @return {boolean}
         */
        initFacebookSdk: function() {
            if(this.isFacebookLoginFeatureEnabled()){
                window.fbAsyncInit = function() {
                    FB.init({
                        appId      : mooCheckoutOptions.moo_fb_app_id,
                        xfbml      : true,
                        version    : 'v2.8'
                    });
                    FB.AppEvents.logPageView();
                };

                (function(d, s, id){
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) {return;}
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/en_US/sdk.js";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));
            }
        },
        /**
         * Event when orderType changed.
         */
        orderTypeChanged: function() {
            var selectedOrderType = mooCheckout.getSelectedOrderingMethod();
            var orderType = mooCheckout.getOneOrderType(selectedOrderType);

            if( orderType ){
                //Check if the orderType allow Schedule Orders
                if(orderType.allow_sc_order === false || orderType.allow_sc_order === "0" || orderType.allow_sc_order === 0) {
                    mooCheckout.hideOrderingTime();
                } else {
                    mooCheckout.showOrderingTime();
                }
                //Check if the orderType is a Delivery Type
                if(orderType.show_sa === false || orderType.show_sa === "0" || orderType.show_sa === 0) {
                    mooGlobalParams.isDeliveryOrder = false;
                } else {
                    mooCheckout.showOrderingTime();
                }

                //Check taxes

                //Check minAmount

                //Check if the orderType allow Using coupons

                //Re-calculate the total
            } else {
                console.log("No OrderType found")
            }
        },
        /**
         * Event when orderType changed.
         */
        paymentMethodChanged: function() {
            var selectedPaymentMethod = mooCheckout.getSelectedPaymentMethod();

            if(selectedPaymentMethod){
                console.log(selectedPaymentMethod);
            }
        },
        /**
         * Get Details of One Order Type.
         */
        getOneOrderType: function(orderTypeUuid) {
            if(typeof mooCheckoutOptions.moo_OrderTypes !== 'undefined') {
                for(var i in mooCheckoutOptions.moo_OrderTypes) {
                    if(orderTypeUuid === mooCheckoutOptions.moo_OrderTypes[i].ot_uuid) {
                        return  mooCheckoutOptions.moo_OrderTypes[i];
                    }
                }
            }
            return null;
        },
        /**
         * Change the visibility of a an Html Element. (Show/hide)
         */
        changeElemVisibility: function(selector,showOrHide) {
            if(showOrHide === "show"){
                $(selector).show();
            }
            if(showOrHide === "hide"){
                $(selector).hide();
            }
        },
        /**
         * Hide the ordering Time
         */
        hideOrderingTime: function() {
            this.changeElemVisibility("#moo-checkout-form-orderdate","hide");
            mooGlobalParams.allowScOrders = false;
        },
        /**
         * Show the ordering Time
         */
        showOrderingTime: function() {
            this.changeElemVisibility("#moo-checkout-form-orderdate","show");
            mooGlobalParams.allowScOrders = true;

        },
        /**
         * Verify Checkout Form
         */
        verifyCheckoutForm: function(form) {
            var regex_exp      = {
                email :  /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                credicard : /^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|(222[1-9]|22[3-9][0-9]|2[3-6][0-9]{2}|27[01][0-9]|2720)[0-9]{12}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11}|62[0-9]{14})$/,
                cvv : /^[0-9]*$/
            };
            var message_errors = {};
            var selectedOrderTypeUuid = mooCheckout.getSelectedOrderingMethod();
            var selectedOrderType = mooCheckout.getOneOrderType(selectedOrderTypeUuid);

            //Check the customer information
            if(form.customer.name === "") {
                mooCheckout.showErrorAlert(
                    'Please enter your name',
                    '',
                    '#MooContactName'
                );
                return false;
            }
            if(form.customer.email === "" || !regex_exp.email.test(form.customer.email)) {
                mooCheckout.showErrorAlert(
                    'Please enter a valid email',
                    'We need a valid email to contact you and send to you the receipt',
                    '#MooContactEmail'
                );
                return false;
            }
            if(form.customer.phone === "") {
                mooCheckout.showErrorAlert(
                    'Please enter your phone',
                    'We need your phone to contact you if we have any question about your order',
                    '#MooContactPhone'
                );
                return false;
            }
            if(document.getElementById('moo-checkout-form-ordertypes')) {
                if((typeof form.order_type === 'undefined') || form.order_type === "") {
                    mooCheckout.showErrorAlert(
                        'Please choose the ordering method',
                        'How you want your order to be served ?',
                        '#moo-checkout-form-ordertypes'
                    );
                    return false;
                }
            }
            //Check the delivery address and min amount per Order Type
            if((typeof selectedOrderType === 'object') && selectedOrderType !== null) {

                var minAmount = parseFloat(selectedOrderType.minAmount);
                var maxAmount = parseFloat(selectedOrderType.maxAmount);

                if(isNaN(minAmount)){
                    minAmount = 0;
                } else {
                    minAmount = minAmount*100;
                    minAmount = Math.round(minAmount * 100 ) / 100;
                }

                if(isNaN(maxAmount)){
                    maxAmount = null;
                } else {
                    maxAmount = maxAmount*100;
                    maxAmount = Math.round(maxAmount * 100 ) / 100;
                }

                if(minAmount > 0) {
                    if(minAmount > mooCheckoutOptions.totals.sub_total) {
                        mooStopLoading();
                        swal({
                                title: 'You did not meet the minimum purchase requirement',
                                text:"this ordering method requires a subtotal greater than $"+mooformatCentPrice(minAmount),
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Continue shopping",
                                cancelButtonText: "Checkout",
                                closeOnConfirm: false },
                            function(){ window.history.back() });

                        return false;
                    }
                }
                if( maxAmount ) {
                    if(maxAmount < mooCheckoutOptions.totals.sub_total) {
                        mooStopLoading();
                        swal({
                            title: 'You reached the maximum purchase amount',
                            text:"this ordering method requires a subtotal less than $"+mooformatCentPrice(maxAmount),
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Update cart",
                            cancelButtonText: "Checkout",
                            closeOnConfirm: false
                        }).then(function (data) {
                            if(data.value) {
                                swal.close();
                                window.location.href = moo_params.cartPage;
                            }
                        });
                        return false;}

                }

                //Check the address if the ordering methid is delivery
                if(selectedOrderType.show_sa === '1' || selectedOrderType.show_sa ===  1 || selectedOrderType.show_sa === true ) {
                    var ChoosenAddress = this.getSelectedAddress();
                    if(typeof ChoosenAddress !== 'undefined' && ChoosenAddress !== null) {
                        if(ChoosenAddress.lat === '' || ChoosenAddress.lng === '') {
                            mooCheckout.showErrorAlert(
                                'Please verify your address',
                                "We can't found this address on the map, please choose an other address",
                                '#moo-checkout-form-ordertypes>.moo-checkout-bloc-message'
                            );
                            return false;
                        } else {
                            if(MooIsDeliveryError === true) {
                               // moo_OrderTypeChanged(selectedOrderType.ot_uuid);
                                mooCheckout.showErrorAlert(
                                    'Please verify your address',
                                    '',
                                    '#moo-checkout-form-ordertypes>.moo-checkout-bloc-message'
                                );
                                return false;
                            }
                        }
                    } else {
                       // moo_OrderTypeChanged(selectedOrderType.ot_uuid);
                        mooCheckout.showErrorAlert(
                            'Please add the delivery address',
                            'You have chosen a delivery method, we need your address',
                            '#moo-checkout-form-ordertypes>.moo-checkout-bloc-message .MooSimplButon'
                        );
                        return false;
                    }
                }
                //Check Coupons : TODO
            }
            //check the Schedule time
            if(mooGlobalParams.allowScOrders && (form.pickup_hour === null ||form.pickup_hour === "Select a time"  ||  form.pickup_hour === "" )) {
                mooCheckout.showErrorAlert(
                    'Please choose a time',
                    '',
                    '#moo-checkout-form-orderdate'
                );
                return false;
            }
            //check special instructions when they are required special_instructions_required
            if( ! (typeof mooCheckoutOptions.special_instructions_required === 'undefined') ) {
                if(mooCheckoutOptions.special_instructions_required === 'yes') {
                    if(typeof form.special_instructions === 'undefined' || form.special_instructions === '' ) {
                        mooCheckout.showErrorAlert(
                            'Special instructions are required',
                            '',
                            '#moo-checkout-form-instruction'
                        );
                        return false;
                    }
                }
            }


            //check the payment info with the phone verification
            if(typeof form.payment_method === 'undefined' || form.payment_method === '' ) {
                mooCheckout.showErrorAlert(
                    'Please choose your payment method',
                    '',
                    '#moo-checkout-form-payments'
                );
                return false;
            } else {
                if(form.payment_method === "cash") {
                    //Check the phone verification
                    if(! mooGlobalParams.doNotVerifyPhone ){
                        if(
                            form.customer.phone_verified === false ||
                            form.customer.phone_verified === "0" ||
                            form.customer.phone_verified === 0
                        ){
                            mooCheckout.showErrorAlert(
                                'Please verify your phone',
                                'When you choose the cash payment you must verify your phone',
                                '#moo-checkout-form-payments'
                            );
                            /*
                             var paymentType = jQuery('input[name="payments"]:checked').val();
                            if(paymentType != '') {
                                moo_changePaymentMethod(paymentType);
                            }
                             */
                            return false;
                        }
                    }
                    this.submitForm(form);
                }
                if(form.payment_method === "clover") {
                    if(window.cloverCardIsValid){
                        window.clover.createToken()
                            .then( function (response) {
                                if(response.token){
                                    form.token = response.token;
                                    form.card = response.card;
                                    window.mooCheckout.submitForm(form);
                                } else {
                                    mooCheckout.showErrorAlert(
                                        'Please verify your card information',
                                        '',
                                        '#moo-checkout-form-payments'
                                    );
                                    return false;
                                }
                            });

                    } else {
                        mooCheckout.showErrorAlert(
                            'Please verify your card information',
                            window.cloverCardErrorMsg,
                            '#moo-checkout-form-payments'
                        );
                        return false;
                    }
                }
                if(form.payment_method === "creditcard") {
                    if(typeof form.card.cardNumber === 'undefined' || form.card.cardNumber === '' || !regex_exp.credicard.test(form.card.cardNumber) ) {
                        mooCheckout.showErrorAlert(
                            'Please enter a valid credit card number',
                            '',
                            '#Moo_cardNumber'
                        );
                        return false;
                    }
                    if(form.card.cvv  === ''  ) {
                        mooCheckout.showErrorAlert(
                            'Please enter a valid Card CVV',
                            '',
                            '#moo_cardcvv'
                        );
                        return false;
                    }
                    if(form.card.zipcode  === ''  ){
                        mooCheckout.showErrorAlert(
                            'Please enter a valid Zip Code',
                            '',
                            '#moo_zipcode'
                        );
                        return false;
                    }
                    form.card.cardNumber = form.card.cardNumber.replace(/\s/g, '');
                    form.card.cardNumber = form.card.cardNumber.replace(/-/g, '');
                    form.card.cardEncrypted = this.getEncryptedCardNumber(form.card.cardNumber);
                    form.card.first6 = this.getFirstSix(form.card.cardNumber);
                    form.card.last4 = this.getLastFour(form.card.cardNumber);
                    form.card.cardNumber = null;
                    this.submitForm(form);
                }
            }
        },
        /**
         * Verify Checkout Form
         */
        getCheckoutForm: function() {
            var form = {
                "channel":"website",
                "customer":{
                    "id":null,
                    "uuid":null,
                    "name":"",
                    "first_name":"",
                    "last_name":"",
                    "email":"",
                    "phone":"",
                    "address":{},
                    "phone_verified" : MooPhoneIsVerified
                },
                "card":{},
                "metainfo" : [{ "name":"source_url", "value":window.location.href}]
            };
            form.customer =  mooCheckout.getCustomer();
            form.card.cardNumber             =  $('#Moo_cardNumber').val();
            form.card.expMonth          =  $('#MooexpiredDateMonth').val();
            form.card.expYear           =  $('#MooexpiredDateYear').val();
            form.card.cvv               =  $('#moo_cardcvv').val();
            form.card.last4             =  null;
            form.card.first6            =  null;
            form.card.cardEncrypted     =  null;
            form.card.zip               =  $('#moo_zipcode').val();
            form.tip_amount             = mooCheckout.getTipAmount();
            form.special_instructions   =  $('#Mooinstructions').val();
            form.pickup_day             =  $('#moo_pickup_day').val();
            form.pickup_hour            =  $('#moo_pickup_hour').val();

            if(document.getElementById('moo-checkout-form-ordertypes')) {
                form.order_type  =  $('input[name="ordertype"]:checked').val();
            }

            form.payment_method  =  $('input[name="payments"]:checked').val();
            form.delivery_amount = mooCheckout.getDeliveryFees();
            form.service_fee = mooCheckout.getServiceFees();
            if(form.cardNumber !== undefined ){
                form.cardNumber = form.cardNumber.trim();
                form.cardNumber = form.cardNumber.replace(/\s+/g,"");
            }
            this.verifyCheckoutForm(form);
        },
        /**
         * Submit The Checkout Form
         */
        submitForm: function( form ) {
            $.ajax({
                type: 'POST',
                cache:false,
                url: mooCheckoutOptions.moo_RestUrl+"moo-clover/v2/checkout",
                contentType: 'application/json; charset=UTF-8',
                data: JSON.stringify(form)
                }).done(function (data) {
                    if(typeof data == 'object') {
                        if(data.status === 'success') {
                            moo_order_approved(data.id);
                        } else {
                            if (data.message === "You cannot use a clover token more than once unless it is marked as multipay."){
                                if(window.mooCheckout){
                                    window.mooCheckout.getCheckoutForm();
                                }
                            } else {
                                moo_order_notApproved(data.message);
                            }

                        }
                    } else {
                        if(data.indexOf('"status":"success"') != -1 ) {
                            moo_order_approved('');
                        } else {
                            moo_order_notApproved('');
                        }
                    }
                }).fail(function(data) {
                    console.log('FAIL');
                    console.log(data.responseText);
                    if(data.responseText.indexOf('"status":"success"') !== -1 ) {
                        moo_order_approved('');
                    } else {
                        moo_order_notApproved('');
                    }
                });
        },
        /**
         * Get the Selected Address By The Customer
         */
        getCustomerSelectedAddress: function() {
            return  {};
        },
        /**
         * Get DeliverFees
         */
        getDeliveryFees: function() {
            var fee = parseFloat(MooDeliveryfees);
            if(isNaN(fee)){
                return 0;
            }
            return fee*100;
        },
        /**
         * Get Service fees
         */
        getServiceFees: function() {
            return mooCheckoutOptions.totals.service_fee;
        },
        /**
         * Encrypt The Card Number
         *
         */
        getEncryptedCardNumber: function(ccn) {
            var rsa = forge.pki.rsa;

            var modulus = mooCheckoutOptions.moo_Key.modulus;
            var exponent = mooCheckoutOptions.moo_Key.exponent;
            var prefix = mooCheckoutOptions.moo_Key.prefix;
            var text = prefix + ccn;
            modulus = new forge.jsbn.BigInteger(modulus);
            exponent = new forge.jsbn.BigInteger(exponent);
            text = text.split(' ').join('');
            var publicKey = rsa.setPublicKey(modulus, exponent);
            var encryptedData = publicKey.encrypt(text, 'RSA-OAEP');
            return forge.util.encode64(encryptedData);
        },
        /**
         * get First Six
         */
        getFirstSix: function(ccn) {
            var cardNumber = ccn.split(' ').join('').trim();
            return cardNumber.substr(0,6);
        },
        /**
         * get Last Four
         */
        getLastFour: function(ccn) {
            var cardNumber = ccn.split(' ').join('').trim();
            return cardNumber.substr(-4);
        },
        /**
         * Calculate_delivery_fee
         * @param customer_lat
         * @param customer_lng
         * @returns {*}
         */
        calculateDeliveryFees: function(customer_lat,customer_lng) {

            var order_total             = parseFloat(mooCheckoutOptions.totals.sub_total);
            var delivery_free_after     = parseFloat(mooDeliveryOptions.free_amount)  ; //Free delivery after this amount
            var delivery_fixed_amount   = parseFloat(mooDeliveryOptions.fixed_amount) ; //Fixed delivery amount
            var delivery_for_other_zone = parseFloat(mooDeliveryOptions.other_zone_fee) ; //Amount of delivery for other zones
            var moo_delivery_areas = null;

            try {
                moo_delivery_areas  = JSON.parse(mooDeliveryOptions.zones);
            } catch (e) {
                console.log("Parsing error: moo_delivery_areas");
            }

            //first of all we will check :
            // if the merchant offer fixed amount
            // else we will check the zones
            if(isNaN(delivery_fixed_amount)) {
                if(customer_lat !== '' && customer_lng !== '') {
                    //check the zones
                    var zones_contain_point = new Array();
                    for(i in moo_delivery_areas)  {
                        var el = moo_delivery_areas[i];

                        // Verify if the selected address is at any zone
                        if(el.type === 'polygon') {
                            if(google.maps.geometry.poly.containsLocation( new google.maps.LatLng(parseFloat(customer_lat),parseFloat(customer_lng)), new google.maps.Polygon({paths:el.path}))) {
                                zones_contain_point.push({
                                    zone_id:el.id,
                                    zone_fee:el.fee,
                                    feeType:el.feeType
                                });
                            }
                        } else {
                            if(el.type === 'circle') {
                                var point  = new google.maps.LatLng(parseFloat(customer_lat),parseFloat(customer_lng));
                                var center = new google.maps.LatLng(parseFloat(el.center.lat),parseFloat(el.center.lng));
                                if(google.maps.geometry.spherical.computeDistanceBetween(point, center) <= el.radius) {
                                    zones_contain_point.push({
                                        zone_id:el.id,
                                        zone_fee:el.fee,
                                        feeType:el.feeType
                                    });
                                }
                            }
                        }
                    }
                    // If the selected point on the map exists in at least one merchant's zone
                    // Then we we update the delivery amount by this zone fees
                    // else we verify if the merchant allow other zones
                    if(zones_contain_point.length >= 1 ) {

                        // Customer address exist in at least one merchant's zone
                        var delivery_final_amount = (zones_contain_point[0].feeType === "percent")?(zones_contain_point[0].zone_fee*order_total/100):zones_contain_point[0].zone_fee;
                        var delivery_zone_id      =  zones_contain_point[0].zone_id;

                        for (i in zones_contain_point) {

                            if(zones_contain_point[i].feeType === "percent") {
                                var amount = (zones_contain_point[i].zone_fee * order_total )/100;
                                if(parseFloat(delivery_final_amount) >= parseFloat(amount))
                                {
                                    delivery_final_amount = parseFloat(amount).toFixed(2);
                                    delivery_zone_id = zones_contain_point[i].zone_id;
                                }
                            }
                            else
                            if(parseFloat(delivery_final_amount) >= parseFloat(zones_contain_point[i].zone_fee))
                            {
                                delivery_final_amount = zones_contain_point[i].zone_fee;
                                delivery_zone_id = zones_contain_point[i].zone_id;
                            }
                        }

                        if(isNaN(delivery_free_after)) {
                            //Verify the min amount
                            for(var i in moo_delivery_areas) {
                                var el = moo_delivery_areas[i];
                                if(delivery_zone_id === el.id) {
                                    var deliveryMinAmount = parseFloat(el.minAmount);
                                    if( !isNaN(deliveryMinAmount) && (parseFloat(el.minAmount) * 100 ) > mooCheckoutOptions.totals.sub_total ) {
                                        var res ={};
                                        res.type='min_error';
                                        res.amount='';
                                        res.message="The minimum order total for this selected zone is $"+parseFloat(el.minAmount).toFixed(2);
                                        return red;
                                    } else {
                                        var res ={};
                                        res.type='success';
                                        res.amount=delivery_final_amount;
                                        res.zoneName=el.name;
                                        return res;
                                    }
                                }
                            }

                        } else {
                            var amountToAdd = delivery_free_after-order_total;
                            if(amountToAdd <= 0){
                                var res ={};
                                res.type='free';
                                res.amount=0;
                                return res;
                            } else {
                                swal({
                                        title: 'Spend $'+delivery_free_after.toFixed(2)+" to get free delivery",
                                        text:'Add $'+(amountToAdd.toFixed(2))+' to your order to enjoy free delivery',
                                        type: "warning",
                                        showCancelButton: true,
                                        confirmButtonColor: "#DD6B55",
                                        confirmButtonText: "Continue shopping",
                                        cancelButtonText: "Checkout",
                                        closeOnConfirm: false
                                    },function(){ window.history.back() }
                                );

                                if(amountToAdd > 0 && amountToAdd < delivery_final_amount)
                                    delivery_final_amount = amountToAdd;
                                //Verify the min amount
                                for(i in moo_delivery_areas)
                                {
                                    var el = moo_delivery_areas[i];
                                    if(delivery_zone_id === el.id) {
                                        var deliveryMinAmount = parseFloat(el.minAmount);
                                        if( !isNaN(deliveryMinAmount) && (parseFloat(el.minAmount) * 100 ) > mooCheckoutOptions.totals.sub_total ) {
                                            var res ={};
                                            res.type='min_error';
                                            res.amount='';
                                            res.message="The minimum order total for this selected zone is $"+parseFloat(el.minAmount).toFixed(2);
                                            return res;
                                        } else {
                                            var res ={};
                                            res.type='success';
                                            res.amount=delivery_final_amount;
                                            res.zoneName=el.name;
                                            return res;
                                        }
                                    }
                                }
                            }
                        }

                    } else {
                        //Customer address not exist in any zone
                        /*
                            we will check the support other zones
                         */
                        if(isNaN(delivery_for_other_zone)) {
                            var res ={};
                            res.type='zone_error';
                            res.amount='';
                            return res;

                        } else {
                            var res ={};
                            res.type='other_zone';
                            res.amount=delivery_for_other_zone.toFixed(2);
                            return res;
                        }

                    }
                } else  {
                    console.log("Customer Address not found");
                    var res ={};
                    res.type='zone_error';
                    res.amount='';
                    return res;
                }
            } else {
                var res ={};
                res.type='fixed';
                res.amount=delivery_fixed_amount.toFixed(2);
                return res;
            }
        },

        /**
         * Update Delivery section by adding information about the price
         * @param result
         */
        updateDeliverySection : function (result) {
            if(typeof mooDeliveryOptions.errorMsg === "undefined"){
                var errorText = 'Sorry, zone not supported. We do not deliver to this address at this time';

            } else {
                var errorText = mooDeliveryOptions.errorMsg;

            }
            var html='<strong>Delivery amount :</strong><br/>';
            switch(result.type) {
                case 'other_zone':
                    html+= '$'+result.amount;
                    MooDeliveryfees = result.amount;
                    MooIsDeliveryError = false;
                    break;
                case 'zone_error':
                    html = errorText;
                    swal(errorText,"","error");
                    MooDeliveryfees = false;
                    MooIsDeliveryError = true;
                    break;
                case 'min_error':
                    html = result.message;
                    swal(result.message,"","error");
                    MooDeliveryfees = false;
                    MooIsDeliveryError = true;
                    break;
                case 'success':
                    html += '$'+result.amount;
                    MooDeliveryfees =result.amount;
                    MooIsDeliveryError = false;
                    break;
                case 'free':
                    html += 'Free';
                    MooDeliveryfees = 0.00;
                    MooIsDeliveryError = false;
                    break;
                case 'fixed':
                    html += '$'+result.amount;
                    MooDeliveryfees = result.amount;
                    MooIsDeliveryError = false;
                    break;
            }
            $('#mooDeliveryAmountInformation').html(html);
        },
        /**
         * Format the price from cents
         * @param priceInCentes
         * @returns {string}
         */
        formatPrice : function (priceInCentes) {
            var p = priceInCentes/100;
            p = p.toFixed(2);
            return p.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
        },

        /**
         * Update Totals section
         */
        updateTotals: function (forceUpdate = false){
            var totals = mooCheckoutOptions.totals;
            if(totals === false ){
                console.log("Nothing to update in totals section");
                return;
            }

            var delivery_amount = this.getDeliveryFees();

            if(forceUpdate || (delivery_amount > 0 && delivery_amount !== totals.delivery_charges)){
                this.startLoadingDeliveryFee();
                this.getOrderTotals(delivery_amount,totals.service_fee, this.updateTotalsSection );
            } else {
                this.updateTotalsSection(totals);
            }
        },

        /**
         *
         * @param totals
         */
        updateTotalsSection: function (totals){
            var grand_total = 0;
            var tips_amount = mooCheckout.getTipAmount();
            var delivery_amount = mooCheckout.getDeliveryFees();

            //Remove the css loading class
            mooCheckout.stopLoadingDeliveryFee();

            //Calculate the new Total
            if( mooGlobalParams.selectedOrderTypeIsTaxable ) {
                grand_total = totals.total + tips_amount + delivery_amount + totals.service_fee;
            } else {
                grand_total = totals.sub_total + tips_amount + delivery_amount + totals.service_fee;
            }

            if(totals.coupon_value === 0) {
                //Hide the coupon section in total
                $('#MooCouponInTotalsSection').hide();
            } else {
                //Show coupon section in total
                $('#MooCouponInTotalsSection').show();
                $('#mooCouponName').html(totals.coupon_name);
                $('#mooCouponValue').html("- $"+mooCheckout.formatPrice(totals.coupon_value));

                //update the total when the order is not taxable
                if(! mooGlobalParams.selectedOrderTypeIsTaxable ) {
                    grand_total = totals.sub_total + tips_amount + delivery_amount + totals.service_fee - totals.coupon_value;
                }
            }

            $('.moo-totals-value').fadeOut(300, function() {
                $('#moo-cart-subtotal').html('$'+mooCheckout.formatPrice(totals.sub_total));

                if( mooGlobalParams.selectedOrderTypeIsTaxable ) {
                    if(totals.coupon_value === 0) {
                        $('#moo-cart-tax').html('$'+mooCheckout.formatPrice(totals.total_of_taxes_without_discounts));
                    } else {
                        $('#moo-cart-tax').html('$'+mooCheckout.formatPrice(totals.total_of_taxes));
                    }

                } else {
                    $('#moo-cart-tax').html("0.00");
                }

                $('#moo-cart-tip').html('$'+mooCheckout.formatPrice(tips_amount));
                $('#moo-cart-delivery-fee').html('$'+mooCheckout.formatPrice(delivery_amount));

                if( totals.service_fee > 0 ) {
                    $('#moo-cart-service-fee').html('$'+mooCheckout.formatPrice( totals.service_fee));
                    $('#MooServiceChargesInTotalsSection').show();
                } else {
                    $('#MooServiceChargesInTotalsSection').hide();
                }
                $('#moo-cart-total').html('$'+mooCheckout.formatPrice(grand_total));
                $('.moo-totals-value').fadeIn(300);
            });
        },

        /**
         * Get Totals from the server
         */
         getOrderTotals: function ( delivery_amount, service_fee, callback ){
            $.ajax({
                type: 'POST',
                cache:false,
                url: mooCheckoutOptions.moo_RestUrl+"moo-clover/v2/checkout/order_totals",
                contentType: 'application/json; charset=UTF-8',
                data: JSON.stringify({
                    "service_fee":service_fee,
                    "delivery_amount":delivery_amount,
                })
            }).done(function (data) {
                mooCheckoutOptions.totals = data;
                callback(data);
            }).fail(function(data) {
                console.log('FAIL');
                console.log(data.responseText);
            });
        },

    /**
         * showErrorAlert
         */
        showErrorAlert: function(title,message, focusOnElem = null) {
            this.stopLoading();
            if(focusOnElem){
                swal(title,message,'error').then(function() {
                    setTimeout(function () {
                        $(focusOnElem).focus();
                    },500)
                });
            } else {
                swal(title,message,'error');
            }
        },
        /**
         * stopLoading
         */
        stopLoading: function() {
            $('#moo_checkout_loading').hide();
            $('#moo_btn_submit_order').show();
        },
        /**
         * startLoadingDeliveryFee
         */
        startLoadingDeliveryFee: function() {
            $('#MooDeliveryfeesInTotalsSection').addClass("moo-fade-in");
            $('.moo-totals-item-total').addClass("moo-fade-in");
        },
        /**
         * stopLoadingDeliveryFee
         */
        stopLoadingDeliveryFee: function() {
            $('#MooDeliveryfeesInTotalsSection').removeClass("moo-fade-in");
            $('.moo-totals-item-total').removeClass("moo-fade-in");
        },

    };


    if(typeof mooCheckoutOptions.moo_clover_payment_form !== 'undefined' &&  typeof mooCheckoutOptions.moo_clover_key !== 'undefined' && mooCheckoutOptions.moo_clover_payment_form === "on" ) {
        try {
            window.clover = new Clover( mooCheckoutOptions.moo_clover_key );
            var elements = window.clover.elements();
            window.cloverCardIsValid = false;
            window.cloverCardErrorMsg = "";

        } catch( error ) {
            console.log( error );
            return;
        }

        window.moo_clover_gateway = {
            /**
             * Mounts all elements to their DOM nodes on initial loads and updates.
             */
            mountElements: function() {
                if ( ! $( '#moo_CloverCardNumber' ).length ) {
                    return;
                }
                window.clover_card.mount( '#moo_CloverCardNumber' );
                window.clover_exp.mount( '#moo_CloverCardDate' );
                window.clover_cvc.mount( '#moo_CloverCardCvv' );
                window.clover_zip.mount( '#moo_CloverCardZip' );
            },

            /**
             * Creates all Clover elements
             */
            createElements: function() {
                var elementStyles = {
                    input: {
                        color: '#31325F',
                        height: '30px',
                        '::placeholder': {
                            color: '#CFD7E0',
                        },
                    }
                };

                window.clover_card = elements.create( 'CARD_NUMBER', elementStyles );
                window.clover_exp  = elements.create( 'CARD_DATE', elementStyles );
                window.clover_cvc  = elements.create( 'CARD_CVV', elementStyles );
                window.clover_zip  = elements.create( 'CARD_POSTAL_CODE', elementStyles );

                window.clover_card.addEventListener( 'change', function( event ) {
                    moo_clover_gateway.onCCFormChange();
                    $( document.body ).trigger( 'cloverError', event );
                } );

                window.clover_exp.addEventListener( 'change', function( event ) {
                    moo_clover_gateway.onCCFormChange();
                    $( document.body ).trigger( 'cloverError', event );
                } );

                window.clover_cvc.addEventListener( 'change', function( event ) {
                    moo_clover_gateway.onCCFormChange();
                    $( document.body ).trigger( 'cloverError', event );
                } );
                window.clover_zip.addEventListener( 'change', function( event ) {
                    moo_clover_gateway.onCCFormChange();
                    $( document.body ).trigger( 'cloverError', event );
                } );
                window.moo_clover_gateway.mountElements();
            },

            /**
             * Initialize event handlers and UI state.
             */
            init: function() {
                $( document )
                    .on(
                        'cloverError',
                        this.onError
                    )
                    .on(
                        'checkout_error',
                        this.reset
                    );


                moo_clover_gateway.createElements();

            },
            /**
             * Check whether a mobile device is being used.
             *
             * @return {boolean}
             */
            isMobile: function() {
                if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test( navigator.userAgent ) ) {
                    return true;
                }

                return false;
            },

            /**
             * Returns the selected payment method HTML element.
             *
             * @return {HTMLElement}
             */
            getSelectedPaymentElement: function() {
                return $( '.payment_methods input[name="payment_method"]:checked' );
            },


            /**
             * Initiates the creation of a Source object.
             *
             * Currently this is only used for credit cards and SEPA Direct Debit,
             * all other payment methods work with redirects to create sources.
             */
            createSource: function() {
                // Handle card payments.
                return clover.createToken()
                    .then( moo_clover_gateway.sourceResponse );
            },

            /**
             * Handles responses, based on source object.
             *
             * @param {Object} response The `stripe.createSource` response.
             */
            sourceResponse: function( response ) {
                if ( response.error ) {
                    return $( document.body ).trigger( 'cloverError', response );
                }
                moo_clover_gateway.reset();
                $( '#moo-CloverToken' ).val( response.token )
            },

            /**
             * If a new credit card is entered, reset sources.
             */
            onCCFormChange: function() {
              //  window.moo_clover_gateway.reset();
            },

            /**
             * Removes all errors from the form.
             */
            reset: function() {
                if ( ! $( '#moo-checkout-form-payments #moo-cloverCreditCardPanel .clover-error' ).length ) {
                   // $( '#moo-checkout-form-payments #moo-cloverCreditCardPanel .clover-error' ).text("");
                }

            },


            /**
             * Displays stripe-related errors.
             *
             * @param {Event}  e      The jQuery event.
             * @param {Object} result The result of Stripe call.
             */
            onError: function( e, result ) {
                //Card Number
                var hasError = false;
                var errorMessage = "";

                if ( result.CARD_NUMBER.error ) {
                    errorMessage = result.CARD_NUMBER.error;
                    hasError = true;
                } else {
                    //date
                    if ( result.CARD_DATE.error ) {
                        errorMessage = result.CARD_DATE.error;
                        hasError = true;
                    } else {
                        //cvv
                        if ( result.CARD_CVV.error ) {
                            errorMessage = result.CARD_CVV.error;
                            hasError = true;
                        } else {
                            //zip code
                            if ( result.CARD_POSTAL_CODE.error ) {
                                errorMessage = result.CARD_POSTAL_CODE.error;
                                hasError = true;
                            }
                        }
                    }

                }
                window.cloverCardIsValid = ! hasError ;
                window.cloverCardErrorMsg = errorMessage ;
            },

            /**
             * Displays an error message in the beginning of the form and scrolls to it.
             *
             * @param {Object} error_message An error message jQuery object.
             */
            submitError: function( error_message ) {
            }
        };

        $( document ).ready(function() {
            window.moo_clover_gateway.init();
        });
    }

    $( document ).ready(function() {
        moo_tips_select_changed();
        //If there is only one order type select it
        if(mooCheckoutOptions.moo_OrderTypes.length === 1) {
            jQuery("#moo-checkout-form-ordertypes-"+mooCheckoutOptions.moo_OrderTypes[0].ot_uuid).iCheck('check');
            moo_OrderTypeChanged(mooCheckoutOptions.moo_OrderTypes[0].ot_uuid);
        } else {
            if(mooCheckoutOptions.moo_OrderTypes.length === 0){
                jQuery("#moo-checkout-form-orderdate").show();
                mooGlobalParams.allowScOrders = true;
            }
        }
    });


    try {

        $('.moo-checkout-form-ordertypes-input').iCheck({
            checkboxClass: 'icheckbox_square',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
        $('.moo-checkout-form-payments-input').iCheck({
            checkboxClass: 'icheckbox_square',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
        $('.moo-checkout-form-savecard-input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        $('.moo-checkout-form-ordertypes-input').on('ifClicked', function (event) {
            var OrderTypeID = jQuery(event.target).val();
            moo_OrderTypeChanged(OrderTypeID);
        });

        $('.moo-checkout-form-payments-input').on('ifClicked', function (event) {
            var paymentType = jQuery(event.target).val();
            moo_changePaymentMethod(paymentType)
        });

        $('.moo-checkout-form-payments-option label').css('font-size', "15px");
        $('.moo-checkout-form-payments-option label').css('vertical-align', "sub");
        $('.moo-checkout-form-ordertypes-option label').css('font-size', "15px");
        $('.moo-checkout-form-ordertypes-option label').css('vertical-align', "sub");
    }
    catch (e) {
        $('.moo-checkout-form-ordertypes-input').on('click', function (event) {
            var OrderTypeID = jQuery(event.target).val();
            moo_OrderTypeChanged(OrderTypeID);
        });

        $('.moo-checkout-form-payments-input').on('click', function (event) {
            var paymentType = jQuery(event.target).val();
            moo_changePaymentMethod(paymentType)
        });
        console.log(e.message);
    }

    //apply coupons automatically
    var sooCoupon = localStorage.getItem("soo-coupon");
    if(sooCoupon){
        jQuery
            .post(moo_params.ajaxurl,{'action':'moo_coupon_apply','moo_coupon_code':sooCoupon}, function (data) {
                if(data !== null) {
                    if(data.status === "success"){
                        mooCheckoutOptions.totals = data.total;
                        if(data.type.toUpperCase() === "AMOUNT") {
                            swal({ title: "Coupon applied", text: "Success! You have received a discount of $"+data.value,   type: "success",timer:5000, confirmButtonText: "Ok" });
                        } else {
                            swal({ title: "Coupon applied", text: "Success! You have received a discount of "+data.value+"%",   type: "success",timer:5000, confirmButtonText: "Ok" });
                        }

                        jQuery("#moo_remove_coupon_code").html(sooCoupon);
                        jQuery("#moo_enter_coupon").hide();
                        jQuery("#moo_remove_coupon").show();

                        mooCheckout.updateTotals(true);
                    } else {
                        if(data.error && data.error === "min_failed"){

                            swal({ title: "There is a coupon that can be applied to this order",
                                    text: data.message,
                                    type: "warning",
                                    confirmButtonText: "Ok"
                                });

                        }
                    }

                } else {
                    jQuery("#moo_remove_coupon").hide();
                    jQuery("#moo_enter_coupon").show();
                }

            })
            .fail(function(data) {
                console.log('FAIL');
                console.log(data.responseText);
                swal({ title: "Error", text:"verify your connection and try again",   type: "error",timer:5000,   confirmButtonText: "Try again" });
            });
    }
});

var MooCustomer = null;
var MooCustomerAddress = null;
var MooCustomerChoosenAddress = null;
var MooDeliveryfees = null;
var MooServicefees = null; // Payment using saved creditcard fees
var MooIsGuest = false;
var MooIsDisabled;
var MooPhoneIsVerified = false;
var MooOrderTypeMinAmount = 0;
var MooIsDeliveryError = true;
var MooIsDeliveryOrder = false;
var MooPhoneVerificationActivated = true;

if(typeof mooCheckoutOptions.moo_use_sms_verification !== 'undefined' && mooCheckoutOptions.moo_use_sms_verification === 'disabled') {
     MooPhoneVerificationActivated = false;
}

if(typeof mooCheckoutOptions.moo_checkout_login !== undefined)
{
    MooIsDisabled =(mooCheckoutOptions.moo_checkout_login === "disabled");
} else {
    MooIsDisabled = true;
}

if(typeof mooCheckoutOptions.moo_save_cards !== undefined)
{
    MooSaveCards =(mooCheckoutOptions.moo_save_cards === "enabled");
} else {
    MooSaveCards = false;
}

if(typeof mooCheckoutOptions.moo_save_cards_fees !== undefined)
{
    MooSaveCardsFees =(mooCheckoutOptions.moo_save_cards_fees === "enabled");
} else {
    MooSaveCardsFees = false;
}


if(typeof mooCheckoutOptions.moo_fb_app_id !== undefined && mooCheckoutOptions.moo_fb_app_id !== null)
{
    if(mooCheckoutOptions.moo_fb_app_id !== "") {
        window.fbAsyncInit = function() {
            FB.init({
                appId      : mooCheckoutOptions.moo_fb_app_id,
                xfbml      : true,
                version    : 'v2.8'
            });
            FB.AppEvents.logPageView();
        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    }
}

var hash = window.location.hash;
if (hash != "") {
   // console.log(hash);
    switch (hash) {
        case "#register":
            moo_show_sigupform();
            break;
        case "#forget-password":
            moo_show_forgotpasswordform();
            break;
        case "#login":
            moo_show_loginform();
            break;
    }
}

if(!MooPhoneVerificationActivated) {
    MooPhoneIsVerified = true;
}

function moo_OrderTypeChanged(OrderTypeID) {
    if(!(typeof mooCheckoutOptions.moo_OrderTypes === 'undefined')) {
        for(i in mooCheckoutOptions.moo_OrderTypes) {
            if(OrderTypeID == mooCheckoutOptions.moo_OrderTypes[i].ot_uuid) {
                var selectedOrderType = mooCheckoutOptions.moo_OrderTypes[i];

                if(selectedOrderType.allow_sc_order == "0") {
                    jQuery("#moo-checkout-form-orderdate").hide();
                    mooGlobalParams.allowScOrders = false;
                } else {
                    jQuery("#moo-checkout-form-orderdate").show();
                    mooGlobalParams.allowScOrders = true;
                }

                if(selectedOrderType.show_sa == "1") { //The order type is delivery type
                    MooIsDeliveryOrder = true;
                    //Change the order date
                    moo_ChangeOrderDate('delivery');

                    jQuery('#MooDeliveryfeesInTotalsSection').show();
                    if(MooCustomerChoosenAddress != null) {

                        var html ='<strong>Delivery to:</strong><br />';
                        var address_string="";

                        if(MooCustomerChoosenAddress.address != '')
                            address_string += MooCustomerChoosenAddress.address+' ';
                        if(MooCustomerChoosenAddress.line2 != '')
                            address_string += MooCustomerChoosenAddress.line2+' ';
                        if(MooCustomerChoosenAddress.city != '')
                            address_string += MooCustomerChoosenAddress.city+', ';
                        if(MooCustomerChoosenAddress.state != '')
                            address_string += MooCustomerChoosenAddress.state+' ';
                        if(MooCustomerChoosenAddress.zipcode != '')
                            address_string += MooCustomerChoosenAddress.zipcode;
                        html += address_string;

                        html += '<br/>';
                        html += '<div id="mooDeliveryAmountInformation"></div>';
                        html += '<br/>';
                        html += '<a class="MooSimplButon" href="#" onclick="moo_show_chooseaddressform(event)">Edit address</a>';


                        jQuery('#moo-checkout-form-ordertypes>.moo-checkout-bloc-message').html(html);
                        jQuery('#moo-checkout-form-ordertypes>.moo-checkout-bloc-message').show();
                        moo_calculate_delivery_fee(MooCustomerChoosenAddress.lat,MooCustomerChoosenAddress.lng,moo_update_delivery_amount);
                    } else {
                        var html ='<strong>No address selected</strong><br /><br />';
                        html += '<a href="#" role="button" tabindex="0" class="MooSimplButon" onclick="moo_show_chooseaddressform(event)">Add/Edit address</a>';
                        jQuery('#moo-checkout-form-ordertypes>.moo-checkout-bloc-message').html(html);
                        jQuery('#moo-checkout-form-ordertypes>.moo-checkout-bloc-message').show();
                        MooDeliveryfees = 0.00;
                    }

                        if(mooCheckoutOptions.moo_cash_upon_delivery === "on") {
                            jQuery(".moo-checkout-form-payments-cash-container").show();
                            jQuery("#moo-checkout-form-payincash-label").text('Pay upon Delivery');
                            jQuery("#moo-checkout-form-payments-cash").val('cash');

                            if(MooCustomer != null) {
                                if(
                                    MooCustomer[0].phone_verified === 0 ||
                                    MooCustomer[0].phone_verified === '0' ||
                                    MooCustomer[0].phone_verified === false
                                ) {

                                    if(MooPhoneVerificationActivated) {
                                        jQuery("#moo-checkout-form-payments #moo_cashPanel").show();
                                    }
                                }
                            } else {
                                if(MooPhoneVerificationActivated) {
                                    jQuery("#moo-checkout-form-payments #moo_cashPanel").show();
                                }
                            }
                        } else  {
                            jQuery("#moo-checkout-form-payments-cash").val('');
                            jQuery(".moo-checkout-form-payments-cash-container").hide();
                            jQuery("#moo-checkout-form-payments #moo_cashPanel").hide();

                        }



                } else {
                    MooIsDeliveryOrder = false;
                    //Change the order date
                    moo_ChangeOrderDate('pickup');

                    jQuery('#moo-checkout-form-ordertypes>.moo-checkout-bloc-message').hide();
                    jQuery('#MooDeliveryfeesInTotalsSection').hide();
                    MooDeliveryfees = 0;


                        if(mooCheckoutOptions.moo_cash_in_store === "on") {

                            jQuery(".moo-checkout-form-payments-cash-container").show();
                            jQuery("#moo-checkout-form-payincash-label").text('Pay at location');
                            jQuery("#moo-checkout-form-payments-cash").val('cash');

                            if(MooCustomer != null) {
                                if(
                                    MooCustomer[0].phone_verified === 0 ||
                                    MooCustomer[0].phone_verified === '0' ||
                                    MooCustomer[0].phone_verified === false
                                ) {

                                    if(MooPhoneVerificationActivated) {
                                        jQuery("#moo-checkout-form-payments #moo_cashPanel").show();
                                    }
                                }
                            } else {
                                if(MooPhoneVerificationActivated) {
                                    jQuery("#moo-checkout-form-payments #moo_cashPanel").show();
                                }
                            }

                        } else {
                            jQuery("#moo-checkout-form-payments-cash").val('');
                            jQuery(".moo-checkout-form-payments-cash-container").hide();
                            jQuery("#moo-checkout-form-payments #moo_cashPanel").hide();
                        }



                }

                if(
                    selectedOrderType.taxable === 1 ||
                    selectedOrderType.taxable === "1" ||
                    selectedOrderType.taxable === true ||
                    selectedOrderType.taxable === "true"
                ) {
                    mooGlobalParams.selectedOrderTypeIsTaxable  = true;
                } else {
                    mooGlobalParams.selectedOrderTypeIsTaxable  = false;
                }

                if(selectedOrderType.minAmount != "0") {
                    MooOrderTypeMinAmount = selectedOrderType.minAmount;
                } else {
                    MooOrderTypeMinAmount = 0;
                }
                if( selectedOrderType.use_coupons === "0" ||
                    selectedOrderType.use_coupons === 0||
                    selectedOrderType.use_coupons === false ) {
                    jQuery("#moo-checkout-form-coupon").hide();
                    jQuery
                        .post(moo_params.ajaxurl,{'action':'moo_coupon_remove'}, function (data) {
                            if(data.status=="success")
                            {
                                mooCheckoutOptions.totals = data.total;
                                jQuery("#moo_remove_coupon_code").html("");
                                jQuery('#moo_coupon').val('');
                                jQuery("#moo_enter_coupon").show();
                                jQuery("#moo_remove_coupon").hide();
                                mooCheckout.updateTotals(true);
                            }
                        })
                } else {
                    jQuery("#moo-checkout-form-coupon").show();
                }

                mooCheckout.updateTotals();
            }
        }
    }
}

function  moo_tips_select_changed() {
    var tips_select_percent = jQuery('#moo_tips_select').val();
    if(tips_select_percent != "cash" && tips_select_percent != 'other'){
        jQuery('#moo_tips').val((mooCheckoutOptions.totals.sub_total*tips_select_percent/10000).toFixed(2))
    }
    else
        if(tips_select_percent == "cash")
            jQuery('#moo_tips').val(0);
        else
            jQuery('#moo_tips').select();

    moo_change_total_with_tips();
}

function moo_tips_amount_changed() {
    var amount = parseFloat(jQuery('#moo_tips').val());
    if(!isNaN(amount)){
        jQuery('#moo_tips').val((amount).toFixed(2));
    } else {
        jQuery('#moo_tips').val("0.00");
    }
    moo_change_total_with_tips();
}

function moo_change_total_with_tips() {
    mooCheckout.updateTotals();
}

function cryptCardNumber(ccn) {
    var rsa = forge.pki.rsa;

    var modulus = mooCheckoutOptions.moo_Key.modulus;
    var exponent = mooCheckoutOptions.moo_Key.exponent;
    var prefix = mooCheckoutOptions.moo_Key.prefix;
    var text = prefix + ccn;
    modulus = new forge.jsbn.BigInteger(modulus);
    exponent = new forge.jsbn.BigInteger(exponent);
    text = text.split(' ').join('');
    var publicKey = rsa.setPublicKey(modulus, exponent);
    var encryptedData = publicKey.encrypt(text, 'RSA-OAEP');
    return forge.util.encode64(encryptedData);
}
function firstSix(ccn) {
    var cardNumber = ccn.split(' ').join('').trim();
    return cardNumber.substr(0,6);
}
function lastFour(ccn) {
    var cardNumber = ccn.split(' ').join('').trim();
    return cardNumber.substr(-4);
}

function moo_verifyPhone(event) {
    event.preventDefault();
    var phone_number = jQuery('#Moo_PhoneToVerify').val();
    if(phone_number === ""){
        swal({
            title: "Error",
            text: "Please enter your phone number",
            type: "error",
            confirmButtonText: "Try again"
        });
        return;
    }
    swal({
        title: 'Sending the verification code please wait ..',
        showConfirmButton: false
    });

    jQuery.post(moo_params.ajaxurl,{'action':'moo_send_sms','phone':phone_number},function (response) {
        if(response && response.status && response.status === "failed"){
            if(response.result && response.result.message) {
                swal({
                    title: "Error",
                    text: response.result.message,
                    type: "error",
                    confirmButtonText: "Try again"
                });
                return;
            } else {
                swal({
                    title: "Error",
                    text: "An error has occurred please try again",
                    type: "error",
                    confirmButtonText: "Try again"
                });
                return;
            }
        } else {
            swal.close();
            jQuery('#moo_verifPhone_sending').hide();
            jQuery('#moo_verifPhone_verified').hide();
            jQuery('#Moo_VerificationCode').val('');
            jQuery('#moo_verifPhone_verificatonCode').show();
            jQuery('#Moo_VerificationCode').focus();
        }
    }).fail(function (data) {
        swal({
            title: "Error",
            text:"An error has occurred please try again",
            type: "error",
            confirmButtonText: "Try again"
        });
    });
}

function moo_verifyCode(event) {
    event.preventDefault();
    var code=jQuery('#Moo_VerificationCode').val();
    jQuery.post(moo_params.ajaxurl,{'action':'moo_check_verification_code','code':code}, function (data) {
        if(data.status == 'success')
        {
            jQuery('#moo_verifPhone_sending').hide();
            jQuery('#moo_verifPhone_verificatonCode').hide();
            jQuery('#moo_verifPhone_verified').css("display","inline-block");
            swal({ title: 'Phone verified', text: 'Please have your payment ready when picking up from the store and don\'t forget to finalize your order below',   type: "success",timer:5000,   confirmButtonText: "OK" });
            if(MooCustomer != null) {
                MooCustomer[0].phone_verified = '1';
            }
            MooPhoneIsVerified = true;
            jQuery('#MooContactPhone').prop("readonly",true);
        } else {
            swal({ title: "Code invalid", text: 'this code is invalid please try again',   type: "error",timer:5000,   confirmButtonText: "Try again" });
            setTimeout(function () {
                jQuery('#Moo_VerificationCode').focus();
            },500)
        }
    });
}

function moo_verifyCodeTryAgain(event) {
    event.preventDefault();
    jQuery('#moo_verifPhone_sending').show();
    jQuery('#moo_verifPhone_verificatonCode').hide();
    jQuery('#moo_verifPhone_verified').hide();
    jQuery('#Moo_PhoneToVerify').focus();
}


function moo_changePaymentMethod(type) {
    if(type === 'cash') {
        //Hide the tips
        jQuery('#moo-checkout-form-tips').hide();
        jQuery('#MooTipsInTotalsSection').hide();
        jQuery('#moo-checkout-form-savecard').hide();
        if(document.getElementById('moo_tips') != null) {
            jQuery('#moo_tips_select').val('cash');
            jQuery('#moo_tips').val('0');
        }
        if(MooCustomer != null) {
            if(
                MooCustomer[0].phone_verified === 0 ||
                MooCustomer[0].phone_verified === '0' ||
                MooCustomer[0].phone_verified === false
            ) {
                if(MooCustomer != null)
                    jQuery('#Moo_PhoneToVerify').val(MooCustomer[0].phone);
                if(MooPhoneVerificationActivated) {
                    jQuery('#moo_cashPanel').show();
                }
            }
        } else {
            if(MooPhoneVerificationActivated) {
                jQuery('#moo_cashPanel').show();
            }
        }
        jQuery('#moo_creditCardPanel').hide();
        jQuery('#moo-cloverCreditCardPanel').hide();
    } else {
        if(type === "clover"){
            jQuery('#moo-checkout-form-tips').show();
            jQuery('#MooTipsInTotalsSection').show();

            jQuery('#moo_cashPanel').hide();
            jQuery('#moo_creditCardPanel').hide();
            jQuery('#moo-cloverCreditCardPanel').show();

        } else {
            jQuery('#moo-checkout-form-tips').show();
            jQuery('#MooTipsInTotalsSection').show();

            jQuery('#moo_cashPanel').hide();
            jQuery('#moo-cloverCreditCardPanel').hide();
            jQuery('#moo_creditCardPanel').show();

            /*
            if(!(!MooIsDisabled && MooSaveCards && !MooIsGuest)) {
                jQuery('#moo_creditCardPanel').show();
                jQuery('#moo-checkout-form-savecard').hide();

            } else {
                jQuery('#moo-checkout-form-savecard').show();
            }
             */

        }


    }
    MooServicefees = 0;
    mooCheckout.updateTotals();
}

function moo_pickup_day_changed(element) {
    var theDay = jQuery(element).val();

    if(MooIsDeliveryOrder)
        var times = mooCheckoutOptions.moo_pickup_time_for_delivery[theDay];
    else
        var times = mooCheckoutOptions.moo_pickup_time[theDay];

    var html  = '';

    if(!(typeof times === 'undefined')) {
        for(i in times)
            html += '<option value="'+times[i]+'">'+times[i]+'</option>'
    }
    else
        html = '';
   jQuery('#moo_pickup_hour').html(html);
}
function moo_ChangeOrderDate(type) {
    var dayInput      = jQuery('#moo_pickup_day');
    var hoursInput    = jQuery('#moo_pickup_hour');
    var theDay        = '';
    var html_days = '';
    var html_hours  = '';

    if(type == 'pickup' ) {
        if(!mooCheckoutOptions.moo_pickup_time){
            return;
        }
        var first = true;
        for(var i in mooCheckoutOptions.moo_pickup_time) {
            if(first) {
                theDay = i;
                first = false;
            }
            html_days += '<option value="'+i+'">'+i+'</option>';
        }
        var times = mooCheckoutOptions.moo_pickup_time[theDay];

    } else {
        if(!mooCheckoutOptions.moo_pickup_time_for_delivery){
            return;
        }
        var first = true;
        for(var i in mooCheckoutOptions.moo_pickup_time_for_delivery)
        {
            if(first)
            {
                theDay = i;
                first = false;
            }
            html_days += '<option value="'+i+'">'+i+'</option>';
        }
        var times = mooCheckoutOptions.moo_pickup_time_for_delivery[theDay];

    }

    if(!(typeof times === 'undefined')) {
        for(i in times)
            html_hours += '<option value="'+times[i]+'">'+times[i]+'</option>'
    }
    else
        html_hours = '';

   hoursInput.html(html_hours);
   dayInput.html(html_days);
}

function moo_order_approved(orderId) {
    if(mooCheckoutOptions.moo_thanks_page != '' && mooCheckoutOptions.moo_thanks_page != null ) {
        window.location.href = mooCheckoutOptions.moo_thanks_page+'?order_id='+orderId;
    } else {
        if(orderId == '')
            html = '<div align="center" class="moo-alert moo-alert-success" role="alert" style="font-size: 20px;">Thank you for your order<br/>Your order is being prepared</div>';
        else
            html = '<div align="center" class="moo-alert moo-alert-success" role="alert"  style="font-size: 20px;" >Thank you for your order<br/>Your order is being prepared<br> You can see your receipt <a href="https://www.clover.com/r/'+orderId+'" target="_blank">here</a></a> </div>';

        // console.log(html);
        jQuery('#moo_checkout_msg').remove();
        jQuery("#moo-checkout").html('');
        if(mooDeliveryOptions.moo_merchantAddress !== "") {
            jQuery("#moo-checkout").parent().prepend("<div style='text-align: center'><p style='font-size: 21px;'>Our Address : </p>"+mooDeliveryOptions.moo_merchantAddress+"</div>");
        }
        jQuery("#moo-checkout").parent().prepend(html);
        jQuery("html, body").animate({
            scrollTop: 0
        }, 600);
    }
}
function moo_order_notApproved(message) {
    mooStopLoading();
    if(message && message !== '' && message !== undefined) {
        html = '<div class="moo-alert moo-alert-danger" role="alert" id="moo_checkout_msg">'+message+'</div>';
    } else {
        html = '<div class="moo-alert moo-alert-warning" role="alert" id="moo_checkout_msg"><strong>We weren\'t able to send the entire order to the store, please try again or contact us</strong></div>';
    }
    jQuery("#moo-checkout .errors-section").html(html);
    jQuery("html, body").animate({
        scrollTop: 0
    }, 600);
}

function mooStartLoading() {
    //hide Submit button
    jQuery('#moo_btn_submit_order').hide();
    //Show loading Icon
    jQuery('#moo_checkout_loading').show();
}
function mooStopLoading() {
    //Hide Loading Icon and Show the button if there is an error
    jQuery('#moo_checkout_loading').hide();
    jQuery('#moo_btn_submit_order').show();
}

function moo_show_sigupform(e) {
    if(e !== undefined)
        e.preventDefault();
    jQuery('#moo-login-form').hide();
    jQuery('#moo-signing-form').show();
    jQuery('#moo-forgotpassword-form').hide();
    jQuery('#moo-chooseaddress-form').hide();
}
function moo_show_loginform() {
   // e.preventDefault();
    jQuery('#moo-login-form').show();
    jQuery('#moo-signing-form').hide();
    jQuery('#moo-forgotpassword-form').hide();
    jQuery('#moo-chooseaddress-form').hide();
    jQuery('#moo-addaddress-form').hide();
    jQuery('#moo-checkout-form').hide();

}
function moo_show_forgotpasswordform(e) {
    if(e !== undefined)
        e.preventDefault();

    jQuery('#moo-login-form').hide();
    jQuery('#moo-signing-form').hide();
    jQuery('#moo-forgotpassword-form').show();
    jQuery('#moo-chooseaddress-form').hide();
    jQuery('#moo-addaddress-form').hide();
    jQuery('#moo-checkout-form').hide();
}
function moo_show_form_adding_address() {
    jQuery('#inputMooAddress').val('');
    jQuery('#inputMooCity').val('');
    jQuery('#inputMooState').val('');
    jQuery('#inputMooZipcode').val('');
    jQuery('#inputMooLat').val('');
    jQuery('#inputMooLng').val('');
    jQuery('#MooMapAddingAddress').hide();
    jQuery('#mooButonAddAddress').hide();
    jQuery('#mooButonChangeAddress').hide();

    jQuery('#moo-login-form').hide();
    jQuery('#moo-signing-form').hide();
    jQuery('#moo-forgotpassword-form').hide();
    jQuery('#moo-chooseaddress-form').hide();
    jQuery('#moo-addaddress-form').show();
    jQuery('#moo-checkout-form').hide();

    jQuery(".mooFormAddingAddress").show();

    setTimeout(function () {
        jQuery('#inputMooAddress').focus();
    },500);

}

function moo_show_chooseaddressform(e) {
    if(typeof e !== "undefined") {
        e.preventDefault();
        e.stopPropagation();
    }

    var addresses = null;
    var cards = null;
    if(MooIsGuest || MooIsDisabled) {
        MooCustomerAddress = null;
        MooCustomer        = null;
        moo_show_form_adding_address();
    } else {
        jQuery('#moo-chooseaddress-formContent').html('<p style="text-align:center">Loading your addresses</p>');
        jQuery('#moo-login-form').hide();
        jQuery('#moo-signing-form').hide();
        jQuery('#moo-forgotpassword-form').hide();
        jQuery('#moo-chooseaddress-form').show();
        jQuery('#moo-addaddress-form').hide();
        jQuery('#moo-checkout-form').hide();


        jQuery
            .post(moo_params.ajaxurl,{'action':'moo_customer_getAddresses'}, function (data) {
                if(data.status == 'success') {
                    addresses =  data.addresses;
                    cards = data.cards;
                    MooCustomerAddress = addresses;
                    MooCustomer = data.customer;

                    if(MooCustomer[0]!== undefined && MooCustomer[0].phone_verified == "1")
                        MooPhoneIsVerified = true;

                    if(addresses.length>0) {
                        var html="";
                        if(addresses.length === 1) {
                            var OneAddress = addresses[0];
                            html +='<div class="moo-col-md-4 moo-col-md-offset-4">';
                            html +='<div class="moo-address-block">';
                            html +='<span title="delete this address" onclick="moo_delete_address(event,'+OneAddress.id+')">X</span>';
                            html +=OneAddress.address+' ';
                            html +=OneAddress.line2;
                            html +=OneAddress.city+', '+OneAddress.state+' '+OneAddress.zipcode+' ';
                            html +='<a class="MooSimplButon MooUseAddressButton" href="#" onclick="moo_useAddress(event,'+OneAddress.id+')">USE THIS ADDRESS</a>';
                            html +='</div></div>';
                        } else {
                            for(i in addresses) {
                                var OneAddress = addresses[i];
                                html +='<div class="moo-col-md-4 ">';
                                html +='<div class="moo-address-block">';
                                html +='<span title="delete this address" onclick="moo_delete_address(event,'+OneAddress.id+')">X</span>';
                                html +=OneAddress.address+' ';
                                html +=OneAddress.line2;
                                html +=OneAddress.city+', '+OneAddress.state+' '+OneAddress.zipcode+' ';
                                html +='  <a class="MooSimplButon MooUseAddressButton" href="#" onclick="moo_useAddress(event,'+OneAddress.id+')">USE THIS ADDRESS</a>';
                                html +='</div></div>';
                            }
                        }
                        //Display addresses
                        jQuery('#moo-chooseaddress-formContent').html(html);
                    }
                    else
                        moo_show_form_adding_address();

                } else {
                    if(data.status === 'expired') {
                        MooCustomerAddress = null;
                        MooCustomer = null;
                        swal({ title: "Your session is expired", type: "error",timer:5000,   confirmButtonText: "Login again" });
                        moo_show_loginform();
                    }
                }
            })
            .fail(function(data) {
                MooCustomerAddress = null;
                MooCustomer        = null;
                swal({ title: "Your session is expired", type: "error",timer:5000,   confirmButtonText: "Login again" });
                moo_show_loginform();
            });
    }

}
function moo_login(e) {
    e.preventDefault();
    jQuery(e.target).html('<i class="fas fa-circle-notch fa-spin"></i>').attr('onclick','');

    MooIsGuest = false;
    var email    =  jQuery('#inputEmail').val();
    var password =  jQuery('#inputPassword').val();
    if(email == '') {
        swal({ title: "Please enter your email",text:"",  timer:5000, type: "error" });
        jQuery(e.target).html('Login In').attr('onclick','moo_login(event)');
        return;
    }
    if(password == '') {
        swal({ title: "Please enter your password",text:"",  timer:5000, type: "error"});
        jQuery(e.target).html('Login In').attr('onclick','moo_login(event)');
        return;
    }
    jQuery
        .post(moo_params.ajaxurl,{'action':'moo_customer_login','email':email,"password":password}, function (data) {
            jQuery(e.target).html('Log In').attr('onclick','moo_login(event)');
            if(data.status == 'success') {
                moo_show_chooseaddressform(e);
            } else {
                swal({ title: "Invalid User Name or Password",text:"Please click on forgot password or Please register as new user.",   type: "error",timer:5000,   confirmButtonText: "Try again" });
            }
        })
        .fail(function(data) {
            swal({ title: "Invalid User Name or Password",text:"Please click on forgot password or Please register as new user.",   type: "error",timer:5000,   confirmButtonText: "Try again" });
            jQuery(e.target).html('Login In').attr('onclick','moo_login(event)');

        });
}
function moo_loginAsguest(e) {
    MooIsGuest = true;
    e.preventDefault();
    moo_checkout_form();
}
function moo_loginViaFacebook(e) {
    e.preventDefault();
    FB.login(function(response) {

        if (response.status === 'connected') {
            // Logged into your app and Facebook.
            FB.api('/me',{fields: 'email,name'}, function(response) {
                if(typeof response.email ==='undefined') {
                    swal("You don't have an email on your Facebook account",'The email is mandatory, we use it to send the receipt','error');
                    return;
                }
                jQuery
                    .post(moo_params.ajaxurl,{'action':'moo_customer_fblogin','email':response.email,"name":response.name,"fbid":response.id}, function (data) {
                        if(data.status == 'success')  {
                            MooIsGuest = false;
                            moo_show_chooseaddressform(e);
                        } else {
                            swal({ title: "An error has occurred, Please try again",text:"",   type: "error",   confirmButtonText: "Try again" });
                        }
                    })
                    .fail(function(data) {
                        swal({ title: "An error has occurred, Please try again",text:"",   type: "error",   confirmButtonText: "Try again" });
                    });
            });

        } else if (response.status === 'not_authorized') {
            // The person is logged into Facebook, but not your app.
            console.log(response);
        } else {
            // The person is not logged into Facebook, so we're not sure if
            // they are logged into this app or not.
            console.log(response);
        }
    }, {scope: 'public_profile,email'});
}
function moo_signin(e) {
    e.preventDefault();
    var title     = "";
    var full_name = jQuery('#inputMooFullName').val();
    var email     = jQuery('#inputMooEmail').val();
    var phone     = jQuery('#inputMooPhone').val();
    var password  = jQuery('#inputMooPassword').val();
    var  regex_email =  /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(email=='') {
        swal("Please enter your email");
        return;
    }
    if(! regex_email.test(email))
    {
        swal("Please enter a valid email");
        return;
    }

    if(password=='') {
        swal("Please enter your password");
        return;
    }
    if(phone=='') {
        swal("Please enter your phone");
        return;
    }
    jQuery(e.target).html('<i class="fas fa-circle-notch fa-spin"></i>').attr('onclick','');
    jQuery
        .post(moo_params.ajaxurl,{'action':'moo_customer_signup','title':title,'full_name':full_name,'phone':phone,'email':email,"password":password}, function (data) {
            if(data.status == 'success')
            {
                jQuery(e.target).html('Register').attr('onclick','moo_signin(event)');
                moo_show_chooseaddressform(e);
            }
            else
            {
                jQuery(e.target).html('Register').attr('onclick','moo_signin(event)');

                swal({ title: "Invalid Email",text:"Please click on forgot password or enter a new email",   type: "error",   confirmButtonText: "Try again" });
            }
        })
        .fail(function(data) {
            console.log(data.responseText);
            jQuery(e.target).html('Register').attr('onclick','moo_signin(event)');
            swal({ title: "Invalid User Name or Password",text:"Please click on forgot password or Please register as new user.",   type: "error",   confirmButtonText: "Try again" });
        });
}
function moo_resetpassword(e) {
    e.preventDefault();
    var email     = jQuery('#inputEmail4Reset').val();
    if(email=='') {
        swal('Please enter your email');
    } else {
        jQuery(e.target).html('<i class="fas fa-circle-notch fa-spin"></i>').attr('onclick','');
        jQuery
            .post(moo_params.ajaxurl,{'action':'moo_customer_resetpassword','email':email}, function (data) {
                if(data && data.status == 'success') {
                    jQuery(e.target).html('Reset').attr('onclick','moo_resetpassword(event)');
                    swal("If the e-mail you specified exists in our system, then you will receive an e-mail shortly to reset your password.");
                    moo_show_loginform();
                } else {
                    jQuery(e.target).html('Reset').attr('onclick','moo_resetpassword(event)');
                    swal({ title: "could not reset your password",text:"Please try again or contact us",   type: "error",   confirmButtonText: "Try again" });
                }
            })
            .fail(function(data) {
                console.log(data.responseText);
                jQuery(e.target).html('Reset').attr('onclick','moo_resetpassword(event)');
                swal({ title: "could not reset your password",text:"Please try again or contact us",   type: "error",   confirmButtonText: "Try again" });
            });
    }
}
function moo_cancel_resetpassword(e) {
    e.preventDefault();
    moo_show_loginform();
}
function moo_initMapAddress() {
    var Merchantlocation = {};
    Merchantlocation.lat = parseFloat(document.getElementById("inputMooLat").value);
    Merchantlocation.lng = parseFloat( document.getElementById("inputMooLng").value);
    var map = new google.maps.Map(document.getElementById('MooMapAddingAddress'), {
        zoom: 16,
        center: Merchantlocation
    });

    var marker = new google.maps.Marker({
        position: Merchantlocation,
        map: map,
        icon:{
            url:moo_params['plugin_img']+'/moo_marker.png'
        },
        draggable:true
    });
    google.maps.event.addListener(marker, 'drag', function() {
        moo_updateMarkerPosition(marker.getPosition());
    });
    var infowindow = new google.maps.InfoWindow({
        content: "Drag&Drop to change the location"
    });
    infowindow.open(map,marker);
}
function moo_updateMarkerPosition(newPosition) {
    jQuery('#inputMooLat').val(newPosition.lat());
    jQuery('#inputMooLng').val(newPosition.lng());
}
function moo_ConfirmAddressOnMap(e) {

    e.preventDefault();
    var address = moo_getAddressFromForm();
    if( address.address == '')
    {
        swal({ title: "Address missing",text:"Please enter your address",   type: "error",   confirmButtonText: "OK" }).then(function() {
            setTimeout(function () {
                jQuery('#inputMooAddress').focus();
            },500);
        });
        return;
    } else {
        if( address.city == '')
        {
            swal({ title: "City missing",text:"Please enter your city",   type: "error",   confirmButtonText: "OK" }).then(function() {
                setTimeout(function () {
                    jQuery('#inputMooCity').focus();
                },500);
            });
            return;
        }
    }
    var address_string = Object.keys(address).map(function(k){return address[k]}).join(" ");
    jQuery.get('https://maps.googleapis.com/maps/api/geocode/json?&address='+encodeURIComponent(address_string)+'&key=AIzaSyBv1TkdxvWkbFaDz2r0Yx7xvlNKe-2uyRc',function (data) {
        if(data.results.length>0) {
            var location = data.results[0].geometry.location;
            document.getElementById("inputMooLat").value = location.lat;
            document.getElementById("inputMooLng").value = location.lng;
            moo_initMapAddress();
            jQuery('#MooMapAddingAddress').show();
            jQuery('#mooButonAddAddress').show();
            jQuery('#mooButonChangeAddress').show();
            jQuery(".mooFormAddingAddress").hide();
            jQuery(".mooFormConfirmingAddress").show();

        } else {
            swal({ title: "We weren't able to locate this address,try again",text:"",   type: "error",   confirmButtonText: "OK" });
        }
    });

}
function moo_getAddressFromForm() {
    var address = {};
    address.address =  jQuery('#inputMooAddress').val();
    address.line2 =  jQuery('#inputMooAddress2').val();
    address.city =  jQuery('#inputMooCity').val();
    address.state =  jQuery('#inputMooState').val();
    address.zipcode =  jQuery('#inputMooZipcode').val();
    address.lat =  jQuery('#inputMooLat').val();
    address.lng =  jQuery('#inputMooLng').val();
    address.country =  "";
    return address;
}
function moo_addAddress(e) {
    e.preventDefault();
    jQuery(e.target).html('<i class="fas fa-circle-notch fa-spin"></i>').attr('onclick','');
    var address = moo_getAddressFromForm();
    if(address.lat == "") {
        swal({ title: "Please confirm your address on the map",text:"By confirming  your address on the map you will help the driver to deliver your order faster, and you will help us to calculate your delivery fee better",   type: "error",   confirmButtonText: "Confirm"});
    } else {
        if(MooIsGuest || MooIsDisabled)
        {
            MooCustomerChoosenAddress = address;
            moo_checkout_form();
            jQuery(e.target).html('Confirm and add address').attr('onclick','moo_addAddress(event)');
        } else {
            jQuery
                .post(moo_params.ajaxurl,{'action':'moo_customer_addAddress','address':address.address,'line2':address.line2,'city':address.city,'state':address.state,'zipcode':address.zipcode,"lat":address.lat,"lng":address.lng}, function (data) {
                    if(data.status == 'failure' || data.status == 'expired') {
                        swal({ title: "Your session has been expired",text:"Please login again",   type: "error",   confirmButtonText: "Login again" });
                        moo_show_loginform();
                        jQuery(e.target).html('Confirm and add address').attr('onclick','moo_addAddress(event)');
                    }
                    else
                        if(data.status == 'success') {
                            moo_show_chooseaddressform(e);
                            jQuery(e.target).html('Confirm and add address').attr('onclick','moo_addAddress(event)');
                        } else {
                            swal({ title: "Address not added to your account",text:"Please try again or contact us",   type: "error",   confirmButtonText: "Try again" });
                            jQuery(e.target).html('Confirm and add address').attr('onclick','moo_addAddress(event)');
                        }
                }).fail(function(data) {
                    console.log(data.responseText);
                    jQuery(e.target).html('Confirm and add address').attr('onclick','moo_addAddress(event)');
                    swal({ title: "Connection lost",text:"Please try again",   type: "error",   confirmButtonText: "Try again" });
                });
        }

    }

}
function moo_changeAddress(e) {
    e.preventDefault();
    jQuery(".mooFormAddingAddress").show();
    jQuery(".mooFormConfirmingAddress").hide();
}
function moo_useAddress(e,address_id) {
    e.preventDefault();
    for(i in MooCustomerAddress) {
        if(MooCustomerAddress[i].id == address_id)
            MooCustomerChoosenAddress = MooCustomerAddress[i]
    }
    moo_checkout_form();
}
function moo_filling_CustomerInformation() {

    if(MooCustomer != null && MooCustomer[0] != null) {
        jQuery('#MooContactName').val(MooCustomer[0].fullname);
        jQuery('#MooContactPhone').val(MooCustomer[0].phone);
        jQuery('#MooContactEmail').val(MooCustomer[0].email).prop("readonly", true).css("background-color", "#e5e5e5");
        jQuery('#moo-checkout-contact-content').html(MooCustomer[0].fullname+"<br/>"+MooCustomer[0].email+"<br/>"+MooCustomer[0].phone+"<br/>");
        if(MooCustomer[0].fullname!="" && MooCustomer[0].phone !="" && MooCustomer[0].email!="") {
            jQuery('#moo-checkout-contact-form').hide();
            jQuery('#moo-checkout-contact-content').show();
            jQuery('.moo-checkout-edit-icon').show();
        } else {
            jQuery('#moo-checkout-contact-form').show();
            jQuery('#moo-checkout-contact-content').hide();
            jQuery('.moo-checkout-edit-icon').hide();
        }

    } else {
        jQuery('#moo-checkout-contact-form').show();
        jQuery('#moo-checkout-contact-content').hide();
        jQuery('.moo-checkout-edit-icon').hide();
    }
}
function moo_checkout_form() {
    moo_filling_CustomerInformation();
    var checkedOrderTypeID = jQuery('input[name="ordertype"]:checked').val();
    if(checkedOrderTypeID != '') {
        moo_OrderTypeChanged(checkedOrderTypeID);
    }

    jQuery('#moo-login-form').hide();
    jQuery('#moo-signing-form').hide();
    jQuery('#moo-forgotpassword-form').hide();
    jQuery('#moo-chooseaddress-form').hide();
    jQuery('#moo-addaddress-form').hide();
    jQuery('#moo-checkout-form').show();

    setTimeout(function () {
        //if there isone order type check it
        if(mooCheckoutOptions.moo_OrderTypes.length === 1){
            jQuery("#moo-checkout-form-ordertypes-"+mooCheckoutOptions.moo_OrderTypes[0].ot_uuid).iCheck('check');
            moo_OrderTypeChanged(mooCheckoutOptions.moo_OrderTypes[0].ot_uuid);
        } else {
            if(mooCheckoutOptions.moo_OrderTypes.length === 0){
                jQuery("#moo-checkout-form-orderdate").show();
                mooGlobalParams.allowScOrders = true;
            }
        }
    },1000)

}
function moo_pickup_the_order(e) {
    MooCustomerChoosenAddress = null;
    MooDeliveryfees = 0.00;
    moo_checkout_form();
}
function moo_checkout_edit_contact() {
    jQuery('#moo-checkout-contact-content').hide();
    jQuery('.moo-checkout-edit-icon').hide();
    jQuery('#moo-checkout-contact-form').show();
}
function moo_delete_address(event,address_id) {
    swal({
            title: "Are you sure?",
            text: "You will not be able to recover this address",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            showLoaderOnConfirm: true,
            cancelButtonText: "No, cancel!",
            closeOnConfirm: false,
            closeOnCancel: false
    }).then(function(result){
        if (result.value) {
                    jQuery
                        .post(moo_params.ajaxurl,{'action':'moo_customer_deleteAddresses','address_id':address_id}, function (data) {
                            if(data.status == 'failure' || data.status == 'expired')
                            {
                                swal({ title: "Your session has been expired",text:"Please login again",   type: "error",   confirmButtonText: "Login again" });
                                moo_show_loginform();
                            }
                            else
                            if(data.status == 'success')
                            {
                                swal("Deleted!", "Your address has been deleted.", "success");
                                moo_show_chooseaddressform(event);
                            }
                            else
                                swal({ title: "Address not deleted",text:"Please try again or contact us",   type: "error",   confirmButtonText: "Try again" });
                        })
                        .fail(function(data) {
                            console.log(data.responseText);
                            swal({ title: "Connection lost",text:"Address not deleted, please try again",   type: "error",   confirmButtonText: "Try again" });
                        });

            } else {
                swal("Cancelled","","error");
            }
        });
}
function moo_verify_form(form) {
    var regex_exp      = {};
    var message_errors = {};
    var selectedOrderType=null;
    regex_exp.email =  /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    regex_exp.credicard = /^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|(222[1-9]|22[3-9][0-9]|2[3-6][0-9]{2}|27[01][0-9]|2720)[0-9]{12}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11}|62[0-9]{14})$/;
    regex_exp.cvv = /^[0-9]*$/;

    //Get the selected ordertype
    if(!(typeof mooCheckoutOptions.moo_OrderTypes === 'undefined'))
        for(i in mooCheckoutOptions.moo_OrderTypes)
        {
          if(form.ordertype == mooCheckoutOptions.moo_OrderTypes[i].ot_uuid) {
              selectedOrderType = mooCheckoutOptions.moo_OrderTypes[i];
          }

        }
    //check the name
    if(form.name == "") {
        mooStopLoading();
        swal('Please enter your name','','error').then(function() {
            setTimeout(function () {
                jQuery('#MooContactName').focus();
            },500)
        });
        return false;
    }
    //check the email
    if(form.email == "" || !regex_exp.email.test(form.email) ) {
        mooStopLoading();
        swal('Please enter a valid email','We need a valid email to contact you and send to you the receipt','error').then(function() {
            setTimeout(function () {
                jQuery('#MooContactEmail').focus();
            },500);
        });
        return false;
    }
    //check the phone
    if(form.phone == "") {
        mooStopLoading();
        swal('Please enter your phone','We need your phone to contact you if we have any question about your order','error').then(function() {
            setTimeout(function () {
                jQuery('#MooContactPhone').focus();
            },500);
        });
        return false;
    }
    //Check the ordering method
    if(document.getElementById('moo-checkout-form-ordertypes'))
        if((typeof form.ordertype === 'undefined') || form.ordertype == "") {
            mooStopLoading();
            swal('Please choose the ordering method','How you want your order to be served ?','error').then(function() {
                setTimeout(function () {
                    jQuery('#moo-checkout-form-ordertypes').focus();
                },500);
            });
            return false;
        }

    //Check the delivery address and min amount per Order Type
    if(selectedOrderType != null) {
        var minAmount = parseFloat(selectedOrderType.minAmount);
        var maxAmount = parseFloat(selectedOrderType.maxAmount);

        if(isNaN(minAmount)){
            minAmount = 0;
        } else {
            minAmount = minAmount*100;
            minAmount = Math.round(minAmount * 100 ) / 100;
        }

        if(isNaN(maxAmount)){
            maxAmount = null;
        } else {
            maxAmount = maxAmount*100;
            maxAmount = Math.round(maxAmount * 100 ) / 100;
        }

        if(minAmount > 0) {
            if(minAmount > mooCheckoutOptions.totals.sub_total) {
                mooStopLoading();
                swal({
                    title: 'You did not meet the minimum purchase requirement',
                    text:"this ordering method requires a subtotal greater than $"+mooformatCentPrice(minAmount),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Continue shopping",
                    cancelButtonText: "Checkout",
                    closeOnConfirm: false },
                    function(){ window.history.back() });

                return false;
            }
        }
        if( maxAmount ) {
            if(maxAmount < mooCheckoutOptions.totals.sub_total) {
                mooStopLoading();
                swal({
                    title: 'You reached the maximum purchase amount',
                    text:"this ordering method requires a subtotal less than $"+mooformatCentPrice(maxAmount),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Update cart",
                    cancelButtonText: "Checkout",
                    closeOnConfirm: false
                    }).then(function (data) {
                        if(data.value) {
                            swal.close();
                            window.location.href = moo_params.cartPage;
                        }
                    });
                return false;}

        }

        if(selectedOrderType.show_sa =='1') {
            if(MooCustomerChoosenAddress!==null) {
                if(MooCustomerChoosenAddress.lat === '' || MooCustomerChoosenAddress.lng === '') {
                    mooStopLoading();
                    swal('Please verify your address',"We can't found this address on the map, please choose an other address",'error').then(function() {
                        setTimeout(function () {
                            jQuery('#moo-checkout-form-ordertypes>.moo-checkout-bloc-message').focus();
                        },500);
                    });
                    return false;
                } else {
                    if(MooIsDeliveryError === true) {
                        moo_OrderTypeChanged(selectedOrderType.ot_uuid);
                        mooStopLoading();
                        swal('Please verify your address',"",'error').then(function() {
                            setTimeout(function () {
                                jQuery('#moo-checkout-form-ordertypes').focus();
                            },500);
                        });
                        return false;
                    }
                }
            } else {
                moo_OrderTypeChanged(selectedOrderType.ot_uuid);
                mooStopLoading();
                swal('Please add the delivery address','You have chosen a delivery method, we need your address','error').then(function() {
                    setTimeout(function () {
                        jQuery('#moo-checkout-form-ordertypes>.moo-checkout-bloc-message .MooSimplButon').focus();
                    },500);
                });
                return false;
            }
        }
    }

    //check the Schedule time
    if(mooGlobalParams.allowScOrders && (form.pickup_hour === null || form.pickup_hour === "Select a time"  ||  form.pickup_hour === "" )) {
        mooStopLoading();
        swal('Please choose a time','','error').then(function() {
            setTimeout(function () {
                jQuery('#moo-checkout-form-orderdate').focus();
            },500);
        });
        return false;
    }

    //check special instructions when they are required special_instructions_required
    if( ! (typeof mooCheckoutOptions.special_instructions_required === 'undefined') ) {

        if(mooCheckoutOptions.special_instructions_required === 'yes'){
            if(typeof form.instructions === 'undefined' || form.instructions === '' ) {
                mooStopLoading();
                swal('Special instructions are required','','error').then(function() {
                    setTimeout(function () {
                        jQuery('#moo-checkout-form-instruction').focus();
                    },500);
                });
                return false;
            }
        }

    }

    //check the payment info with the phone verification
    if(typeof form.payments === 'undefined' || form.payments === '' ) {
        mooStopLoading();
        swal('Please choose your payment method','','error').then(function() {
            setTimeout(function () {
                jQuery('#moo-checkout-form-payments').focus();
            },500);
        });
        return false;
    } else {
        if(form.payments === "cash") {
            if(MooCustomer !== null && MooCustomer[0].phone_verified === '0') {

                if(MooPhoneVerificationActivated) {
                    mooStopLoading();
                    swal('Please verify your phone',"When you choose the cash payment you must verify your phone",'error').then(function() {
                        setTimeout(function () {
                            jQuery('#moo-checkout-form-payments').focus();
                        },500);
                    });

                    var paymentType = jQuery('input[name="payments"]:checked').val();
                    if(paymentType != '') {
                        moo_changePaymentMethod(paymentType);
                    }
                    return false;
                }

            } else {
                if(MooPhoneIsVerified === false) {
                    mooStopLoading();
                    swal('Please verify your phone',"When you choose the cash payment you must verify your phone",'error').then(function() {
                        setTimeout(function () {
                            jQuery('#moo-checkout-form-payments').focus();
                        },500);
                    });

                    var paymentType = jQuery('input[name="payments"]:checked').val();
                    if(paymentType != '') {
                        moo_changePaymentMethod(paymentType);
                    }

                    return false;
                }
            }
            moo_SendForm(form);
        } else {
            if(form.payments === "clover") {
                if(window.cloverCardIsValid){
                    window.clover.createToken()
                        .then( function (response) {
                            if(response.token){
                                form.token = response.token;
                                form.card = response.card;
                                moo_SendForm(form);
                            } else {
                                mooStopLoading();
                                swal('Please verify your card information',"",'error');
                                return false;
                            }
                        });

                } else {
                    mooStopLoading();
                    swal('Please verify your card information',window.cloverCardErrorMsg,'error');
                    return false;
                }
            } else {
                if(form.cardNumber === '' || !regex_exp.credicard.test(form.cardNumber) ) {
                    mooStopLoading();
                    swal('Please enter a valid credit card number',"",'error');
                    return false;
                }
                if(form.cardcvv  === ''  ) {
                    mooStopLoading();
                    swal('Please enter a valid Card CVV',"",'error');
                    return false;
                }
                if(form.zipcode  === ''  ){
                    mooStopLoading();
                    swal('Please enter a valid Zip Code',"",'error');
                    return false;
                }
                if(typeof form.cardNumber !== 'undefined') {
                    form.cardNumber = form.cardNumber.replace(/\s/g, '');
                    form.cardNumber = form.cardNumber.replace(/-/g, '');
                }
                form.cardEncrypted = cryptCardNumber(form.cardNumber);
                form.firstSix = firstSix(form.cardNumber);
                form.lastFour = lastFour(form.cardNumber);
                form.cardNumber = null;

                moo_SendForm(form);

            }
        }
    }
}
function moo_SendForm(form) {
    //Send the form to server
    jQuery
        .post(moo_params.ajaxurl,{'action':'moo_checkout','form':form}, function (data) {
            if(typeof data == 'object') {
                if(data.status == 'APPROVED') {
                    moo_order_approved(data.order);
                } else {
                    if(data.cloverMessage && data.cloverMessage.message){
                        moo_order_notApproved(data.cloverMessage.message);
                    } else {
                        moo_order_notApproved(data.message);
                    }
                }
            } else {
                if(data.indexOf('"status":"APPROVED"') != -1 ) {
                    moo_order_approved('');
                } else {
                    moo_order_notApproved('');
                }
            }
        })
        .fail(function(data) {
            console.log('FAIL');
            console.log(data.responseText);

            if(data.responseText.indexOf('"status":"APPROVED"') != -1 ) {
                moo_order_approved('');
            } else {
                moo_order_notApproved('')
            }

        });
}
function moo_get_form(callback) {
    var form={};
    form._wpnonce               =  jQuery('#_wpnonce').val();
    form.name                   =  jQuery('#MooContactName').val().trim();
    form.email                  =  jQuery('#MooContactEmail').val().trim();
    form.phone                  =  jQuery('#MooContactPhone').val().trim();
    form.cardNumber             =  jQuery('#Moo_cardNumber').val();
    form.expiredDateMonth       =  jQuery('#MooexpiredDateMonth').val();
    form.expiredDateYear        =  jQuery('#MooexpiredDateYear').val();
    form.cardcvv                =  jQuery('#moo_cardcvv').val();
    form.zipcode                =  jQuery('#moo_zipcode').val();
    form.tips                   =  jQuery('#moo_tips').val();
    form.instructions           =  jQuery('#Mooinstructions').val();
    form.pickup_day             =  jQuery('#moo_pickup_day').val();
    form.pickup_hour            =  jQuery('#moo_pickup_hour').val();

    if(document.getElementById('moo-checkout-form-ordertypes')) {
        form.ordertype  =  jQuery('input[name="ordertype"]:checked').val();
    }

    form.payments  =  jQuery('input[name="payments"]:checked').val();
    form.address = MooCustomerChoosenAddress;
    form.deliveryAmount = MooDeliveryfees;
    form.serviceCharges = MooServicefees;
    if(form.cardNumber !== undefined ){
        form.cardNumber = form.cardNumber.trim();
        form.cardNumber = form.cardNumber.replace(/\s+/g,"");
    }
    callback(form);
}
function moo_finalize_order(e) {
    e.preventDefault();
    mooStartLoading();
    jQuery("#moo-checkout .errors-section").html("");
    try {
        if(window.mooCheckout){
            window.mooCheckout.getCheckoutForm();
        } else {
            moo_get_form(moo_verify_form);
        }
    } catch (e) {
        console.log(e);
        window.mooCheckout.stopLoading();
        window.mooCheckout.showErrorAlert("An error has occurred, please try again or contact us");
    }
}
function moo_phone_changed() {
    var phone  =  jQuery('#MooContactPhone').val();
    jQuery('#Moo_PhoneToVerify').val(phone);
}
function moo_phone_to_verif_changed() {
    var phone  =  jQuery('#Moo_PhoneToVerify').val();
    jQuery('#MooContactPhone').val(phone);
    if(MooCustomer != null && MooCustomer[0] != null)
       MooCustomer[0].phone = phone;
    moo_filling_CustomerInformation();
}
function mooCouponApply(e) {
    if(e){
        e.preventDefault();
    }
    var coupon_code = jQuery('#moo_coupon').val();
    if(coupon_code == "") {
        swal({
            title:'Please enter your coupon code',
            timer:5000
        });
    } else {
        swal({
            title:'Checking your coupon...',
            showConfirmButton:false
        });
        jQuery
            .post(moo_params.ajaxurl,{'action':'moo_coupon_apply','moo_coupon_code':coupon_code}, function (data) {
                if(data!==null && data.status==="success") {
                    mooCheckoutOptions.totals = data.total;
                    if(data.type.toUpperCase() === "AMOUNT")
                        swal({ title: "Coupon applied", text: "Success! You have received a discount of $"+data.value,   type: "success",timer:5000, confirmButtonText: "Ok" });
                    else
                        swal({ title: "Coupon applied", text: "Success! You have received a discount of "+data.value+"%",   type: "success",timer:5000, confirmButtonText: "Ok" });

                    jQuery("#moo_remove_coupon_code").html(coupon_code);
                    jQuery("#moo_enter_coupon").hide();
                    jQuery("#moo_remove_coupon").show();

                    mooCheckout.updateTotals(true);
                } else {
                    jQuery("#moo_remove_coupon").hide();
                    jQuery("#moo_enter_coupon").show();
                    swal({ title: "Error", text: data.message,   type: "error",timer:5000,   confirmButtonText: "Try again" });
                }

            })
            .fail(function(data) {
                console.log('FAIL');
                console.log(data.responseText);
                swal({ title: "Error", text:"verify your connection and try again",   type: "error",timer:5000,   confirmButtonText: "Try again" });
            });
    }
}
function mooCouponRemove(e) {
    if(e){
        e.preventDefault();
    }

    swal({
        title:'Removing your coupon....',
        showConfirmButton:false
    });
    jQuery
        .post(moo_params.ajaxurl,{'action':'moo_coupon_remove'}, function (data) {
            if(data.status=="success")
            {
                mooCheckoutOptions.totals = data.total;
                jQuery("#moo_remove_coupon_code").html("");
                jQuery('#moo_coupon').val('');
                jQuery("#moo_enter_coupon").show();
                jQuery("#moo_remove_coupon").hide();
                mooCheckout.updateTotals(true);
            }
            swal.close();
        })
        .fail(function(data) {
            console.log('FAIL');
            console.log(data.responseText);
            swal({ title: "Error", text:"verify your connection and try again",   type: "error",timer:5000,   confirmButtonText: "Try again" });
        });
}
function mooCouponValueChanged(e) {
    if (e.which === 10 || e.which === 13) {
        mooCouponApply(e);
    }
}