/* Set rates + misc */
var fadeTime = 300;

//this function not used see moo_recalculateCart
function moo_updateCartTotal() {
        jQuery(".moo_cart_total > td:last").html("Calculating...");

        jQuery.post(moo_params.ajaxurl,{'action':'moo_cart_getTotal'}, function (data) {
                if(data !== false) {
                    if(data.total === 0 || Object.keys(MOO_CART).length === 0 ){
                        jQuery(".moo_cart_total").remove();
                        return;
                    }
                    html ="<tr class='moo_cart_total'>";
                    html +="<td colspan='1' style='text-align: right;'>Subtotal:</td>";
                    html +="<td colspan='3'>$"+mooformatCentPrice(data.sub_total)+"</td>";
                    html +="</tr>";

                    if(data.coupon_value > 0){
                        html +="<tr  class='moo_cart_total'>";
                        html +="<td colspan='1' style='text-align: right; color: green'>"+data.coupon_name+":</td>";
                        html +="<td colspan='3'>- $"+mooformatCentPrice(data.coupon_value)+"</td>";
                        html +="</tr>";
                    }

                    html +="<tr  class='moo_cart_total'>";
                    html +="<td colspan='1' style='text-align: right;'>Tax:</td>";
                    html +="<td colspan='3'>$"+mooformatCentPrice(data.total_of_taxes)+"</td>";
                    html +="</tr>";

                    html +="<tr  class='moo_cart_total'>";
                    html +="<td colspan='1' style='text-align: right;'>Total:</td>";
                    html +="<td colspan='3'>$"+mooformatCentPrice(data.total)+"</td>";
                    html +="</tr>";

                    jQuery(".moo_cart_total").remove();
                    jQuery(".moo_cart .CartContent>table").append(html);
                } else {
                    moo_updateCart();
                }
        });

}

function moo_cart_DeleteItem(item) {
    //send delete query to server
    jQuery.post(moo_params.ajaxurl,{'action':'moo_deleteItemFromcart',"item":item}, function (data) {
        if(data.status != "success"){
            moo_updateCart();
        };
    });

    jQuery("#moo_cart_line_"+item).remove();
    jQuery(".MooLineModifier4_"+item).remove();

    delete(MOO_CART[item]);

    if(Object.keys(MOO_CART).length>0)
    {
        moo_updateCartTotal();
    }
    else
    {
        jQuery(".moo_cart .CartContent>table>tbody").html('<tr><td colspan="4">Your Cart is empty !!</td></tr>');
        jQuery(".moo_cart_total").remove();
    }


}
function moo_emptyCart(event) {
    event.preventDefault();
    //send delete query to server
    swal("please wait..");
    jQuery.post(moo_params.ajaxurl,{'action':'moo_emptycart'}, function (data) {
        if(data.status === "success"){
            var cart = '<div class="moo_emptycart"><p>Your cart is empty</p><span><a class="moo-btn moo-btn-default" href="'+moo_params.storePage+'" style="margin-top: 30px;">Back to Main Menu</a></span></div>';
            jQuery(".moo-shopping-cart").html(cart);
            swal.close()
        } else {
            swal("Error, please try again, or refresh the page");
        }
    });
}

function moo_addModifiers(item_name,item_uuid) {
    jQuery.post(moo_params.ajaxurl,{'action':'moo_check_item_modifiers',"item":item_uuid}, function (data) {
        if(data.status == 'success')
        {
            var required_modifiers_groups = "";
            required_modifiers_groups = data.uuids.split(';');
            var selected_modifies = jQuery("#moo_form_modifiers").serializeArray();
            var Mgroups = {};
            var Modifiers = [];
            var qte = jQuery('#moo_popup_quantity').val();
            var special_instruction = jQuery('#moo_popup_si').val();
            if(typeof jQuery.magnificPopup !== 'undefined' ) {
                jQuery.magnificPopup.close();
            }


            if(selected_modifies.length>0)
                for(m in selected_modifies)
                {
                    var modifier = selected_modifies[m];
                    if(typeof modifier == "object" )
                        if(modifier.value=='on'){
                            var name = modifier.name; //the format is : moo_modifiers['item','modifierGroup','Modifier']
                            var string = name.split(','); // the new format is a table
                            var item = string[0].substr(15);  // 15 is the length of moo_modifiers['
                            item = item.substr(0,item.length-1); // remove the last '
                            var modifierGroup = string[1].substr(1);
                            modifierGroup = modifierGroup.substr(0,modifierGroup.length-1);
                            var modif = string[2].substr(1);
                            modif = modif.substr(0,modif.length-2);
                            if(item == '' || modifierGroup == '' || modif == '' || item != item_uuid) continue;
                            if(typeof Mgroups[modifierGroup] === 'undefined') Mgroups[modifierGroup] = 1;
                            else Mgroups[modifierGroup] +=1;
                            var modifier = {
                                "item":item,
                                "modifier": modif,
                                "modifierGroup": modifierGroup
                            };
                            Modifiers.push(modifier);
                        }
                        else
                        {
                            var name = modifier.name; //the format is : moo_modifiers['item','modifierGroup']
                            var string = name.split(','); // the new format is a table
                            var item = string[0].substr(15);  // 15 is the length of moo_modifiers['
                            item = item.substr(0,item.length-1); // remove the last '
                            var modifierGroup = string[1].substr(1);
                            modifierGroup = modifierGroup.substr(0,modifierGroup.length-2);
                            var modif = modifier.value;
                            if(item == '' || modifierGroup == '' || modif == '' || item != item_uuid) continue;
                            if(typeof Mgroups[modifierGroup] === 'undefined') Mgroups[modifierGroup] = 1;
                            else Mgroups[modifierGroup] +=1;
                            var modifier = {
                                "item":item,
                                "modifier": modif,
                                "modifierGroup": modifierGroup
                            };
                            Modifiers.push(modifier);
                        }
                }

            var flag = false;
            if(Object.keys(Mgroups).length == 0 && Object.keys(required_modifiers_groups).length <= 1) {
                if(typeof jQuery.magnificPopup !== 'undefined' ) {
                    jQuery.magnificPopup.close();
                }
                moo_cartv3_addtocart(item_uuid,item_name);
                return false;
            }

            /* verify if required modifier Groups are chooses */
            for(mg in required_modifiers_groups){
                var element = required_modifiers_groups[mg];
                if(typeof element == "string" && element !="")
                {
                    if(Object.keys(Mgroups).indexOf(element) == -1)
                    {
                        swal({ title: "Error!", text: "You didn't choose all required options",   type: "error",   confirmButtonText: "Try again" });
                        flag=true;
                        return;
                    }
                }
            }

            /* verify the min and max in modifier Group */
            var compteur = 0;
            for(mg in Mgroups){
                jQuery.post(moo_params.ajaxurl,{'action':'moo_modifiergroup_getlimits',"modifierGroup":mg}, function (data) {
                    if(data.status == 'success' )
                    {
                        /* If the min is not null then we display a message if the custmet not choose the minimum */
                        if(data.min != null && data.min != 0 && Mgroups[data.uuid] < data.min) {
                            var error_msg = "Please choose "+data.min+" options in "+data.name;
                            swal({ title: "Error!", text: error_msg,   type: "error",   confirmButtonText: "Try again" });
                            flag=true;
                        }
                        if(data.max!= null && data.max != 0 && Mgroups[data.uuid] > data.max) {
                            var error_msg = "You can't choose more than "+ data.max+" options in "+data.name;
                            swal({ title: "Error!", text: error_msg,   type: "error",   confirmButtonText: "Try again" });
                            flag=true;
                        }
                    }
                    else
                    {
                        flag = true;
                    }
                }).done(function () {
                    compteur++;
                    if(compteur == Object.keys(Mgroups).length)
                    {
                        if(!flag)
                        {
                            //toastr.success(item_name+' added to cart');
                            swal({ title: item_name, text: 'Added to cart',   type: "success",   confirmButtonText: "OK" });
                            //send the request to the server
                            jQuery.post(moo_params.ajaxurl,{'action':'moo_modifier_add',"modifiers":Modifiers}, function (data) {
                                if(data.status == 'success' )
                                {
                                    item_uuid = data.uuid;
                                    return true;
                                }
                            }).done(function () {
                                moo_updateQuantityAndSI(item_uuid,qte,special_instruction);
                            })
                        }
                        else
                        {
                            return 'error';
                        }

                    }
                });
            }
        }
    });
}
function moo_addItemWithModifiersToCart(event,item_uuid,item_name,item_price) {
    event.preventDefault();
    moo_addModifiers(item_name,item_uuid);
}
function moo_updateQuantityAndSI(item_uuid,qte,special_instruction) {
    // var qte = jQuery('#moo_popup_quantity').val();
    // var special_instruction = jQuery('#moo_popup_si').val();

    if(qte>1)
        jQuery.post(moo_params.ajaxurl,{'action':'moo_update_qte',"item":item_uuid,"qte":qte});
    if(special_instruction!="")
        jQuery.post(moo_params.ajaxurl,{'action':'moo_update_special_ins',"item":item_uuid,"special_ins":special_instruction});
}

/* Recalculate cart */
function moo_recalculateCart() {
    jQuery('#moo-cart-total').html("Updating the total...");
    jQuery.post(moo_params.ajaxurl,{'action':'moo_cart_getTotal'}, function (totals) {
        if( totals !== false ) {
            jQuery('.moo-totals-value').fadeOut(fadeTime, function() {
                jQuery('#moo-cart-subtotal').html('$'+mooformatCentPrice(totals.sub_total));
                jQuery('#moo-cart-tax').html('$'+mooformatCentPrice(totals.total_of_taxes));
                jQuery('#moo-cart-total').html('$'+mooformatCentPrice(totals.total));
                if(totals.total === 0){
                    jQuery('.moo-checkout').fadeOut(fadeTime);
                }else{
                    jQuery('.moo-checkout').fadeIn(fadeTime);
                }
                jQuery('.moo-totals-value').fadeIn(fadeTime);
            });
        } else {
            var html = '<div class="moo_emptycart"><p>Your cart is empty</p><span><a class="moo-btn moo-btn-default" href="'+moo_params.storePage+'" style="margin-top: 30px;">Back to Main Menu</a></span></div>';

            jQuery('.moo-shopping-cart').html(html);
        }
    });

}
/* Update quantity */
function moo_updateQuantity(quantityInput,item_uuid) {
    /* Calculate line price */
    var productRow = jQuery(quantityInput).parent().parent();
    var price = parseFloat(productRow.children('.moo-product-price').text());
    var quantity = jQuery(quantityInput).val();
    var linePrice = price * quantity;

    /* Update the quantity in the session */
    jQuery.post(moo_params.ajaxurl,{'action':'moo_update_qte',"item":item_uuid,"qte":quantity}, function (data) {
        if(data.status != 'success')
        {
            swal({ title: "Connection error", text: 'Please refresh the page and try again',   type: "error",   confirmButtonText: "Try again " });

        }
    });
    /* Update line price display and recalc cart totals */
    productRow.children('.moo-product-line-price').each(function () {
        jQuery(this).fadeOut(fadeTime, function() {
            jQuery(this).text(formatPrice(linePrice.toFixed(2)));
            moo_recalculateCart();
            jQuery(this).fadeIn(fadeTime);
        });
    });
}
/* Remove item from cart */
function moo_removeItem(removeButton,item_uuid) {
    /* remove from session */
    jQuery.post(moo_params.ajaxurl,{'action':'moo_deleteItemFromcart',"item":item_uuid}, function (data) {
        if(data.status != "success"){
            swal({ title: "Connection error", text: 'Please refresh the page and try again',   type: "error",   confirmButtonText: "Try again " });
        };
    });
    /* Remove row from DOM and recalc cart total */
    var productRow = jQuery(removeButton).parent().parent();
    productRow.slideUp(fadeTime, function() {
        productRow.remove();
        moo_recalculateCart();
    });
}

function formatPrice (p) {
    return p.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}