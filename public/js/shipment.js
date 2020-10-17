window.onerror = null;

$(document).ready(function () {
    // if($('#dtEntityTable td').length) {
        $('#dtEntityTable').DataTable({
            "paging": true,
            order: [[0, "desc"]],
            columnDefs: [{
                orderable: false,
                targets: -1
            }]
        });
        $('.dataTables_length').addClass('bs-select');
    let top1 = $('.text-full-size').eq(0).closest('div').offset().top;
    let pag = $('.main-container .dataTables_paginate').closest('.row');
    pag.css({"top": top1 - pag.height(), position: 'absolute', left: 0});
    // }
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
    $('#showQuantity')[0].innerText = data.quantity
    $('#showCreated')[0].innerText = data.created_at.split('T')[0]


    let elements = $('.show-product-container').toArray();
    let element = elements.shift();
    for (let i = 0; i < elements.length; i++) {
      elements[i].remove();
    }
    if(data.products.length > 0) {
      createShowProduct($(element), data.products.shift())
      data.products.forEach((product) => {
        let clone = $(element).clone();
        createShowProduct(clone, product);
        clone.appendTo('.show-products-container');
      });
    }

    $('#showModal').modal()
  });
});

function createShowProduct (element, product) {
  let title = `${product.brand} | ${product.name} | UPC[${product.upc}] | SKU[${product.sku}] | In transit (${product.in_transit}) | Available (${product.available}) | Reserved (${product.reserved})`
  element.find('.show-product')[0].innerText = product.name;
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
      // let element = $('.product-container').eq(0)
      // element.next().remove();
      // if(data.products.length > 0) {
      //   addProduct(element, data.products.shift())
      //   for (let i = 0; i < data.products.length; i++) {
      //     let clone = element.clone()
      //     addProduct(clone, data.products[i])
      //     clone.appendTo('.products-container')
      //   }
      // }
      // $('#modalAdd').modal()
      // $.ajax({
      //     type: 'GET',
      //     url: `/api/product?id=` + data.user.id,
      //     success: function (data){
      //         func = handler(data);
      //         $('.select2-selection').eq(0).bind('click', func);
      //     }
      // })


      $.ajax({
          type: 'GET',
          url: `/api/product?all_id=` + data.user.id,
          success: function (products){
              for (let i = 0; i < data.products.length; i++) {
                  addProduct(products, data.products[i])
              }
              $('#modalAdd').modal()
          }
      })
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
    $('#modalAdd .product-container').toArray().forEach((productsSelect) => {
        // $(productsSelect).next().remove();
        productsSelect.remove();
    });


  $('.is-invalid').toArray().forEach((element) => {
    $(element).removeClass('is-invalid');
  });
  $('.form-text').toArray().forEach((element) => {
    element.innerText = '';
  });
})

function addProduct (products, product) {
  //   element.find('.product-select').eq(0).select2({
  //       placeholder: "Select Product2",
  //   });
  //   element.find('.product-select').eq(0).val(product.id).trigger('change')
  // element.find('.quantity')[0].value = product.pivot.quantity
    let container = $('.products-container');
    let productNode = container.append('<div class="product-container"></div>').find('.product-container').last();
    let select = productNode.append('<select class="form-control product-select product-shipment-select"></select>').find('select');
    products.forEach(function (prod){
        let name = `${prod.brand} | ${prod.name} | ${prod.upc} | ${prod.sku} | In transit (${prod.in_transit}) | Available (${prod.available}) | Reserved (${prod.received})`;
        select.append(`<option value="${prod.id}" ${prod.id === product.id ? 'selected' : ''}>${name}</option>`)
    });
    productNode.append(`<input type="number" class="form-control quantity"` +
        ` placeholder="Quantity" required min="1" max="10000" value="${product.pivot.quantity}">`);
    let a = productNode.append(
        '<a href="#" class="remove-product-select">' +
        '   <i class="fa fa-times fa-2x text-dark" aria-hidden="true"></i>' +
        '</a>'
    ).find('a').click(function (event) {
        event.preventDefault();
        $(this).closest('.product-container').eq(0).remove();
    });

    productNode.find('.product-select').eq(0).select2({
        placeholder: "Select Product",
    });
    select.on('select2:select', function (e) {
        $('option').toArray().forEach(function (option) {
            $(option).attr('disabled', false)
        });
        let val = $(this).val()
        $(this).find(`option`).toArray().forEach(function (opt) {
            if ($(opt).val() == val) {
                $(opt).attr('selected', true);
            } else {
                $(opt).attr('selected', false);
            }
        })
        $('option[selected]').toArray().forEach(function (option) {
            $(`option[value="${$(option).val()}"]`).toArray().forEach(function (optionOther) {
                if (optionOther != option) {
                    $(optionOther).attr('disabled', true)
                }
            })
        })
        $('.product-select.product-shipment-select').toArray().forEach(function (select) {
            try { $(select).select2('destroy'); } catch (e) {}
            $(select).select2({
                placeholder: "Select Product",
            });
        })
    });
    select.trigger('select2:select');
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
    if(type === 'PUT' || type === 'DELETE'){
        data['_method'] = type;
        type = 'POST';
    }
  $.ajax({
    type,
    data,
    url: `/api/shipment${entityPath}`,
    success: (data) => {
      window.location.reload()
    },
    error: (errorEvent) => {
      let errors = errorEvent.responseJSON;
      console.log(errorEvent)
      Object.keys(errors).forEach((error) => {
        $(`#${error}`).eq(0).addClass('is-invalid');
        $(`#${error}`).eq(0).next('small')[0].innerText = errors[error][0];
      });
    }
  })
}

$('.add-product-select').click(function (event) {
    event.preventDefault();
    let clone = $(".product-container").eq(0).clone();
    let select = clone.find('.product-select').eq(0);
    let options = select.find('option');
    for (let i = 0; i < options.length; i++) {
        if(!options.eq(i).attr('disabled')){
            options.eq(i).attr('selected', true);
            break;
        }
    }
    clone.find('.quantity')[0].value = '';
    if(clone.find('.price')[0]) {
        clone.find('.price')[0].value = '';
    }
    if(clone.find('.description')[0]) {
        clone.find('.description')[0].value = '';
    }
    clone.find('.remove-product-select').click(function (event) {
        event.preventDefault();
        $(this).closest('.product-container').eq(0).remove();
    });
    clone.appendTo(".products-container");

    select.next('remove');
    // select.select2('destroy')
    select.select2({
        placeholder: "Select Product"
    });
    select.on('select2:select', function (e) {
        $('option').toArray().forEach(function (option) {
            $(option).attr('disabled', false)
        });
        let val = $(this).val()
        $(this).find(`option`).toArray().forEach(function (opt) {
            if ($(opt).val() == val) {
                $(opt).attr('selected', true);
            } else {
                $(opt).attr('selected', false);
            }
        })
        $('option[selected]').toArray().forEach(function (option) {
            $(`option[value="${$(option).val()}"]`).toArray().forEach(function (optionOther) {
                if (optionOther != option) {
                    $(optionOther).attr('disabled', true)
                }
            })
        })
        $('.product-select.product-shipment-select').toArray().forEach(function (select) {
            try {
                $(select).select2('destroy');
            } catch (e) {
            }
            $(select).select2({
                placeholder: "Select Product",
            });
        })
    });
    select.trigger('select2:select');
});

$('#modalAdd').on('shown.bs.modal', function (e) {
    if(e.relatedTarget){
        $.ajax({
            type: 'GET',
            url: `/api/product?all_auth=true`,
            success: function (products){
                console.log(products)
                addProduct(products, {pivot: {quantity: ''}})
            }
        })
    }
})

$('.delete-entity-button').click(function (event) {
    event.preventDefault();

    if(confirm('This shipment will be deleted. Shall we continue?')) {
        let deleteId = $(this).data('value-id');
        sendEntityAjax({}, "DELETE", `/${deleteId}`);
    }
})
