
$(".product-select").select2( {
    placeholder: "Select Product"
} );

$(document).ready(function () {
  $('#dtEntityTable').DataTable({
    "paging": false,
    columnDefs: [{
      orderable: false,
      targets: 9
    }]
  });
  $('.dataTables_length').addClass('bs-select');
});

let id = 0, showId = 0;
$('.show-entity-button').click(function (event) {
  event.preventDefault();
  showId = $(this).data('value-id');
  getEntityAjax(showId,(data) => {
    $('#showId')[0].innerText = data.id
    $('#showUser')[0].innerText = data.user.name
    $('#showShipped')[0].innerText = data.shipped
    $('#showReceived')[0].innerText = data.received
    $('#showCompany')[0].innerText = data.shipping_company
    $('#showTrackingNumber')[0].innerText = data.tracking_number
    $('#showComment')[0].innerText = data.comment
    $('#showComment')[0].innerText = data.comment
    $('#showQuantity')[0].innerText = data.quantity
    $('#showCreated')[0].innerText = data.created_at.split('T')[0]


    let elements = $('.show-product-container').toArray();
    let element = elements.shift();
    for (let i = 0; i < elements.length; i++) {
      elements[i].remove();
    }
    createShowProduct($(element), data.products.shift())
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
}

$('.edit-entity-button').click(function (event) {
  event.preventDefault();
  id = $(this).data('value-id');
  $('.save-changes')[0].innerText = 'Update';
  $('#modalAddLabel')[0].innerText = 'Update Inbound Shipment'
  getEntityAjax(id,(data) => {
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
    url: `/api/shipment/${dataId}`,
    success
  })
}


$('.close-modal-button').click(function (event) {
  $('.save-changes')[0].innerText = 'Create'
  $('#modalAddLabel')[0].innerText = 'Add new Inbound Shipment'
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
    element.find('.product-select').eq(0).select2({
        placeholder: "Select Product2",
    });
    element.find('.product-select').eq(0).val(product.id).trigger('change')
  // element.find('.product-select')[0].value = product.id
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
