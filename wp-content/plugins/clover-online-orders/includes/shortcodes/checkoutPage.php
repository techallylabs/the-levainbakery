<?php


class checkoutPage
{
    /**
     * Display or not the header in checkoutPage
     *  Change this to false if you want hide our header that contain information about teh benifets of using an account
     * @var bool
     */
    private $displayPageHeader = true;

    /**
     * the plugin settings
     * @var array()
     */
    private $pluginSettings;

    /**
     * The model of this plugin (For all interaction with the DATABASE ).
     * @access   private
     * @var      Moo_OnlineOrders_Model    Object of functions that call the Database pr the API.
     */
    private $model;

    /**
     * The model of this plugin (For all interaction with the DATABASE ).
     * @access   private
     * @var Moo_OnlineOrders_CallAPI
     */
    private $api;

    /**
     * use or not alternateNames
     * @var bool
     */
    private $useAlternateNames;

    /**
     * checkoutPage constructor.
     */
    public function __construct() {
        $MooOptions = (array)get_option('moo_settings');
        $this->pluginSettings = $MooOptions;
        $this->model = new moo_OnlineOrders_Model();
        $this->api   = new moo_OnlineOrders_CallAPI();

        if(isset($this->pluginSettings["useAlternateNames"])){
            $this->useAlternateNames = ($this->pluginSettings["useAlternateNames"] !== "disabled");
        } else {
            $this->useAlternateNames = true;
        }

    }

    /**
     * @param $atts
     * @param $content
     * @return string
     */
    public function render($atts, $content)
    {
        $this->enqueueStyles();
        $this->enqueueScripts();

        ob_start();
        $session = MOO_SESSION::instance();
        //check store availibilty

        if(isset($this->pluginSettings['accept_orders']) && $this->pluginSettings['accept_orders'] === "disabled"){
            if(isset($this->pluginSettings["closing_msg"]) && $this->pluginSettings["closing_msg"] !== '') {
                $oppening_msg = '<div class="moo-alert moo-alert-danger" role="alert" id="moo_checkout_msg">'.$this->pluginSettings["closing_msg"].'</div>';
            } else  {
                $oppening_msg = '<div class="moo-alert moo-alert-danger" role="alert" id="moo_checkout_msg">We are currently closed and will open again soon</div>';

            }
            return '<div id="moo_OnlineStoreContainer" >'.$oppening_msg.'</div>';
        }

        //Get blackout status
        $blackoutStatusResponse = $this->api->getBlackoutStatus();
        if(isset($blackoutStatusResponse["status"]) && $blackoutStatusResponse["status"] === "close"){

            if(isset($blackoutStatusResponse["custom_message"]) && !empty($blackoutStatusResponse["custom_message"])){
                $oppening_msg = '<div class="moo-alert moo-alert-danger" role="alert" id="moo_checkout_msg">'.$blackoutStatusResponse["custom_message"].'</div>';
            } else {
                $oppening_msg = '<div class="moo-alert moo-alert-danger" role="alert" id="moo_checkout_msg">We are currently closed and will open again soon</div>';

            }
            return '<div id="moo_OnlineStoreContainer" >'.$oppening_msg.'</div>';
        }

       // $nbOfOrdersPerHour = $this->model->nbOfOrdersPerHour();
       // var_dump($nbOfOrdersPerHour);

        $orderTypes = $this->model->getVisibleOrderTypes();
        if(!is_array($orderTypes)){
            $orderTypes = array();
        }
        // Get ordertypes times
        $counter = $this->model->getOrderTypesWithCustomHours();
        if(isset($counter->nb) && $counter->nb > 0 ) {
            $HoursResponse = $this->api->getMerchantCustomHoursStatus("ordertypes");
            if( $HoursResponse ){
                $merchantCustomHoursStatus = $HoursResponse;
                $merchantCustomHours = array_keys($HoursResponse);
            } else {
                $merchantCustomHoursStatus = array();
                $merchantCustomHours = array();
            }
        } else {
            $merchantCustomHoursStatus = array();
            $merchantCustomHours = array();
        }

        $nbOfOrderTypes = count($orderTypes);
        $nbOfUnvailableOrderTypes = null;
        if(@count($merchantCustomHours) > 0 && $nbOfOrderTypes > 0){
            $nbOfUnvailableOrderTypes = 0;
            for($i=0;$i<$nbOfOrderTypes;$i++) {
                $orderType  = $orderTypes[$i];
                $orderTypes[$i]->available = true;
                if(isset($orderType->custom_hours) && !empty($orderType->custom_hours)) {
                    if(in_array($orderType->custom_hours, $merchantCustomHours)){
                        $isNotAvailable = $merchantCustomHoursStatus[$orderType->custom_hours] === "close";
                        if ($isNotAvailable){
                            //unset($orderTypes[$i]);
                            $orderTypes[$i]->available = false;
                            $nbOfUnvailableOrderTypes++;
                        }
                    }
                }
            }
        }
        /*
        if($nbOfOrderTypes === $nbOfUnvailableOrderTypes ){
            echo '<div id="moo_checkout_msg">This store cannot accept orders right now, please come back later</div>';
            return ob_get_clean();
        }
        */

        //Force disabling payment_creditcard payment method
        if(isset($this->pluginSettings["payment_creditcard"]) && $this->pluginSettings["payment_creditcard"] == "on") {
            $this->pluginSettings["clover_payment_form"] = "on";
            $this->pluginSettings["payment_creditcard"] = "off";
        }


        if(isset($this->pluginSettings["clover_payment_form"]) && $this->pluginSettings["clover_payment_form"] == "on") {

            $cloverPakmsKey = $this->api->getPakmsKey();
            $cloverPakmsKey = json_decode($cloverPakmsKey);
            if($cloverPakmsKey && isset($cloverPakmsKey->status) && $cloverPakmsKey->status == "success") {
                $cloverPakmsKey = $cloverPakmsKey->key;
                //localize clover code
                $cloverCodeExist = true;
            } else {
                $cloverCodeExist = false;
                $cloverPakmsKey = null;
            }
        } else {
            $cloverPakmsKey = null;
        }

        $custom_css = $this->pluginSettings["custom_css"];
        $custom_js  = $this->pluginSettings["custom_js"];


        if(is_double($this->pluginSettings['fixed_delivery']) && $this->pluginSettings['fixed_delivery'] > 0) {
            $fixedDeliveryFees = floatval($this->pluginSettings['fixed_delivery']) * 100;
        } else {
            $fixedDeliveryFees = 0;
        }

        if(isset($this->pluginSettings['service_fees'])  && floatval($this->pluginSettings['service_fees']) > 0) {
            if(isset($this->pluginSettings['service_fees_type']) && $this->pluginSettings['service_fees_type'] === "percent") {
                $serviceFees = floatval($this->pluginSettings['service_fees']);
                $serviceFeesType = "percent";
            } else {
                $serviceFees = floatval($this->pluginSettings['service_fees']) * 100;
                $serviceFeesType = "amount";
            }
        } else {
            $serviceFees = 0;
            $serviceFeesType = "amount";
        }


        $totals = $session->getTotals($fixedDeliveryFees,$serviceFees,$serviceFeesType);

        $merchant_proprites = (json_decode($this->api->getMerchantProprietes())) ;

        //Coupons
        if(!$session->isEmpty("coupon")) {
            $coupon = $session->get("coupon");
            if($coupon['minAmount']>$totals['sub_total'])
                $coupon = null;
        } else {
            $coupon = null;
        }

        //Include custom css
        if($custom_css != null) {
            wp_add_inline_style( "custom-style-cart3", $custom_css );
        }


        if($this->pluginSettings["order_later"] == "on") {
            $inserted_nb_days = $this->pluginSettings["order_later_days"];
            $inserted_nb_mins = $this->pluginSettings["order_later_minutes"];

            $inserted_nb_days_d = $this->pluginSettings["order_later_days_delivery"];
            $inserted_nb_mins_d = $this->pluginSettings["order_later_minutes_delivery"];

            if($inserted_nb_days === "") {
                $nb_days = 4;
            } else {
                $nb_days = intval($inserted_nb_days);
            }

            if($inserted_nb_mins === "") {
                $nb_minutes = 20;
            } else {
                $nb_minutes = intval($inserted_nb_mins);
            }

            if( $inserted_nb_days_d === "") {
                $nb_days_d = 4;
            } else {
                $nb_days_d = intval($inserted_nb_days_d);
            }

            if($inserted_nb_mins_d === "") {
                $nb_minutes_d = 60;
            } else {
                $nb_minutes_d = intval($inserted_nb_mins_d);
            }

        } else {
            $nb_days = 0;
            $nb_minutes = 0;
            $nb_days_d = 0;
            $nb_minutes_d = 0;
        }


        $oppening_status = json_decode($this->api->getOpeningStatus($nb_days,$nb_minutes),true);

        if($nb_days != $nb_days_d || $nb_minutes != $nb_minutes_d)
            $oppening_status_d = json_decode($this->api->getOpeningStatus($nb_days_d,$nb_minutes_d),true);
        else
            $oppening_status_d = $oppening_status;

        $oppening_msg = "";

        if($this->pluginSettings['hours'] != 'all' && $oppening_status["status"] == 'close') {
            if(isset($this->pluginSettings["closing_msg"]) && $this->pluginSettings["closing_msg"] !== '') {
                $oppening_msg = '<div class="moo-alert moo-alert-danger" role="alert" id="moo_checkout_msg">'.$this->pluginSettings["closing_msg"].'</div>';
            } else  {
                if($oppening_status["store_time"] == '')
                    $oppening_msg = '<div class="moo-alert moo-alert-danger" role="alert" id="moo_checkout_msg">Online Ordering Currently Closed'.(($this->pluginSettings['accept_orders_w_closed'] == 'on' )?"<br/><p style='color: #006b00'>Order in Advance Available</p>":"").'</div>';
                else
                    $oppening_msg = '<div class="moo-alert moo-alert-danger" role="alert" id="moo_checkout_msg"><strong>Today\'s Online Ordering Hours</strong> <br/> '.$oppening_status["store_time"].'<br/>Online Ordering Currently Closed'.(($this->pluginSettings['accept_orders_w_closed'] == 'on' )?"<br/><p style='color: #006b00'>Order in Advance Available</p>":"").'</div>';
            }
        }

        //Adding asap to pickup time
        if(isset($oppening_status["pickup_time"])) {
            if(isset($this->pluginSettings['order_later_asap_for_p']) && $this->pluginSettings['order_later_asap_for_p'] == 'on') {
                if(isset($oppening_status["pickup_time"]["Today"]))
                    array_unshift($oppening_status["pickup_time"]["Today"],'ASAP');
            }
            if(isset($oppening_status["pickup_time"]["Today"]))
                array_unshift($oppening_status["pickup_time"]["Today"],'Select a time');

        }

        if(isset($oppening_status_d["pickup_time"])) {
            if(isset($this->pluginSettings['order_later_asap_for_d']) && $this->pluginSettings['order_later_asap_for_d'] == 'on')
            {
                if(isset($oppening_status_d["pickup_time"]["Today"]))
                    array_unshift($oppening_status_d["pickup_time"]["Today"],'ASAP');
            }
            if(isset($oppening_status_d["pickup_time"]["Today"]))
                array_unshift($oppening_status_d["pickup_time"]["Today"],'Select a time');

        }

        if($this->pluginSettings['hours'] != 'all' && $this->pluginSettings['accept_orders_w_closed'] != 'on' && $oppening_msg != "") {
            echo '<div id="moo_OnlineStoreContainer">'.$oppening_msg.'</div>';
            return ob_get_clean();
        }

        //show or hide the choose time section
        if(isset($this->pluginSettings['order_later']) && $this->pluginSettings['order_later'] == 'on'){
            if(is_array($oppening_status["pickup_time"]) && @count($oppening_status["pickup_time"])>0){
                $showTimeSection = true;
            } else {
                if(isset($this->pluginSettings['order_later_mandatory']) && $this->pluginSettings['order_later_mandatory'] === "on"){
                    $showTimeSection = true;
                } else {
                    $showTimeSection = false;
                }
            }
        } else {
            $showTimeSection = false;
        }



        $merchant_address =  $this->api->getMerchantAddress();
        $store_page_id     = $this->pluginSettings['store_page'];
        $cart_page_id     = $this->pluginSettings['cart_page'];
        $checkout_page_id     = $this->pluginSettings['checkout_page'];

        $store_page_url    =  get_page_link($store_page_id);
        $cart_page_url    =  get_page_link($cart_page_id);
        $checkout_page_url    =  get_page_link($checkout_page_id);

        if(isset($this->pluginSettings['thanks_page_wp']) && !empty($this->pluginSettings['thanks_page_wp'])){
            $this->pluginSettings['thanks_page'] = get_page_link($this->pluginSettings['thanks_page_wp']);
        }

        if(!isset($this->pluginSettings['save_cards'])){
            $this->pluginSettings['save_cards'] = null;
        }
        if(!isset($this->pluginSettings['save_cards_fees'])){
            $this->pluginSettings['save_cards_fees'] = null;
        }
        if(!isset($this->pluginSettings['delivery_errorMsg']) || empty($this->pluginSettings['delivery_errorMsg'])){
            $this->pluginSettings['delivery_errorMsg'] = "Sorry, zone not supported. We do not deliver to this address at this time";
        }
        $mooCheckoutJsOptions = array(
                'moo_RestUrl' =>  get_rest_url(),
                "moo_OrderTypes"=>$orderTypes,
                "totals"=>$totals,
                "moo_Key"=>array(),
                "moo_thanks_page"=>$this->pluginSettings['thanks_page'],
                "moo_cash_upon_delivery"=>$this->pluginSettings['payment_cash_delivery'],
                "moo_cash_in_store"=>$this->pluginSettings['payment_cash'],
                "moo_pay_online"=>$this->pluginSettings['payment_creditcard'],
                "moo_pickup_time"=>$oppening_status["pickup_time"],
                "moo_pickup_time_for_delivery"=>$oppening_status_d["pickup_time"],
                "moo_fb_app_id"=>$this->pluginSettings['fb_appid'],
                "moo_scp"=>$this->pluginSettings['scp'],
                "moo_use_sms_verification"=>$this->pluginSettings['use_sms_verification'],
                "moo_checkout_login"=>$this->pluginSettings['checkout_login'],
                "moo_save_cards"=>$this->pluginSettings['save_cards'],
                "moo_save_cards_fees"=>$this->pluginSettings['save_cards_fees'],
                "moo_clover_payment_form"=>$this->pluginSettings['clover_payment_form'],
                "moo_clover_key"=>$cloverPakmsKey,
                "special_instructions_required"=>$this->pluginSettings['special_instructions_required'],
        );
        $mooDeliveryJsOptions = array(
                "moo_merchantLat"=>$this->pluginSettings['lat'],
                "moo_merchantLng"=>$this->pluginSettings['lng'],
                "moo_merchantAddress"=>$merchant_address,
                "zones"=>$this->pluginSettings['zones_json'],
                "other_zone_fee"=>$this->pluginSettings['other_zones_delivery'],
                "free_amount"=>$this->pluginSettings['free_delivery'],
                "fixed_amount"=>$this->pluginSettings['fixed_delivery'],
                "errorMsg"=>$this->pluginSettings['delivery_errorMsg']
        );
        wp_localize_script("custom-script-checkout", "mooCheckoutOptions",$mooCheckoutJsOptions);
        wp_localize_script("custom-script-checkout", "mooDeliveryOptions",$mooDeliveryJsOptions);


        if($totals === false || !isset($totals['nb_items']) || $totals['nb_items'] < 1){
            return $this->cartIsEmpty();
        };

        if((isset($_GET['logout']) && $_GET['logout'] == true)) {
            $session->delete("moo_customer_token");
            wp_redirect ( $checkout_page_url );
        }
        if($this->pluginSettings['checkout_login'] == "disabled") {
            $session->delete("moo_customer_token");
        }
        ?>

        <div id="moo_OnlineStoreContainer">
            <div class="moo-row" id="moo-checkout">
                <div class="errors-section"></div>
                <?php echo $oppening_msg; ?>
                <div id="moo_merchantmap"></div>
                <!--            login               -->
                <div id="moo-login-form" <?php if((!$session->isEmpty("moo_customer_token")) || $this->pluginSettings['checkout_login']=="disabled") echo 'style="display:none;"'?> class="moo-col-md-12 ">
                    <?php if($this->displayPageHeader){ ?>
                        <div class="moo-row login-top-section" tabindex="-1">
                            <div class="login-header" >
                                Why create a  <a href="https://www.smartonlineorder.com" target="_blank">Smart Online Order</a> account?
                            </div>
                            <div class="moo-col-md-6">
                                <ul>
                                    <li>Save your address</li>
                                    <li>Faster Checkout!</li>
                                </ul>
                            </div>
                            <div class="moo-col-md-6">
                                <ul>
                                    <li>View your past orders</li>
                                    <li>Get exclusive deals and coupons</li>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="moo-col-md-6" tabindex="0">
                        <div class="moo-row login-social-section">
                            <?php if(isset($this->pluginSettings['fb_appid']) && $this->pluginSettings['fb_appid']!=""){ ?>
                                <p>
                                    <strong>Sign in</strong> with your social account
                                    <br />
                                    <small>No posts on your behalf, promise!</small>
                                </p>
                                <div class="moo-row">
                                    <div class="moo-col-xs-12 moo-col-sm-6 moo-col-md-7 moo-col-md-offset-3 moo-col-sm-offset-3" >
                                        <a href="#" class="moo-btn moo-btn-lg moo-btn-primary moo-btn-block" onclick="moo_loginViaFacebook(event)" style="margin-top: 12px;" tabindex="0" aria-label="Sign in with your Facebook account">Facebook</a>
                                    </div>
                                    <div class="moo-col-xs-12 moo-col-sm-12 moo-col-md-7 moo-col-md-offset-3" tabindex="0">
                                        <div class="login-or">
                                            <hr class="hr-or">
                                            <span class="span-or">or</span>
                                        </div>
                                        <a role="button" class="moo-btn moo-btn-danger" onclick="moo_loginAsguest(event)" tabindex="0">
                                            Continue As Guest
                                        </a>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <p>
                                    Don't want an account?
                                    <br />
                                    <small>You can checkout without registering</small>
                                </p>
                                <div class="moo-row">
                                    <div class="moo-col-xs-12 moo-col-sm-6 moo-col-md-7 moo-col-md-offset-3 moo-col-sm-offset-3">
                                        <a  role="button" tabindex="0" href="#" class="moo-btn moo-btn-lg moo-btn-primary moo-btn-block" onclick="moo_loginAsguest(event)" style="margin-top: 12px;"> Continue As Guest</a>
                                    </div>
                                    <div class="moo-col-xs-12 moo-col-sm-12 moo-col-md-9 moo-col-md-offset-2">
                                        <div class="login-or">
                                            <hr class="hr-or">
                                            <span class="span-or">or</span>
                                        </div>
                                        <a  class="moo-btn moo-btn-danger" onclick="moo_show_sigupform(event)">
                                            Create An Account
                                        </a>
                                    </div>
                                </div>
                            <?php  } ?>
                        </div>
                        <div class="login-separator moo-hidden-xs moo-hidden-sm">
                            <div class="separator">
                                <span>or</span>
                            </div>
                        </div>
                    </div>
                    <div class="moo-col-md-6" tabindex="0" >
                        <form action="post" onsubmit="moo_login(event)" aria-label="Sign in with your account">
                            <div class="form-group">
                                <label for="inputEmail">Email</label>
                                <input type="text" id="inputEmail" class="moo-form-control" autocomplete="email" aria-label="your email">
                            </div>
                            <div class="moo-form-group">
                                <label for="inputPassword">Password</label>
                                <input type="password"  id="inputPassword" class="moo-form-control" autocomplete="current-password" aria-label="your password">
                                <a class="pull-right" href="#" onclick="moo_show_forgotpasswordform(event)" aria-label="Click here if you forgotten your password">Forgot password?</a>
                            </div>
                            <button id="mooButonLogin" class="moo-btn" onclick="moo_login(event)" aria-label="log in">
                                Log In
                            </button>
                            <p style="padding: 10px;"> Don't have an account<a  href="#" onclick="moo_show_sigupform(event)" aria-label="Don't have an account Sign-up"> Sign-up</a> </p>
                        </form>
                    </div>
                </div>
                <!--            Register            -->
                <div id="moo-signing-form" class="moo-col-md-12">
                    <div class="moo-col-md-8 moo-col-md-offset-2">
                        <form action="post" onsubmit="moo_signin(event)">
                            <div class="moo-form-group">
                                <label for="inputMooFullName">Full Name</label>
                                <input type="text" class="moo-form-control" id="inputMooFullName" autocomplete="fullName">
                            </div>

                            <div class="moo-form-group">
                                <label for="inputMooEmail">Email</label>
                                <input type="text" class="moo-form-control" id="inputMooEmail" autocomplete="email">
                            </div>
                            <div class="moo-form-group">
                                <label for="inputMooPhone">Phone</label>
                                <input type="text" class="moo-form-control" id="inputMooPhone" autocomplete="phone">
                            </div>
                            <div class="moo-form-group">
                                <label for="inputMooPassword">Password</label>
                                <input type="password" class="moo-form-control" id="inputMooPassword" autocomplete="current-password">
                            </div>
                            <p>
                                By clicking the button below you agree to our <a href="https://www.zaytechapps.com/zaytech-eula/" target="_blank">Terms Of Service</a>
                            </p>
                            <button class="moo-btn moo-btn-primary" onclick="moo_signin(event)">
                                Submit
                            </button>
                            <p style="padding: 10px;"> Have an account already?<a  href="#" onclick="moo_show_loginform()"> Click here</a> </p>
                        </form>
                    </div>

                </div>
                <!--            Reset Password      -->
                <div   id="moo-forgotpassword-form" class="moo-col-md-12">
                    <div class="moo-col-md-8 moo-col-md-offset-2">
                        <form action="post" onsubmit="moo_resetpassword(event)">
                            <div class="moo-form-group">
                                <label for="inputEmail4Reset">Email</label>
                                <input type="text" class="moo-form-control" id="inputEmail4Reset">
                            </div>
                            <button class="moo-btn moo-btn-primary" onclick="moo_resetpassword(event)">
                                Reset
                            </button>
                            <button class="moo-btn moo-btn-default" onclick="moo_cancel_resetpassword(event)">
                                Cancel
                            </button>
                        </form>
                    </div>
                </div>
                <!--            Choose address      -->
                <div id="moo-chooseaddress-form" class="moo-col-md-12">
                    <div id="moo-chooseaddress-formContent" class="moo-row">
                    </div>
                    <div class="MooAddressBtnActions">
                        <a class="MooSimplButon" href="#" onclick="moo_show_form_adding_address()">Add Another Address</a>
                        <a class="MooSimplButon" href="#" onclick="moo_pickup_the_order(event)">Click here if this Order is for Pick Up</a>
                    </div>
                    <a class="moologoutlabel" href="?logout=true">Logout</a>
                </div>
                <!--            Add new address      -->
                <div id="moo-addaddress-form" class="moo-col-md-12">
                    <form method="post" onsubmit="moo_addAddress(event)">
                        <h1 tabindex="0" aria-level="1">Add new Address to your account</h1>
                        <div class="moo-col-md-8 moo-col-md-offset-2">
                            <div class="mooFormAddingAddress">
                                <div class="moo-form-group">
                                    <label for="inp utMooAddress">Address</label>
                                    <input type="text" class="moo-form-control" id="inputMooAddress">
                                </div>
                                <div class="moo-form-group">
                                    <label for="inputMooAddress">Suite / Apt #</label>
                                    <input type="text" class="moo-form-control" id="inputMooAddress2">
                                </div>
                                <div class="moo-form-group">
                                    <label for="inputMooCity">City</label>
                                    <input type="text" class="moo-form-control" id="inputMooCity">
                                </div>
                                <div class="moo-form-group">
                                    <label for="inputMooState">State</label>
                                    <input type="text" class="moo-form-control" id="inputMooState">
                                </div>
                                <div class="moo-form-group">
                                    <label for="inputMooZipcode">Zip code</label>
                                    <input type="text" class="moo-form-control" id="inputMooZipcode">
                                </div>
                                <p class="moo-centred">
                                    <button href="#" class="moo-btn moo-btn-warning" onclick="moo_ConfirmAddressOnMap(event)">Next</button>
                                </p>
                            </div>
                            <div class="mooFormConfirmingAddress">
                                <div id="MooMapAddingAddress" tabindex="-1">
                                    <p style="margin-top: 150px;">Loading the MAP...</p>
                                </div>
                                <input type="hidden" class="moo-form-control" id="inputMooLat">
                                <input type="hidden" class="moo-form-control" id="inputMooLng">
                                <div class="form-group">
                                    <button id="mooButonAddAddress" onclick="moo_addAddress(event)" aria-label="Confirm and add address">
                                        Confirm and add address
                                    </button>
                                    <button id="mooButonChangeAddress" onclick="moo_changeAddress(event)" aria-label="Change address">
                                        Change address
                                    </button>
                                </div>
                            </div>
                            <p style="padding: 10px;">If you want to skip this step and add your address later <a role="button" href="#" onclick="moo_pickup_the_order(event)" style="color:blue"> Click here</a> </p>
                        </div>
                    </form>
                </div>
                <!--            Checkout form        -->
                <div id="moo-checkout-form" class="moo-col-md-12" <?php if($this->pluginSettings['checkout_login']=="disabled") echo 'style="display:block;"'?>>
                    <form action="#" method="post" onsubmit="moo_finalize_order(event)">
                        <!--            Checkout form - Informaton section       -->
                        <div class="moo-col-md-7 moo-checkout-form-leftside" tabindex="0" aria-label="the checkout form">
                            <div id="moo-checkout-form-customer" tabindex="0" aria-label="your information">
                                <div class="moo-checkout-bloc-title moo-checkoutText-contact">
                                    contact
                                    <span class="moo-checkout-edit-icon" onclick="moo_checkout_edit_contact()">
                                        <img src="<?php echo  plugin_dir_url(dirname(__FILE__))."../public/img/edit-pen.png"?>" alt="edit">
                                    </span>
                                </div>
                                <div class="moo-checkout-bloc-content">
                                    <div id="moo-checkout-contact-content">
                                    </div>
                                    <div id="moo-checkout-contact-form">
                                        <div class="moo-row">
                                            <div class="moo-form-group">
                                                <label for="MooContactName" class="moo-checkoutText-fullName">Full Name:*</label>
                                                <input class="moo-form-control" name="name" id="MooContactName" autocomplete="name">
                                            </div>
                                        </div>
                                        <div class="moo-row">
                                            <div class="moo-form-group">
                                                <label for="MooContactEmail" class="moo-checkoutText-email">Email:*</label>
                                                <input class="moo-form-control" id="MooContactEmail" autocomplete="email">
                                            </div>
                                        </div>
                                        <div class="moo-row">
                                            <div class="moo-form-group">
                                                <label for="MooContactPhone" class="moo-checkoutText-phoneNumber">Phone number:*</label>
                                                <input class="moo-form-control" name="phone" id="MooContactPhone" onchange="moo_phone_changed()" autocomplete="phone">
                                            </div>
                                        </div>
                                        <?php wp_nonce_field('moo-checkout-form');?>
                                    </div>
                                </div>
                            </div>
                            <div class="moo_chekout_border_bottom"></div>
                            <?php if(count($orderTypes)>0){?>
                                <div id="moo-checkout-form-ordertypes" tabindex="0" aria-label="the ordering method">
                                    <div class="moo-checkout-bloc-title moo-checkoutText-orderingMethod">
                                        ORDERING METHOD*
                                    </div>
                                    <div class="moo-checkout-bloc-content">
                                        <?php
                                        foreach ($orderTypes as $ot) {
                                            if(isset($ot->available) && $ot->available === false){
                                                echo '<div class="moo-checkout-form-ordertypes-option">';
                                                echo '<input class="moo-checkout-form-ordertypes-input" type="radio" name="ordertype" value="'.$ot->ot_uuid.'" id="moo-checkout-form-ordertypes-'.$ot->ot_uuid.'" disabled>';
                                                echo '<label for="moo-checkout-form-ordertypes-'.$ot->ot_uuid.'" style="display: inline;margin-left:15px;font-size: 16px; vertical-align: sub;">'.stripslashes($ot->label).' ( '.stripslashes($ot->custom_message).' )</label></div>';

                                            } else {
                                                echo '<div class="moo-checkout-form-ordertypes-option">';
                                                echo '<input class="moo-checkout-form-ordertypes-input" type="radio" name="ordertype" value="'.$ot->ot_uuid.'" id="moo-checkout-form-ordertypes-'.$ot->ot_uuid.'">';
                                                echo '<label for="moo-checkout-form-ordertypes-'.$ot->ot_uuid.'" style="display: inline;margin-left:15px;font-size: 16px; vertical-align: sub;">'.stripslashes($ot->label).'</label></div>';
                                            }
                                          }
                                        ?>
                                    </div>
                                    <div class="moo-checkout-bloc-message" id="moo-checkout-form-ordertypes-message">
                                    </div>
                                </div>
                                <div class="moo_chekout_border_bottom"></div>
                            <?php  } ?>
                            <?php
                            if($showTimeSection){?>
                                <div id="moo-checkout-form-orderdate" tabindex="0" aria-label="Choose a time if you want schedule the order">
                                    <div class="moo-checkout-bloc-title moo-checkoutText-ChooseATime">
                                        CHOOSE A TIME
                                    </div>
                                    <div class="moo-checkout-bloc-content">
                                        <div class="moo-row">
                                            <div class="moo-col-md-6">
                                                <div class="moo-form-group">
                                                    <select class="moo-form-control" name="moo_pickup_day" id="moo_pickup_day" onchange="moo_pickup_day_changed(this)">
                                                        <?php
                                                        foreach ($oppening_status["pickup_time"] as $key=>$val) {
                                                            echo '<option value="'.$key.'">'.$key.'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="moo-col-md-6">
                                                <div class="moo-form-group">
                                                    <select class="moo-form-control" name="moo_pickup_hour" id="moo_pickup_hour" >
                                                        <?php
                                                        foreach ($oppening_status["pickup_time"] as $key=>$val) {
                                                            foreach ($val as $h)
                                                                echo '<option value="'.$h.'">'.$h.'</option>';
                                                            break;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if($oppening_status["store_time"] != '') { ?>
                                            <div class="moo-row">
                                                <div class="moo-col-md-12">
                                                    Today's Online Ordering Hours: <?php echo $oppening_status["store_time"]  ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="moo_chekout_border_bottom"></div>
                            <?php } ?>
                            <div id="moo-checkout-form-payments" tabindex="0" aria-label="the payments method">
                                <div class="moo-checkout-bloc-title moo-checkoutText-payment" >
                                    PAYMENT  <?php if($this->pluginSettings['payment_cash'] == 'on' || $this->pluginSettings['payment_cash_delivery'] == 'on'){ echo 'METHOD';}?>*
                                </div>
                                <div class="moo-checkout-bloc-content">

                                    <?php
                                    if (isset($cloverCodeExist) && $cloverCodeExist && isset($this->pluginSettings['clover_payment_form']) && $this->pluginSettings['clover_payment_form'] == 'on'){ ?>
                                        <div class="moo-checkout-form-payments-option">
                                            <input class="moo-checkout-form-payments-input" type="radio" name="payments" value="clover" id="moo-checkout-form-payments-clover">
                                            <label for="moo-checkout-form-payments-clover" style="display: inline;margin-left:15px;font-size: 16px; vertical-align: sub;">Pay now with Credit Card (Secured By Clover)</label>
                                        </div>
                                    <?php }
                                    if (isset($this->pluginSettings['payment_creditcard']) && $this->pluginSettings['payment_creditcard'] == 'on'){ ?>
                                        <div class="moo-checkout-form-payments-option">
                                            <input class="moo-checkout-form-payments-input" type="radio" name="payments" value="creditcard" id="moo-checkout-form-payments-creditcard">
                                            <label for="moo-checkout-form-payments-creditcard" style="display: inline;margin-left:15px;font-size: 16px; vertical-align: sub;">Pay now with Credit Card</label>
                                        </div>
                                    <?php } ?>
                                    <?php if($this->pluginSettings['payment_cash'] == 'on' || $this->pluginSettings['payment_cash_delivery'] == 'on'){ ?>
                                        <div class="moo-checkout-form-payments-option moo-checkout-form-payments-cash-container">
                                            <input class="moo-checkout-form-payments-input" type="radio" name="payments" value="cash" id="moo-checkout-form-payments-cash">
                                            <label for="moo-checkout-form-payments-cash" style="display: inline;margin-left:15px;font-size: 16px; vertical-align: sub;" id="moo-checkout-form-payincash-label">Pay at Location</label>
                                        </div>
                                    <?php } ?>
                                    <?php if(isset($this->pluginSettings['payment_creditcard']) && $this->pluginSettings['payment_creditcard'] == 'on' && $this->pluginSettings['scp'] !=="on"){ ?>
                                        <div id="moo_creditCardPanel">
                                            <div class="moo-row">
                                                <div class="moo-col-md-12">
                                                    <div class="moo-form-group">
                                                        <label for="Moo_cardNumber" class="control-label moo-checkoutText-cardNumber">Card number</label>
                                                        <input class="moo-form-control" name="cardNumber" id="Moo_cardNumber" placeholder="Debit/Credit Card Number" pattern="[0-9]{13,16}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="moo-row">
                                                <div class="moo-col-md-6">
                                                    <div class="moo-form-group">
                                                        <select name="expiredDateMonth" id="MooexpiredDateMonth" class="moo-form-control">
                                                            <option value="01">Jan (01)</option>
                                                            <option value="02">Feb (02)</option>
                                                            <option value="03">Mar (03)</option>
                                                            <option value="04">Apr (04)</option>
                                                            <option value="05">May (05)</option>
                                                            <option value="06">June(06)</option>
                                                            <option value="07">July(07)</option>
                                                            <option value="08">Aug (08)</option>
                                                            <option value="09">Sep (09)</option>
                                                            <option value="10">Oct (10)</option>
                                                            <option value="11">Nov (11)</option>
                                                            <option value="12">Dec (12)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="moo-col-md-6">
                                                    <div class="moo-form-group">

                                                        <select name="expiredDateYear"id="MooexpiredDateYear"  class="moo-form-control">
                                                            <?php
                                                            $current_year = date("Y");
                                                            if($current_year < 2018 )$current_year = 2020;
                                                            for($i=$current_year;$i<$current_year+20;$i++)
                                                                echo '<option value="'.$i.'">'.$i.'</option>';
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="moo-row">
                                                <div class="moo-col-md-12">
                                                    <div class="moo-form-group">
                                                        <label for="moo_cardcvv" class="moo-control-label moo-checkoutText-cardCvv">Card CVV</label>
                                                        <input class="moo-form-control" name="cvv" id="moo_cardcvv" placeholder="Security Code">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="moo-row">
                                                <div class="moo-col-md-12">
                                                    <div class="moo-form-group">
                                                        <label for="moo_zipcode" class="moo-control-label moo-checkoutText-zipCode">Zip Code</label>
                                                        <input class="moo-form-control" name="zipcode" id="moo_zipcode" placeholder="Zip code">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                    if(isset($cloverCodeExist) && $cloverCodeExist  && isset($this->pluginSettings['clover_payment_form']) && $this->pluginSettings['clover_payment_form'] == 'on'){
                                        $this->cloverCardSection();
                                    }
                                    if($this->pluginSettings['payment_cash'] == 'on' || $this->pluginSettings['payment_cash_delivery'] == 'on'){ ?>
                                        <div id="moo_cashPanel">
                                            <div class="moo-row"  id="moo_verifPhone_verified">
                                                <img src="<?php echo  plugin_dir_url(dirname(__FILE__))."../public/img/check.png"?>" width="60px" style="display: inline-block;">
                                                <p>Your phone number has been verified <br/>Please finalize your order below</p>
                                            </div>
                                            <div class="moo-row" id="moo_verifPhone_sending">
                                                <div class="moo-form-group moo-form-inline">
                                                    <label for="Moo_PhoneToVerify moo-checkoutText-yourPhone">Your phone</label>
                                                    <input class="moo-form-control" id="Moo_PhoneToVerify" style="margin-bottom: 10px" onchange="moo_phone_to_verif_changed()"/>
                                                    <a class="moo-btn moo-btn-primary" href="#" style="margin-bottom: 10px" onclick="moo_verifyPhone(event)">Verify via SMS</a>
                                                    <label for="Moo_PhoneToVerify" class="error" style="display: none;"></label>
                                                </div>
                                                <p>
                                                    We will send a verification code via SMS to number above
                                                </p>
                                            </div>
                                            <div class="moo-row" id="moo_verifPhone_verificatonCode">
                                                <p style='font-size:18px;color:green'>
                                                    Please enter the verification that was sent to your phone, if you didn't receive a code,
                                                    <a href="#" onclick="moo_verifyCodeTryAgain(event)"> click here to try again</a>
                                                </p>
                                                <div class="moo-form-group moo-form-inline">
                                                    <input class="moo-form-control" id="Moo_VerificationCode" style="margin-bottom: 10px" autocomplete="off" />
                                                    <a class="moo-btn moo-btn-primary" href="#" style="margin-bottom: 10px" onclick="moo_verifyCode(event)">Submit</a>
                                                    <label for="Moo_VerificationCode" class="error" style="display: none;"></label>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="moo_chekout_border_bottom"></div>
                            <!-- Save payment method -->
                            <div id="moo-checkout-form-savecard">
                                <div class="moo-checkout-bloc-content">
                                    <div class="moo-checkout-form-savecard-option">
                                        <input class="moo-checkout-form-savecard-input" type="checkbox" name="moo_save_card" id="moo-checkout-form-savecard" checked>
                                        <label for="moo-checkout-form-savecard" style="display: inline;margin-left:15px">Use this card for future purchase</label>
                                    </div>
                                </div>
                            </div>
                            <div class="moo_chekout_border_bottom"></div>

                            <?php
                                if($this->pluginSettings['tips'] == 'enabled' && isset($merchant_proprites->tipsEnabled) && $merchant_proprites->tipsEnabled) {
                                        $this->tipsSection();
                                        $this->borderBottom();
                                }
                                if($this->pluginSettings['use_special_instructions']=="enabled") {
                                    ?>
                                    <div id="moo-checkout-form-instruction">
                                        <div class="moo-checkout-bloc-title moo-checkoutText-instructions">
                                            <label for="Mooinstructions">Special instructions</label>
                                        </div>
                                        <div class="moo-checkout-bloc-content">
                                            <?php
                                                if(isset($this->pluginSettings['text_under_special_instructions']) && $this->pluginSettings['text_under_special_instructions']!=='') {
                                                    echo '<div class="moo-special-instruction-title">'.$this->pluginSettings['text_under_special_instructions'].'</div>';
                                                }
                                                if(isset($this->pluginSettings['special_instructions_required']) && $this->pluginSettings['special_instructions_required']==='yes') {
                                                    echo '<textarea class="moo-form-control" cols="100%" rows="5" id="Mooinstructions" required></textarea>';
                                                } else {
                                                    echo '<textarea class="moo-form-control" cols="100%" rows="5" id="Mooinstructions"></textarea>';
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            //Check if coupons are enabled
                            if($this->pluginSettings['use_coupons']=="enabled") {
                                ?>
                                <div class="moo_chekout_border_bottom"></div>
                                <div id="moo-checkout-form-coupon">
                                    <div class="moo-checkout-bloc-title moo-checkoutText-couponCode">
                                        <label for="moo_coupon">Coupon code</label>
                                    </div>
                                    <div class="moo-checkout-bloc-content" id="moo_enter_coupon" style="<?php if($coupon !== null) echo 'display:none';?>">
                                        <div class="moo-col-md-8">
                                            <div class="moo-form-group">
                                                <input onkeypress="mooCouponValueChanged(event)" type="text" class="moo-form-control" id="moo_coupon" style="background-color: #ffffff">
                                            </div>
                                        </div>
                                        <div class="moo-col-md-4">
                                            <div class="moo-form-group">
                                                <a href="#" class="moo-btn moo-btn-primary" onclick="mooCouponApply(event)" style="height: 40px;line-height: 24px;">Apply</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="moo-checkout-bloc-content" id="moo_remove_coupon" style="<?php if($coupon === null) echo 'display:none'; ?>">
                                        <div class="moo-col-md-8">
                                            <div class="moo-form-group">
                                                <p style="font-size: 20px" id="moo_remove_coupon_code"><?php if($coupon != null) echo $coupon['code'];?></p>
                                            </div>
                                        </div>
                                        <div class="moo-col-md-4">
                                            <div class="moo-form-group">
                                                <a href="#" class="moo-btn moo-btn-primary" onclick="mooCouponRemove(event)">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php  }?>
                        </div>
                        <!--            Checkout form - Cart section       -->
                        <div class="moo-col-md-5 moo-checkout-cart">
                            <div class="moo-shopping-cart MooCartInCheckout" tabindex="0" aria-label="the cart">
                                <div class="moo-column-labels-checkout">
                                    <label class="moo-product-quantity moo-product-quantity-checkou moo-checkoutText-qtyt" style="width: 20%">Qty</label>
                                    <label class="moo-product-details moo-product-details-checkout moo-checkoutText-product" style="width: 60%">Product</label>
                                    <label class="moo-product-price moo-product-price-checkout moo-checkoutText-price" style="width: 20%">Price</label>
                                </div>
                                <?php foreach ($session->get("items") as $key=>$line) {
                                    $modifiers_price = 0;
                                    $item_name = "";
                                    if($this->useAlternateNames && isset($line['item']->alternate_name) && $line['item']->alternate_name!==""){
                                        $item_name=stripslashes($line['item']->alternate_name);
                                    } else {
                                        $item_name=stripslashes($line['item']->name);
                                    }
                                    ?>
                                    <div class="moo-product" tabindex="0" aria-label="<?php echo $line['quantity']." of ".$line['item']->name."" ?>">
                                        <div class="moo-product-quantity" style="width: 20%">
                                            <strong><?php echo $line['quantity']?></strong>
                                        </div>
                                        <div class="moo-product-details moo-product-details-checkout" style="width: 60%">
                                            <div class="moo-product-title"><strong><?php echo $item_name; ?></strong></div>
                                            <p class="moo-product-description">
                                                <?php
                                                foreach($line['modifiers'] as $modifier){
                                                    $modifier_name = "";
                                                    if($this->useAlternateNames && isset($modifier["alternate_name"]) && $modifier["alternate_name"]!==""){
                                                        $modifier_name =stripslashes($modifier["alternate_name"]);
                                                    } else {
                                                        $modifier_name =stripslashes($modifier["name"]);
                                                    }
                                                    if(isset($modifier['qty']) && intval($modifier['qty'])>0) {
                                                        echo '<small tabindex="0">'.$modifier['qty'].'x ';
                                                        $modifiers_price += $modifier['price']*$modifier['qty'];
                                                    } else {
                                                        echo '<small tabindex="0">1x ';
                                                        $modifiers_price += $modifier['price'];
                                                    }

                                                    if($modifier['price']>0)
                                                        echo ''.$modifier_name.'- $'.number_format(($modifier['price']/100),2)."</small><br/>";
                                                    else
                                                        echo ''.$modifier_name."</small><br/>";

                                                }
                                                if($line['special_ins'] != "")
                                                    echo '<span tabindex="0" aria-label="your special instructions">SI:<span><span tabindex="0"> '.$line['special_ins']."<span>";
                                                ?>
                                            </p>
                                        </div>
                                        <?php $line_price = $line['item']->price+$modifiers_price;?>
                                        <div class="moo-product-line-price" tabindex="0"><strong>$<?php echo number_format(($line_price*$line['quantity']/100),2)?></strong></div>
                                    </div>
                                <?php } ?>

                                <div class="moo-totals" style="padding-right: 10px;">
                                    <div class="moo-totals-item">
                                        <label class="moo-checkoutText-subtotal"  tabindex="0">Subtotal</label>
                                        <div class="moo-totals-value" id="moo-cart-subtotal"  tabindex="0">
                                            <?php echo number_format(($totals['sub_total']/100),2)?>
                                        </div>
                                    </div>
                                    <?php if($this->pluginSettings['use_coupons']=="enabled"){ //check if coupons are enabled ?>
                                        <div class="moo-totals-item" id="MooCouponInTotalsSection" style="<?php if($totals['coupon_value'] === 0) echo 'display:none;';?>;color: green;">
                                            <label id="mooCouponName" tabindex="0"><?php echo $totals['coupon_name'];?></label>
                                            <div class="moo-totals-value" id="mooCouponValue" tabindex="0">
                                                <?php  echo '- $'.number_format($totals['coupon_value']/100,2); ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="moo-totals-item">
                                        <label class="moo-checkoutText-tax"  tabindex="0" >Tax</label>
                                        <div class="moo-totals-value" id="moo-cart-tax"  tabindex="0">
                                            <?php
                                            if($totals['coupon_value'] === 0) {
                                                    echo  '$'.number_format($totals['total_of_taxes_without_discounts']/100,2);
                                            } else {
                                                echo  '$'.number_format($totals['total_of_taxes']/100,2);
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="moo-totals-item" id="MooDeliveryfeesInTotalsSection">
                                        <label class="moo-checkoutText-deliveryFees"  tabindex="0">
                                            <?php echo ($this->pluginSettings["delivery_fees_name"] === "")?"Delivery Charge":$this->pluginSettings["delivery_fees_name"];?>
                                        </label>
                                        <div class="moo-totals-value" id="moo-cart-delivery-fee"  tabindex="0">
                                            <?php
                                                echo '$'.number_format(($totals['delivery_charges']/100),2);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="moo-totals-item" id="MooServiceChargesInTotalsSection"  style="<?php if($totals['services_fees'] <= 0) echo 'display:none;';?>">
                                        <label id="MooServiceChargesName" tabindex="0">
                                            <?php
                                                if( isset($this->pluginSettings['service_fees_name']) && !empty($this->pluginSettings['service_fees_name'])){
                                                    echo $this->pluginSettings['service_fees_name'];
                                                } else {
                                                    echo "Service Fees";
                                                }
                                            ?>
                                        </label>
                                        <div class="moo-totals-value" id="moo-cart-service-fee"  tabindex="0">
                                            <?php
                                                echo '$'.number_format($totals['services_fees']/100,2);
                                            ?>
                                        </div>
                                    </div>
                                    <?php if($this->pluginSettings['tips']=='enabled'){?>
                                        <div class="moo-totals-item" id="MooTipsInTotalsSection">
                                            <label class="moo-checkoutText-tipAmount" tabindex="0" >Tip</label>
                                            <div class="moo-totals-value" id="moo-cart-tip" tabindex="0">
                                                $0.00
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="moo-totals-item moo-totals-item-total" style="font-weight: 700;" >
                                        <label class="moo-checkoutText-grandTotal" tabindex="0" >Grand Total</label>
                                        <div class="moo-totals-value" id="moo-cart-total" tabindex="0" >
                                            <?php
                                                if($totals['coupon_value'] === 0) {
                                                    $grandTotal = $totals['total'] + $totals['services_fees'] + $totals['delivery_charges'];
                                                } else {
                                                    $grandTotal = $totals['total_without_discounts'] + $totals['services_fees'] + $totals['delivery_charges'] - $totals['coupon_value'];
                                                }
                                                echo '$'.number_format($grandTotal/100,2);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--   Checkout form - Link section     -->
                        <div style="text-align: center;text-decoration: none;">
                            <a href="<?php echo $cart_page_url?>" class="moo-checkoutText-updateCart">Update cart</a><a href="<?php echo $store_page_url?>" class="moo-checkoutText-continueShopping">Continue shopping</a>
                        </div>
                        <!--            Checkout form - Buttons section       -->
                        <div id="moo-checkout-form-btnActions">
                            <div id="moo_checkout_loading" style="display: none; width: 100%;text-align: center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="44px" height="44px" viewBox="0 0 100 100"
                                     preserveAspectRatio="xMidYMid" class="uil-default">
                                    <rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect>
                                    <rect x="46.5" y="40" width="7" height="20" rx="5" ry="5" fill="#00b2ff"
                                          transform="rotate(0 50 50) translate(0 -30)">
                                        <animate attributeName="opacity" from="1" to="0" dur="1s" begin="0s"
                                                 repeatCount="indefinite"></animate>
                                    </rect>
                                    <rect x="46.5" y="40" width="7" height="20" rx="5" ry="5" fill="#00b2ff"
                                          transform="rotate(30 50 50) translate(0 -30)">
                                        <animate attributeName="opacity" from="1" to="0" dur="1s"
                                                 begin="0.08333333333333333s" repeatCount="indefinite"></animate>
                                    </rect>
                                    <rect x="46.5" y="40" width="7" height="20" rx="5" ry="5" fill="#00b2ff"
                                          transform="rotate(60 50 50) translate(0 -30)">
                                        <animate attributeName="opacity" from="1" to="0" dur="1s"
                                                 begin="0.16666666666666666s" repeatCount="indefinite"></animate>
                                    </rect>
                                    <rect x="46.5" y="40" width="7" height="20" rx="5" ry="5" fill="#00b2ff"
                                          transform="rotate(90 50 50) translate(0 -30)">
                                        <animate attributeName="opacity" from="1" to="0" dur="1s" begin="0.25s"
                                                 repeatCount="indefinite"></animate>
                                    </rect>
                                    <rect x="46.5" y="40" width="7" height="20" rx="5" ry="5" fill="#00b2ff"
                                          transform="rotate(120 50 50) translate(0 -30)">
                                        <animate attributeName="opacity" from="1" to="0" dur="1s"
                                                 begin="0.3333333333333333s" repeatCount="indefinite"></animate>
                                    </rect>
                                    <rect x="46.5" y="40" width="7" height="20" rx="5" ry="5" fill="#00b2ff"
                                          transform="rotate(150 50 50) translate(0 -30)">
                                        <animate attributeName="opacity" from="1" to="0" dur="1s"
                                                 begin="0.4166666666666667s" repeatCount="indefinite"></animate>
                                    </rect>
                                    <rect x="46.5" y="40" width="7" height="20" rx="5" ry="5" fill="#00b2ff"
                                          transform="rotate(180 50 50) translate(0 -30)">
                                        <animate attributeName="opacity" from="1" to="0" dur="1s" begin="0.5s"
                                                 repeatCount="indefinite"></animate>
                                    </rect>
                                    <rect x="46.5" y="40" width="7" height="20" rx="5" ry="5" fill="#00b2ff"
                                          transform="rotate(210 50 50) translate(0 -30)">
                                        <animate attributeName="opacity" from="1" to="0" dur="1s"
                                                 begin="0.5833333333333334s" repeatCount="indefinite"></animate>
                                    </rect>
                                    <rect x="46.5" y="40" width="7" height="20" rx="5" ry="5" fill="#00b2ff"
                                          transform="rotate(240 50 50) translate(0 -30)">
                                        <animate attributeName="opacity" from="1" to="0" dur="1s"
                                                 begin="0.6666666666666666s" repeatCount="indefinite"></animate>
                                    </rect>
                                    <rect x="46.5" y="40" width="7" height="20" rx="5" ry="5" fill="#00b2ff"
                                          transform="rotate(270 50 50) translate(0 -30)">
                                        <animate attributeName="opacity" from="1" to="0" dur="1s" begin="0.75s"
                                                 repeatCount="indefinite"></animate>
                                    </rect>
                                    <rect x="46.5" y="40" width="7" height="20" rx="5" ry="5" fill="#00b2ff"
                                          transform="rotate(300 50 50) translate(0 -30)">
                                        <animate attributeName="opacity" from="1" to="0" dur="1s"
                                                 begin="0.8333333333333334s" repeatCount="indefinite"></animate>
                                    </rect>
                                    <rect x="46.5" y="40" width="7" height="20" rx="5" ry="5" fill="#00b2ff"
                                          transform="rotate(330 50 50) translate(0 -30)">
                                        <animate attributeName="opacity" from="1" to="0" dur="1s"
                                                 begin="0.9166666666666666s" repeatCount="indefinite"></animate>
                                    </rect>
                                </svg>
                            </div>
                            <button type="submit"  id="moo_btn_submit_order" onclick="moo_finalize_order(event)" class="moo-btn moo-btn-primary moo-finalize-order-btn moo-checkoutText-finalizeOrder">
                                FINALIZE ORDER
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
        if($custom_js != null)
            echo '<script type="text/javascript">'.$custom_js.'</script>';
        if(!$session->isEmpty("moo_customer_token"))
            echo '<script type="text/javascript"> jQuery( document ).ready(function($) { moo_show_chooseaddressform() });</script>';

        return ob_get_clean();
    }

    private function enqueueStyles(){

        wp_enqueue_style( 'moo-font-awesome' );
        wp_enqueue_style( 'custom-style-cart3');

    }
    private function enqueueScripts(){
        wp_enqueue_script( 'moo-google-map' );
        wp_enqueue_script( 'moo-clover' );
        wp_enqueue_script( 'moo-google-map');
        wp_enqueue_script( 'display-merchant-map');
        wp_enqueue_script( 'custom-script-checkout');
        wp_enqueue_script( 'moo-forge' );
    }
    private function cartIsEmpty() {
        $message =  '<div class="moo_emptycart"><p>Your cart is empty</p><span><a class="moo-btn moo-btn-default" href="'.get_page_link($this->pluginSettings['store_page']).'" style="margin-top: 30px;">Back to Main Menu</a></span></div>';
        return $message;
    }
    private function tipsSection(){
        $html = <<<HTML
        <div id="moo-checkout-form-tips">
            <div class="moo-checkout-bloc-title moo-checkoutText-tip">
                <label for="moo_tips">tip</label>
            </div>
            <div class="moo-checkout-bloc-content">
                <div class="moo-row"  style="margin-top: 13px;">
                    <div class="moo-col-md-6">
                        <div class="moo-form-group">
                            <select class="moo-form-control" name="moo_tips_select" id="moo_tips_select" onchange="moo_tips_select_changed()" aria-label="list of tips">
                                <option value="cash">Add a tip to this order</option>
HTML;
        if(isset($this->pluginSettings["tips_default"]) && !empty($this->pluginSettings["tips_default"])){
            $defaultTips = floatval(trim($this->pluginSettings["tips_default"]));
        } else {
            $defaultTips = null;
        }
        if(isset($this->pluginSettings["tips_selection"]) && !empty($this->pluginSettings["tips_selection"])){
            $vals = explode(",", $this->pluginSettings["tips_selection"]);
            if (is_array($vals) && count($vals) > 0){
                foreach ($vals as $k=>$v){
                    if(floatval(trim($v)) === $defaultTips)  {
                        $html.= '<option value="'.floatval(trim($v)).'" selected>'. floatval(trim($v)) .'%</option>';
                    } else {
                        $html.= '<option value="'.floatval(trim($v)).'">'. floatval(trim($v)) .'%</option>';
                    }
                }
            }
        } else {
            $html.= '<option value="10" '.(($defaultTips == 10)?"selected":"").'>10%</option>';
            $html.= '<option value="15" '.(($defaultTips == 15)?"selected":"").'>15%</option>';
            $html.= '<option value="20" '.(($defaultTips == 20)?"selected":"").'>20%</option>';
            $html.= '<option value="25" '.(($defaultTips == 25)?"selected":"").'>25%</option>';
        }
        $html .= <<<HTML
                            <option value="other">Custom $</option>
                        </select>
                        </div>
                    </div>
                    <div class="moo-col-md-6">
                        <div class="moo-form-group">
                            <input class="moo-form-control" name="tip" id="moo_tips" value="0" onchange="moo_tips_amount_changed()">
                        </div>
                    </div>
                </div>
            </div>
        </div>
HTML;
        $html = apply_filters( 'moo_filter_checkout_tips', $html);
         echo  $html;
    }
    private function cloverCardSection(){
        $html = <<<HTML
        <div id="moo-cloverCreditCardPanel">
            <input type="hidden" name="cloverToken" id="moo-CloverToken">
            <div class="moo-row">
                <div class="moo-col-md-12">
                    <div class="moo-form-group">
                        <div class="moo-form-control" id="moo_CloverCardNumber"></div>
                        <div class="card-number-error">
                            <div class="clover-error"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="moo-row">
                <div class="moo-col-md-6">
                    <div class="moo-form-group">
                        <div class="moo-form-control" id="moo_CloverCardDate"></div>  
                         <div class="date-error">
                            <div class="clover-error"></div>
                        </div>                                                  
                    </div>
                </div>
                <div class="moo-col-md-6">
                    <div class="moo-form-group">
                        <div class="moo-form-control" id="moo_CloverCardCvv"></div>
                         <div class="cvv-error">
                            <div class="clover-error"></div>
                        </div>                                                  
                    </div>
                </div>
            </div>
            <div class="moo-row">
                <div class="moo-col-md-12">
                    <div class="moo-form-group">
                        <div class="moo-form-control" id="moo_CloverCardZip"></div>
                         <div class="zip-error">
                            <div class="clover-error"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clover-errors"></div>
        </div>
HTML;
        $html = apply_filters( 'moo_filter_checkout_cloverCard', $html);
         echo  $html;
    }
    private function borderBottom() {
        echo '<div class="moo_chekout_border_bottom"></div>';
    }
}
