
CRM.$(function($) {

  click_membership_type = function(evt) {
    var ct, type, data, pfv, dataStr;

    if (evt) {
      ct = $(evt.target);
    }
    else {
      ct = $('[data-price-field-values]:checked');
    }
    if (!ct || !ct.length) {
      return;
    }

    // Replaced eval() with JSON.parse() for security and CSP compatibility.
    dataStr = ct.attr('data-price-field-values');
    if (!dataStr) {
      return;
    }
    try {
      data = JSON.parse(dataStr);
    }
    catch (e) {
      return;
    }

    pfv = data[ct.val()];
    if (pfv) {
      $('#auto_renew').prop('checked', CRM.autoRenewIds.indexOf(pfv.membership_type_id) != -1);
    }
  }

  click_pay_later = function(evt) {
    var ar = $('#auto_renew');
    if ($(evt.currentTarget).val() == '0') {
      ar.prop('disabled', true).prop('checked', false);
    }
    else {
      ar.prop('disabled', false);
      click_membership_type();
    }
  }

  $('[data-price-field-values]').click(click_membership_type);
  $('[name="payment_processor_id"]').click(click_pay_later);

  click_membership_type();

});
