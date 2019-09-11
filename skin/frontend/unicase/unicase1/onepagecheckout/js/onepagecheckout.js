/* Login popup class */
var OnePageCheckoutLoginPopup = Class.create({
    initialize: function(options) {
        this.options = options;
        this.popup_container = $('onepagecheckout-login-popup');
        this.popup_link = $('onepagecheckout-login-link');
        this.popup = null;
        this.createPopup();
        this.mode = 'login';

        this.forgot_password_link = $('onepagecheckout-forgot-password-link');
        this.forgot_password_container = $('onepagecheckout-login-popup-contents-forgot');
        this.forgot_password_loading = $('onepagecheckout-forgot-loading');
        this.forgot_password_error = $('onepagecheckout-forgot-error');
        this.forgot_password_success = $('onepagecheckout-forgot-success');
        this.forgot_password_button = $('onepagecheckout-forgot-button');
        this.forgot_password_table = $('onepagecheckout-forgot-table');

        this.login_link = $('onepagecheckout-return-login-link');
        this.login_container = $('onepagecheckout-login-popup-contents-login');
        this.login_table = $('onepagecheckout-login-table');
        this.login_error = $('onepagecheckout-login-error');
        this.login_loading = $('onepagecheckout-login-loading');
        this.login_button = $('onepagecheckout-login-button');
        this.login_form = $('onepagecheckout-login-form');
        this.login_username = $('id_onepagecheckout_username');

        /* Bindings for the enter button */
        this.keypress_handler = function(e) {
            if(e.keyCode == Event.KEY_RETURN) {
                e.preventDefault();

                if(this.mode == 'login') {
                    this.login_handler();
                } else if(this.mode == 'forgot') {
                    this.forgot_password_handler();
                }
            }
        }.bind(this);

        this.login_handler = function(e) {

            var parameters = this.login_form.serialize(true);
            var url = this.options.login_url;
            this.showLoginLoading();

            new Ajax.Request(url, {
                method: 'post',
                parameters: parameters,
                onSuccess: function(transport) {
                    var result = transport.responseText.evalJSON();
                    if(result.success) {
                        window.location = window.location;
                    } else {
                        this.showLoginError(result.error);
                    }
                }.bind(this)
            });
        };

        this.forgot_password_handler = function(e) {
            var email = $('id_onepagecheckout_email').getValue();

            if(email == '') {
                alert(this.options.translations.invalid_email);
                return;
            }

            this.showForgotPasswordLoading();

            /* Prepare AJAX call */
            var url = this.options.forgot_password_url;

            new Ajax.Request(url, {
                method: 'post',
                parameters: { email: email },
                onSuccess: function(transport) {
                    var result = transport.responseText.evalJSON();

                    if(result.success) {
                        /* Show success message */
                        this.showForgotPasswordSuccess();

                        /* Pre-set username to simplify login */
                        this.login_username.setValue(email);
                    } else {
                        /* Show error message */
                        this.showForgotPasswordError();
                    }

                }.bind(this)
            });
        };

        this.bindEventHandlers();
    },

    bindEventHandlers: function() {
        /* First bind the link for opening the popup */
        if(this.popup_link){
            this.popup_link.observe('click', function(e) {
                e.preventDefault();
                this.popup.open();
            }.bind(this));
        }

        /* Link for closing the popup */
        if(this.popup_container){
            this.popup_container.select('p.close a').invoke(
                'observe', 'click', function(e) {
                this.popup.close();
            }.bind(this));
        }

        /* Link to switch between states */
        if(this.login_link){
            this.login_link.observe('click', function(e) {
                e.preventDefault();
                this.forgot_password_container.hide();
                this.login_container.show();
                this.mode = 'login';
            }.bind(this));
        }

        /* Link to switch between states */
        if(this.forgot_password_link){
            this.forgot_password_link.observe('click', function(e) {
                e.preventDefault();
                this.login_container.hide();
                this.forgot_password_container.show();
                this.mode = 'forgot';
            }.bind(this));
        }

        /* Now bind the submit button for logging in */
        if(this.login_button){
            this.login_button.observe(
                'click', this.login_handler.bind(this));
        }

        /* Now bind the submit button for forgotten password */
        if(this.forgot_password_button){
            this.forgot_password_button.observe('click',
                this.forgot_password_handler.bind(this));
        }

        /* Handle return keypress when open */
        if(this.popup){
            this.popup.observe('afterOpen', function(e) {
                document.observe('keypress', this.keypress_handler);
            }.bind(this));

            this.popup.observe('afterClose', function(e) {
                this.resetPopup();
                document.stopObserving('keypress', this.keypress_handler);
            }.bind(this));
        }

    },

    resetPopup: function() {
        this.login_table.show();
        this.forgot_password_table.show();

        this.login_loading.hide();
        this.forgot_password_loading.hide();

        this.login_error.hide();
        this.forgot_password_error.hide();

        this.login_container.show();
        this.forgot_password_container.hide();
    },

    showLoginError: function(error) {
        this.login_table.show();
        this.login_error.show();
        this.login_loading.hide();

        if(error) {
            this.login_error.update(error);
        }
    },

    showLoginLoading: function() {
        this.login_table.hide();
        this.login_loading.show();
        this.login_error.hide();
    },

    showForgotPasswordSuccess: function() {
        this.forgot_password_error.hide();
        this.forgot_password_loading.hide();
        this.forgot_password_table.hide();
        this.forgot_password_success.show();
    },

    showForgotPasswordError: function() {
        this.forgot_password_error.show();
        this.forgot_password_error.update(
            this.options.translations.email_not_found),

        this.forgot_password_table.show();
        this.forgot_password_loading.hide();
    },

    showForgotPasswordLoading: function() {
        this.forgot_password_loading.show();
        this.forgot_password_error.hide();
        this.forgot_password_table.hide();
    },

    show: function(){
        this.popup.open();
    },

    createPopup: function() {
        this.popup = new Control.Modal(this.popup_container, {
            overlayOpacity: 0.65,
            fade: true,
            fadeDuration: 0.3
        });
    }
});

function $RF(el, radioGroup) {
    if($(el).type && $(el).type.toLowerCase() == 'radio') {
        var radioGroup = $(el).name;
        var el = $(el).form;
    } else if ($(el).tagName.toLowerCase() != 'form') {
        return false;
    }

    var checked = $(el).getInputs('radio', radioGroup).find(
            function(re) {return re.checked;}
    );
    return (checked) ? $F(checked) : null;
}

function $RFF(el, radioGroup) {
    if($(el).type && $(el).type.toLowerCase() == 'radio') {
        var radioGroup = $(el).name;
        var el = $(el).form;
    } else if ($(el).tagName.toLowerCase() != 'form') {
        return false;
    }
    return $(el).getInputs('radio', radioGroup).first();
}

function get_totals_element()
{
    // Search for OSC summary element
    var search_osc = $$('div.onepagecheckout-summary');

    if(search_osc.length > 0)    {
        return search_osc[0];
    }

    var search_cart = $$('div.shopping-cart-totals');

    if(search_cart.length > 0)    {
        return search_cart[0];
    }

    if($('shopping-cart-totals-table'))    {
        return $('shopping-cart-totals-table');
    }

}

function get_save_methods_function(url, update_payments)
{

    if(typeof update_payments == 'undefined')    {
        var update_payments = false;
    }
    return function(e)    {

        if(typeof e != 'undefined')    {
            var element = e.element();

            if(element.name != 'shipping_method')    {
                update_payments = false;
            }
        }

        var form = $('onepagecheckout-form');
        var shipping_method = $RF(form, 'shipping_method');
        var payment_method = $RF(form, 'payment[method]');

        var totals = get_totals_element();
        totals.update('<div class="loading-ajax">&nbsp;</div>');

        if(update_payments)    {
            var payment_methods = $$('div.payment-methods')[0];
            payment_methods.update('<div class="loading-ajax">&nbsp;</div>');
        }

        var parameters = {
                shipping_method: shipping_method,
                payment_method: payment_method
        }

        /* Find payment parameters and include */
        var items = $$('input[name^=payment]').concat($$('select[name^=payment]'));
        var names = items.pluck('name');
        var values = items.pluck('value');

        for(var x=0; x < names.length; x++)    {
            if(names[x] != 'payment[method]')    {
                parameters[names[x]] = values[x];
            }
        }

        new Ajax.Request(url, {
            method: 'post',
            onSuccess: function(transport)    {
            if(transport.status == 200)    {
                var data = transport.responseText.evalJSON();

                totals.update(data.summary);

                if(update_payments)    {

                    payment_methods.replace(data.payment_method);

                    $$('div.payment-methods input[name^=payment\[method\]]').invoke('observe', 'click', get_separate_save_methods_function(url));

                    $$('div.payment-methods input[name^=payment\[method\]]').invoke('observe', 'click', function() {
                        $$('div.onepagecheckout-payment-method-error').each(function(item) {
                            new Effect.Fade(item);
                        });
                    });

                    if($RF(form, 'payment[method]') != null)    {
                        try    {
                            var payment_method = $RF(form, 'payment[method]');
                            $('container_payment_method_' + payment_method).show();
                            $('payment_form_' + payment_method).show();
                        } catch(err)    {

                        }
                    }

                }
            }
        },
        parameters: parameters
        });
    }
}

function exclude_unchecked_checkboxes(data)
{
    var items = [];
    for(var x=0; x < data.length; x++)    {
        var item = data[x];
        if(item.type == 'checkbox')    {
            if(item.checked)    {
                items.push(item);
            }
        }
        else    {
            items.push(item);
        }
    }

    return items;
}

function get_save_billing_function(url, set_methods_url, update_payments, triggered)
{
    if(typeof update_payments == 'undefined')    {
        var update_payments = false;
    }

    if(typeof triggered == 'undefined')    {
        var triggered = true;
    }

    if(!triggered){
        return function(){return;};
    }

    return function()    {
        var form = $('onepagecheckout-form');
        var items = exclude_unchecked_checkboxes($$('input[name^=billing]').concat($$('select[name^=billing]')));
        var names = items.pluck('name');
        var values = items.pluck('value');
        var parameters = {
                shipping_method: $RF(form, 'shipping_method')
        };


        var street_count = 0;
        for(var x=0; x < names.length; x++)    {
            if(names[x] != 'payment[method]')    {

                var current_name = names[x];

                if(names[x] == 'billing[street][]')    {
                    current_name = 'billing[street][' + street_count + ']';
                    street_count = street_count + 1;
                }

                parameters[current_name] = values[x];
            }
        }

        var use_for_shipping = $('billing:use_for_shipping_yes');




        if(use_for_shipping && use_for_shipping.getValue() != '1')    {
            var items = $$('input[name^=shipping]').concat($$('select[name^=shipping]'));
            var shipping_names = items.pluck('name');
            var shipping_values = items.pluck('value');
            var shipping_parameters = {};
            var street_count = 0;

            for(var x=0; x < shipping_names.length; x++)    {
                if(shipping_names[x] != 'shipping_method')    {
                    var current_name = shipping_names[x];
                    if(shipping_names[x] == 'shipping[street][]')    {
                        current_name = 'shipping[street][' + street_count + ']';
                        street_count = street_count + 1;
                    }

                    parameters[current_name] = shipping_values[x];
                }
            }
        }

        var shipment_methods = $$('div.onepagecheckout-shipping-method-block')[0];
        var shipment_methods_found = false;

        if(typeof shipment_methods != 'undefined') {
            shipment_methods_found = true;
        }

        if(shipment_methods_found)  {
            shipment_methods.update('<div class="loading-ajax">&nbsp;</div>');
        }

        var payment_method = $RF(form, 'payment[method]');
        parameters['payment_method'] = payment_method;
        parameters['payment[method]'] = payment_method;

        var payment_methods = $$('div.payment-methods')[0];
        payment_methods.update('<div class="loading-ajax">&nbsp;</div>');

        var totals = get_totals_element();
        totals.update('<div class="loading-ajax">&nbsp;</div>');


        new Ajax.Request(url, {
            method: 'post',
            onSuccess: function(transport)    {
            if(transport.status == 200)    {

                var data = transport.responseText.evalJSON();

                // Update shipment methods
                if(shipment_methods_found)  {
                    shipment_methods.update(data.shipping_method);
                }
                payment_methods.replace(data.payment_method);
                totals.update(data.summary);


            }
        },
        onComplete: function(transport){
            if(transport.status == 200)    {
                if(shipment_methods_found)  {
                    $$('dl.shipment-methods input').invoke('observe', 'click', get_separate_save_methods_function(set_methods_url, update_payments));
                    $$('dl.shipment-methods input').invoke('observe', 'click', function() {
                        $$('div.onepagecheckout-shipment-method-error').each(function(item) {
                            new Effect.Fade(item);
                        });
                    });
                }

                $$('div.payment-methods input[name^=payment\[method\]]').invoke('observe', 'click', get_separate_save_methods_function(set_methods_url));

                $$('div.payment-methods input[name^=payment\[method\]]').invoke('observe', 'click', function() {
                    $$('div.onepagecheckout-payment-method-error').each(function(item) {
                        new Effect.Fade(item);
                    });
                });

                if($RF(form, 'payment[method]') != null)    {
                    try    {
                        var payment_method = $RF(form, 'payment[method]');
                        $('container_payment_method_' + payment_method).show();
                        $('payment_form_' + payment_method).show();
                    } catch(err)    {

                    }
                }
            }
        },
        parameters: parameters
        });

    }
}

function get_separate_save_methods_function(url, update_payments)
{
    if(typeof update_payments == 'undefined')    {
        var update_payments = false;
    }

    return function(e)    {
        if(typeof e != 'undefined')    {
            var element = e.element();

            if(element.name != 'shipping_method')    {
                update_payments = false;
            }
        }

        var form = $('onepagecheckout-form');
        var shipping_method = $RF(form, 'shipping_method');
        var payment_method = $RF(form, 'payment[method]');
        var totals = get_totals_element();

        var freeMethod = $('p_method_free');
        if(freeMethod){
            payment.reloadcallback = true;
            payment.countreload = 1;
        }

        totals.update('<div class="loading-ajax">&nbsp;</div>');

        if(update_payments)    {
            var payment_methods = $$('div.payment-methods')[0];
            payment_methods.update('<div class="loading-ajax">&nbsp;</div>');
        }

        var parameters = {
                shipping_method: shipping_method,
                payment_method: payment_method
        }

        /* Find payment parameters and include */
        var items = $$('input[name^=payment]').concat($$('select[name^=payment]'));
        var names = items.pluck('name');
        var values = items.pluck('value');

        for(var x=0; x < names.length; x++)    {
            if(names[x] != 'payment[method]')    {
                parameters[names[x]] = values[x];
            }
        }

        new Ajax.Request(url, {
            method: 'post',
            onSuccess: function(transport)    {
            if(transport.status == 200)    {
                var data = transport.responseText.evalJSON();
                var form = $('onepagecheckout-form');

                totals.update(data.summary);

                if(update_payments)    {

                    payment_methods.replace(data.payment_method);

                    $$('div.payment-methods input[name^=payment\[method\]]').invoke('observe', 'click', get_separate_save_methods_function(url));
                    $$('div.payment-methods input[name^=payment\[method\]]').invoke('observe', 'click', function() {
                        $$('div.onepagecheckout-payment-method-error').each(function(item) {
                            new Effect.Fade(item);
                        });
                    });

                    if($RF($('onepagecheckout-form'), 'payment[method]') != null)    {
                        try    {
                            var payment_method = $RF(form, 'payment[method]');
                            $('container_payment_method_' + payment_method).show();
                            $('payment_form_' + payment_method).show();
                        } catch(err)    {

                        }
                    }
                }
            }
        },
        parameters: parameters
        });
    }
}

function paymentrefresh(url) {
    var payment_methods = $$('div.payment-methods')[0];
    payment_methods.update('<div class="loading-ajax">&nbsp;</div>');
    new Ajax.Request(url, {
        method: 'get',
        onSuccess: function(transport){
            if(transport.status == 200)    {
                    var data = transport.responseText.evalJSON();
                    payment_methods.replace(data.payment_method);

                    $$('div.payment-methods input[name^=payment\[method\]]', 'div.payment-methods input[name^=payment[useProfile]]').invoke('observe', 'click', get_separate_save_methods_function(url));
                    $$('div.payment-methods input[name^=payment\[method\]]', 'div.payment-methods input[name^=payment[useProfile]]').invoke('observe', 'click', function() {
                        $$('div.onepagecheckout-payment-method-error').each(function(item) {
                            new Effect.Fade(item);
                        });
                    });

                    if($RF(form, 'payment[method]') != null)    {
                        try    {
                            var payment_method = $RF(form, 'payment[method]');
                            $('container_payment_method_' + payment_method).show();
                            $('payment_form_' + payment_method).show();
                        } catch(err){}
                    }

            }
        }
    });
}

function addressPreview(templates, target) {
    var bparams = {};
    var sparams = {};
    var savedBillingItems = $('billing-address-select');
    if(savedBillingItems && savedBillingItems.getValue()){
        index = savedBillingItems.selectedIndex;
        bparams = customerBAddresses[index];
    } else {
        var items = $$('input[name^=billing]').concat($$('select[name^=billing]'));
        items.each(function(s) {
          if(s.getStyle('display') != 'none'){
              selectText = s.options
              if(selectText){
                  value = selectText[s.selectedIndex].text;
              } else {
                  value = s.getValue();
              }
              value = '<span class="' + s.id.replace(':','-') + '">' + value + '</span>';

              if(s.id == 'billing:region_id'){
                  bparams['billing:region'] = value;
              } else {
                  bparams[s.id] = value;
              }
          }
        });
    }



    var savedShippingItems = $('shipping-address-select');
    if(savedShippingItems && savedShippingItems.getValue()){
        index = savedShippingItems.selectedIndex;
        sparams = customerSAddresses[index];
    } else {
        var items = $$('input[name^=shipping]').concat($$('select[name^=shipping]'));
        items.each(function(s) {
            if(s.getStyle('display') != 'none'){
                selectText = s.options
                if(selectText){
                    value = selectText[s.selectedIndex].text;
                } else {
                    value = s.getValue();
                }

                value = '<span class="' + s.id.replace(':','-') + '">' + value + '</span>';

                if(s.id == 'shipping:region_id'){
                    sparams['shipping:region'] = value;
                } else {
                    sparams[s.id] = value;
                }
            }
        });
    }


    var form = $('onepagecheckout-form');

    var shipping_method = $RF(form, 'shipping_method');
    if(shipping_method){
        var shipping_label = $('s_method_' + shipping_method).up('dt').down('label').innerHTML;
        var shipping_title = $('s_method_' + shipping_method).up('dt').previous('dd').innerHTML;
        var shipping_row = shipping_title + ' - ' + shipping_label
    }

    var useOnlyBilling = $('billing:use_for_shipping_yes').getValue();
    billinga_template = new Template(templates.billing);

    if(useOnlyBilling){
        shippinga_template = new Template(templates.billing);
    }else{
        shippinga_template = new Template(templates.shipping);
    }

    var payment_method = payment.currentMethod;

    if(payment_method){
        var payment_label = $('p_method_'+payment_method).up('dt').down('label').innerHTML;
    }

    var targetelem = $(target + '_billinga').childElements()[1];
    if(targetelem){
        targetelem.update(billinga_template.evaluate(bparams));
    }

    var targetelem = $(target + '_shippinga').childElements()[1];
    if(targetelem){
        if(useOnlyBilling){
            targetelem.update(shippinga_template.evaluate(bparams));
        }else{
            targetelem.update(shippinga_template.evaluate(sparams));
        }
    }

    var targetelem = $(target + '_shipping').childElements()[1];
    if(targetelem){
        targetelem.update(shipping_row);
    }

    var targetelem = $(target + '_payment').childElements()[1];
    if(targetelem){
        targetelem.update(payment_label);
    }

    var targetelem = $(target + '_summary').childElements()[1];
    if(targetelem){
        targetelem.update('');
        targetelem.insert($$('table.onepagecheckout-summary')[0].cloneNode(true));
        targetelem.insert($$('table.onepagecheckout-totals')[0].cloneNode(true));
    }
}


var Checkout = Class.create();
    Checkout.prototype = {
        initialize: function(){
        this.accordion = '';
        this.progressUrl = '';
        this.reviewUrl = '';
        this.saveMethodUrl = '';
        this.failureUrl = '';
        this.billingForm = false;
        this.shippingForm= false;
        this.syncBillingShipping = false;
        this.method = '';
        this.payment = '';
        this.loadWaiting = false;
    },

    ajaxFailure: function(){
        location.href = this.failureUrl;
    },

    setLoadWaiting: function(step, keepDisabled) {
        return true
    }
};

//billing
var Billing = Class.create();
Billing.prototype = {
            initialize: function(form, addressUrl, saveUrl){
        this.form = form;
    },

    setAddress: function(addressId){

    },

    newAddress: function(isNew){
        if (isNew) {
            //this.resetSelectedAddress();
            Element.show('billing_address_list');
            if($('billing:use_for_shipping_yes').getValue() != "1" && $('shipping-address-select').getValue() == ''){
                Element.show('shipping_address_list');
            }

        } else {
            Element.hide('billing_address_list');
        }
        $$('input[name^=billing]', 'select[id=billing:region_id]').each(function(e){
            if(e.name=='billing[use_for_shipping]' || e.name=='billing[save_in_address_book]'){

            } else {
                e.value = '';
            }
        });
    },

    resetSelectedAddress: function(){
        var selectElement = $('shipping-address-select')
        if (selectElement) {
            selectElement.value='';
        }
    },

    fillForm: function(transport){

    },

    setUseForShipping: function(flag) {

    },

    save: function(){

    },

    resetLoadWaiting: function(transport){

    },

    nextStep: function(transport){

    }
};

//shipping
var Shipping = Class.create();
    Shipping.prototype = {
            initialize: function(form){
        this.form = form;
    },

    setAddress: function(addressId){

    },

    newAddress: function(isNew){
        if (isNew) {
            this.resetSelectedAddress();
            Element.show('shipping_address_list');
        } else {
            Element.hide('shipping_address_list');
        }

        $$('#shipping_address input[name^=shipping],#shipping_address select[id=shipping:region_id]').each(function(e){
            if(e.name=='shipping[save_in_address_book]'){

            } else {
                e.value = '';
            }
        })

    },

    resetSelectedAddress: function(){
        var selectElement = $('shipping-address-select')
        if (selectElement) {
            selectElement.value='';
        }
    },

    fillForm: function(transport){

    },

    setSameAsBilling: function(flag) {

    },

    syncWithBilling: function () {

    },

    setRegionValue: function(){
        //$('shipping:region').value = $('billing:region').value;
    },

    save: function(){

    }
};

//payment object
var Payment = Class.create();
    Payment.prototype = {
            beforeInitFunc:$H({}),
            afterInitFunc:$H({}),
            beforeValidateFunc:$H({}),
            afterValidateFunc:$H({}),
            initialize: function(form, saveUrl){
        this.form = form;
        this.saveUrl = saveUrl;
    },

    init : function () {
        var elements = Form.getElements(this.form);
        if ($(this.form)) {
            //$(this.form).observe('submit', function(event){this.save();Event.stop(event);}.bind(this));
        }
        var method = null;
        for (var i=0; i<elements.length; i++) {
            if (elements[i].name=='payment[method]') {
                if (elements[i].checked) {
                    method = elements[i].value;
                }
            } else {
                elements[i].disabled = true;
            }
        }
        if (method) this.switchMethod(method);
    },

    switchMethod: function(method){
        if (this.currentMethod && $('payment_form_'+this.currentMethod)) {
            var form = $('payment_form_'+this.currentMethod);
            form.style.display = 'none';
            var elements = form.select('input').concat(form.select('select')).concat(form.select('textarea'));
            for (var i=0; i<elements.length; i++) elements[i].disabled = true;
        }

        if ($('payment_form_'+method)){
            var form = $('payment_form_'+method);
            form.style.display = '';
            var elements = form.select('input').concat(form.select('select')).concat(form.select('textarea'));
            for (var i=0; i<elements.length; i++) elements[i].disabled = false;
            this.currentMethod = method;
        }
    },

    addBeforeValidateFunction : function(code, func) {
        this.beforeValidateFunc.set(code, func);
    },

    beforeValidate : function() {
        var validateResult = true;
        var hasValidation = false;
        (this.beforeValidateFunc).each(function(validate){
            hasValidation = true;
            if ((validate.value)() == false) {
                validateResult = false;
            }
        }.bind(this));
        if (!hasValidation) {
            validateResult = false;
        }
        return validateResult;
    },

    validate: function() {
        var methods = document.getElementsByName('payment[method]');
        if (methods.length==0) {
            alert(Translator.translate('Your order can not be completed at this time as there is no payment methods available for it.'));
            return false;
        }
        for (var i=0; i<methods.length; i++) {
            if (methods[i].checked) {
                return true;
            }
        }
        alert(Translator.translate('Please specify payment method.'));
        return false;
    },

    save: function(){
    },
    addAfterInitFunction : function(code, func) {
        this.afterInitFunc.set(code, func);
    },

    afterInit : function() {
        (this.afterInitFunc).each(function(init){
            (init.value)();
        });
    }
};
