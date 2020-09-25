$("#country").select2( {
    placeholder: "Select Country",
} );

$(".product-order-select").select2( {
    placeholder: "Select Product"
} );

$(document).ready(function () {
  $('#dtEntityTable').DataTable({
    "paging": false,
    order: [[ 0, "desc" ]],
    columnDefs: [{
      orderable: false,
      targets: 8
    }]
  });
  $.ajax({
    type: "GET",
    url: "/api/countries",
    success: (data) => {
      let countrySelect = $('#country')[0];
      JSON.parse(data).forEach((element) => {
        let option = document.createElement('option');
        option.innerText = element.name;
        option.value = element.name;
        countrySelect.appendChild(option);
      })
        $(countrySelect).val($(countrySelect).find(':first')[0].value).trigger('change');
    }
  });

});


let id = 0, showId = 0;
$('.show-entity-button').click(function (event) {
  event.preventDefault();
  showId = $(this).data('value-id');
  getEntityAjax(showId,(data) => {
    $('#showOrderId')[0].innerText = data.id
    $('#showOrderCustomer')[0].innerText = data.customer
    $('#showOrderCompanyName')[0].innerText = data.company_name
    $('#showOrderStatus')[0].innerText = data.status
    $('#showOrderTrackingNumber')[0].innerText = data.tracking_number
    $('#showOrderAddress')[0].innerText = data.address
    $('#showOrderShippingCost')[0].innerText = data.shipping_cost
    $('#showOrderCity')[0].innerText = data.city
    $('#showOrderShipped')[0].innerText = data.shipped
    $('#showOrderPackingSelection')[0].innerText = data.packing_selection
    $('#showOrderZipCode')[0].innerText = data.zip_postal_code
    $('#showOrderState')[0].innerText = data.state_region
    $('#showOrderCountry')[0].innerText = data.country
    $('#showOrderCreated')[0].innerText = data.created_at.split('T')[0]
    $('#showOrderPhone')[0].innerText = data.phone
    $('#showOrderShippingCompany')[0].innerText = data.shipping_company
    $('#showOrderComment')[0].innerText = data.comment
    $('#showOrderUser')[0].innerText = data.user.id


    let elements = $('.show-product-container').toArray();
    let element = elements.shift();
    for (let i = 0; i < elements.length; i++) {
      elements[i].remove();
    }
    createShowProduct($(element), data.products.shift());
    data.products.forEach((product) => {
      let clone = $(element).clone();
      createShowProduct (clone, product);
      clone.appendTo('.show-products-container');
    });

    $('#showModal').modal()
  });
});

function createShowProduct (element, product) {
  element.find('.show-product')[0].innerText = product.name
  element.find('.show-quantity')[0].innerText = product.pivot.quantity
  element.find('.show-price')[0].innerText = product.pivot.price
  element.find('.show-description')[0].innerText = product.pivot.description
}

$('.edit-entity-button').click(function (event) {
  event.preventDefault();
  id = $(this).data('value-id');
  $('.save-changes')[0].innerText = 'Update';
  $('#modalAddLabel')[0].innerText = 'Update Order'
  getEntityAjax(id,(data) => {
      $('#customer')[0].value = data.customer
      $('#company_name')[0].value = data.company_name
      $('#country').eq(0).val(data.country).trigger('change');
      $('#address')[0].value = data.address

      if ($('#tracking_number')[0]) {
          $('#tracking_number')[0].value = data.tracking_number
      }
      $('#city')[0].value = data.city
      $('#zip_postal_code')[0].value = data.zip_postal_code
      $('#state_region')[0].value = data.state_region
      $('#phone')[0].value = data.phone
      $('#shipping_company')[0].value = data.shipping_company
      $('#comment')[0].value = data.comment
      $(`#packing_selection [value="${data.packing_selection}"]`)[0].checked = true

      if ($('#shipping_cost')[0]) {
        $('#shipping_cost')[0].value = data.shipping_cost
      }
      if ($('#shipped')[0]) {
        $('#shipped')[0].value = data.shipped
      }
      if ($('#status')[0]) {
        $('#status')[0].value = data.status
      }

      let element = $('.product-container').eq(0);
      element.next().remove();
      addProduct(element, data.products.shift())
      for (let i = 0; i < data.products.length; i++) {
        let clone = element.clone()
        addProduct(clone, data.products[i])
        clone.appendTo('.products-container')
      }
      $('#modalAdd').modal()
    });
});

function getEntityAjax (dataId, success) {
  $.ajax({
    type: 'GET',
    url: `/api/order/${dataId}`,
    success
  })
}


$('.close-modal-button').click(function (event) {
  $('.save-changes')[0].innerText = 'Create'
  $('#modalAddLabel')[0].innerText = 'Add new Order'
  $('#customer')[0].value = ''
  $('#company_name')[0].value = ''
  $('#country')[0].value = $('#country option')[0].innerText
  $('#address')[0].value = ''
  $('#tracking_number')[0].value = ''
  $('#city')[0].value = ''
  $('#zip_postal_code')[0].value = ''
  $('#state_region')[0].value = ''
  $('#phone')[0].value = ''
  $('#shipping_company')[0].value = $('#shipping_company option')[0].innerText
  $('#comment')[0].value = ''
  $('#packing_selection input:first')[0].checked =  true

  if ($('#shipping_cost')[0]) {
    $('#shipping_cost')[0].value = ''
  }
  if ($('#shipped')[0]) {
    $('#shipped')[0].value = ''
  }
  if ($('#status')[0]) {
    $('#status')[0].value = ''
  }

  $('.product-container:not(:first-child)').toArray().forEach((productsSelect) => {
      $(productsSelect).next().remove();
      productsSelect.remove();
  })
  $('.product-select :first').attr('selected', 'true');
  $('.product-container').find('.quantity')[0].value = '';
  $('.product-container').find('.price')[0].value = '';
  $('.product-container').find('.description')[0].value = '';

  $('.is-invalid').toArray().forEach((element) => {
    $(element).removeClass('is-invalid');
  });
  $('.form-text').toArray().forEach((element) => {
    element.innerText = '';
  });
})

function addProduct (element, product) {
    element.find('.product-select').eq(0).select2({
        placeholder: "Select Product2",
    });
    if(product) {
        element.find('.product-select').eq(0).val(product.id).trigger('change')
        element.find('.product-order-quantity')[0].value = product.pivot.quantity
        element.find('.product-order-price')[0].value = product.pivot.price
        element.find('.product-order-description')[0].value = product.pivot.description
    }
}

function createOrderProducts () {
  let container = [];
  $('.product-container').toArray().forEach((productContainer) => {
    container.push({
      "product_id" : $(productContainer).find('.product-select')[0].value,
      "quantity": $(productContainer).find('.product-order-quantity')[0].value,
      "price": $(productContainer).find('.product-order-price')[0].value,
      "description": $(productContainer).find('.product-order-description')[0].value,
    });
  });
  return container;
}


$('.form-submit').submit(function (event) {
  if (true) {
    event.preventDefault()
    let entity = {
      _token: $('.modal [name="_token"]')[0].value,
      customer: $('#customer')[0].value,
      country: $('#country')[0].value,
      company_name: $('#company_name')[0].value,
      address: $('#address')[0].value,
      city: $('#city')[0].value,
      zip_postal_code: $('#zip_postal_code')[0].value,
      state_region: $('#state_region')[0].value,
      phone: $('#phone')[0].value,
      shipping_company: $('#shipping_company')[0].value,
      comment: $('#comment')[0].value,
      packing_selection: $('#packing_selection :checked')[0].value,
      order_products: createOrderProducts(),
    }
    if ($('#tracking_number')[0]) {
        entity.tracking_number = $('#tracking_number')[0].value
    }
    if ($('#shipping_cost')[0]) {
      entity.shipping_cost = $('#shipping_cost')[0].value
    }
    if ($('#shipped')[0]) {
      entity.shipped = $('#shipped')[0].value
    }
    if ($('#status')[0]) {
      entity.status = $('#status')[0].value
    }
    if($('.save-changes')[0].innerText === 'Create') {
      sendEntityAjax(entity, "POST");
    } else {
      sendEntityAjax(entity, "PUT", `/${id}`);
    }
  }
})


$('.delete-entity-button').click(function (event) {
  event.preventDefault();
  let deleteId = $(this).data('value-id');
  sendEntityAjax({}, "DELETE", `/${deleteId}`);
})

$('.copy-entity-button').click(function (event) {
  event.preventDefault();
  let copyId = $(this).data('value-id');
  sendEntityAjax({}, "POST", `/${copyId}`);
})

function sendEntityAjax (data, type, entityPath = '') {
  $.ajax({
    type,
    data,
    url: `/api/order${entityPath}`,
    success: (data) => {
      window.location.reload()
    },
    error: (errorEvent) => {
      let errors = errorEvent.responseJSON;
        console.log(errors)
      Object.keys(errors).forEach((error) => {
        $(`#${error}`).eq(0).addClass('is-invalid');
        $(`#${error}`).eq(0).parent().find('small')[0].innerText = errors[error][0];
      });
    }
  })
}










