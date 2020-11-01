$(document).ready(function () {
  $('#dtEntityTable').DataTable({
    "paging": true,
    order: [[ 0, "desc" ]],
    columnDefs: [{
      orderable: false,
      targets: -1
    }]
  });
  $('.dataTables_length').addClass('bs-select');

  if( !(/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) ) {
      let top1 = $('.text-full-size').eq(0).offset().top - 50;
      let pag = $('.main-container .dataTables_paginate').closest('.row');
      pag.css({"top": top1 - pag.height(), position: 'absolute', left: 0});
  }
});

let id = 0, showId = 0
$('.show-entity-button').click(function (event) {
  event.preventDefault()
  showId = $(this).data('value-id')
  getEntityAjax(showId, (data) => {
    $('#showProductId')[0].innerText = data.id
    $('#showProductCreated')[0].innerText = data.created_at.split('T')[0]
    $('#showProductUPC')[0].innerText = data.upc
    $('#showProductSKU')[0].innerText = data.sku
    $('#showProductBrand')[0].innerText = data.brand
    $('#showProductName')[0].innerText = data.name
    $('#showProductTransit')[0].innerText = data.in_transit
    $('#showProductReserved')[0].innerText = data.received
    $('#showProductAvailable')[0].innerText = data.available
      if(data.user) {
          $('#showProductUser')[0].innerText = data.user.name
      }

    $('#showModal').modal()
  })
})

$('.edit-entity-button').click(function (event) {
  event.preventDefault()
  id = $(this).data('value-id')
  $('.save-changes')[0].innerText = 'Update'
  $('#modalAddLabelProduct')[0].innerText = 'Update product'
  getEntityAjax(id, (data) => {
    $('#name')[0].value = data.name
    $('#brand')[0].value = data.brand
    $('#upc')[0].value = data.upc
    $('#sku')[0].value = data.sku
    // $('#reserved')[0].value = data.received
    // $('#available')[0].checked = data.available
    // $('#in_transit')[0].checked = data.in_transit

    $('#modalAdd').modal()
  })
})

function getEntityAjax (dataId, success) {
  $.ajax({
    type: 'GET',
    url: `/api/product/${dataId}`,
    success
  })
}

$('.close-modal-button').click(function (event) {
  $('.save-changes')[0].innerText = 'Create'
  $('#modalAddLabelProduct')[0].innerText = 'Add new product'

  $('#name')[0].value = ''
  $('#brand')[0].value = ''
  $('#upc')[0].value = ''
  $('#sku')[0].value = ''
  // $('#reserved')[0].value = ''
  // $('#available')[0].checked = false
  // $('#in_transit')[0].checked = false


  $('.is-invalid').toArray().forEach((element) => {
    $(element).removeClass('is-invalid');
  });
  $('.form-text').toArray().forEach((element) => {
    element.innerText = '';
  });
})

$('.form-submit').submit(function (event) {
    event.preventDefault();
    $('.form-submit button[type="submit"]').prop('disabled', true);
    let entity = {
      _token: $('.modal [name="_token"]')[0].value,
      name: $('#name')[0].value,
      brand: $('#brand')[0].value,
      upc: $('#upc')[0].value,
      sku: $('#sku')[0].value,
      // received: $('#reserved')[0].value,
      // available: $('#available')[0].checked ? 1 : 0,
      // in_transit: $('#in_transit')[0].checked ? 1 : 0,
    }
    if($('.save-changes')[0].innerText === 'Create') {
      sendEntityAjax(entity, "POST");
    } else {
      sendEntityAjax(entity, "PUT", `/${id}`);
    }
})

function sendEntityAjax (data, type, entityPath = '') {
    if(type === 'PUT' || type === 'DELETE'){
        data['_method'] = type;
        type = 'POST';
    }

  $.ajax({
    type,
    data,
    url: `/api/product${entityPath}`,
    success: (data) => {
      window.location.reload()
    },
    error: (errorEvent) => {
      console.log(errorEvent)
      let errors = errorEvent.responseJSON;
      Object.keys(errors).forEach((error) => {
        $('.form-submit button[type="submit"]').prop('disabled', false);
        $(`#${error}`).eq(0).addClass('is-invalid');
        if(error !== 'available' && error !== 'in_transit') {
          $(`#${error}`).eq(0).next('small')[0].innerText = errors[error][0];
        }
      });
    }
  })
}
