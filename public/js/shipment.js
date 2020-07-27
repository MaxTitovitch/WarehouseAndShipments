let id = 0;
$('.edit-entity-button').click(function (event) {
  event.preventDefault();
  id = $(this).data('value-id');
  $('.save-changes')[0].innerText = 'Udpate';
  $.ajax({
    type: 'GET',
    url: `/api/shipment/${id}`,
    success: (data) => {
      $('#tracking_number')[0].value = data.tracking_number
      $('#shipping_company')[0].value = data.shipping_company
      $('#comment')[0].value = data.comment
      if ($('#received')[0]) {
        $('#received')[0].value = data.received
      }
      if ($('#shipped')[0]) {
        $('#shipped')[0].value = data.shipped
      }
      let element = $('.product-container').eq(0)
      addProduct(element, data.products.shift())
      for (let i = 0; i < data.products.length; i++) {
        let clone = element.clone()
        addProduct(clone, data.products[i])
        clone.appendTo('.products-container')
      }
      $('#modalAdd').modal()
    }
  })
})

$('.close-modal-button').click(function (event) {
  $('.save-changes')[0].innerText = 'Create'
  $('#tracking_number')[0].value = ''
  $('#shipping_company :first').attr('selected', 'true')
  $('#comment')[0].value = ''
  if ($('#received')[0]) {
    $('#received')[0].value = ''
  }
  if ($('#shipped')[0]) {
    $('#shipped')[0].value = ''
  }
  $('.product-container:not(:first-child)').toArray().forEach((productsSelect) => {
    productsSelect.remove()
  })
  $('.product-select :first').attr('selected', 'true')
  $('.product-container').find('.quantity')[0].value = '';

  $('.is-invalid').toArray().forEach((element) => {
    $(element).removeClass('is-invalid');
  });
  $('.form-text').toArray().forEach((element) => {
    element.innerText = '';
  });
})

function addProduct (element, product) {
  element.find('.product-select')[0].value = product.id
  element.find('.quantity')[0].value = product.pivot.quantity
}

function createProductShipment () {
  let container = [];
  $('.product-container').toArray().forEach((productContainer) => {
    container.push({
      "product_id" : $(productContainer).find('.product-select')[0].value,
      "quantity": $(productContainer).find('.quantity')[0].value
    });
  });
  return container;
}


$('.form-submit').submit(function (event) {
  if (true) {
    event.preventDefault()
    let entity = {
      _token: $('.modal [name="_token"]')[0].value,
      tracking_number: $('#tracking_number')[0].value,
      shipping_company: $('#shipping_company')[0].value,
      comment: $('#comment')[0].value,
      product_shipments: createProductShipment(),
    }
    if ($('#received')[0]) {
      entity.received = $('#received')[0].value
    }
    if ($('#shipped')[0]) {
      entity.shipped = $('#shipped')[0].value
    }
    if($('.save-changes')[0].innerText === 'Create') {
      sendEntityAjax(entity, "POST");
    } else {
      sendEntityAjax(entity, "PUT", `/${id}`);
    }
  }
})

function sendEntityAjax (data, type, entityPath = '') {
  $.ajax({
    type,
    data,
    url: `/api/shipment${entityPath}`,
    success: (data) => {
      window.location.reload()
    },
    error: (errorEvent) => {
      let errors = errorEvent.responseJSON;
      Object.keys(errors).forEach((error) => {
        $(`#${error}`).eq(0).addClass('is-invalid');
        $(`#${error}`).eq(0).next('small')[0].innerText = errors[error][0];
      });
    }
  })
}