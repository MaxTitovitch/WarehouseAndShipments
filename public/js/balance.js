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

let showId = 0;
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
        $('#showOrderFeeCost')[0].innerText = data.fee_cost
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
        $('#showOrderUser')[0].innerText = `${data.user.name}, Suite: ${data.user.suite}`;



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

function getEntityAjax (dataId, success) {
    $.ajax({
        type: 'GET',
        url: `/api/order/${dataId}`,
        success
    })
}

function createShowProduct (element, product) {
    element.find('.show-upc')[0].innerText = product.upc;
    element.find('.show-sku')[0].innerText = product.sku;
    element.find('.show-brand')[0].innerText = product.brand;
    element.find('.show-product')[0].innerText = product.name;
    element.find('.show-quantity')[0].innerText = product.pivot.quantity
    element.find('.show-price')[0].innerText = product.pivot.price
    element.find('.show-description')[0].innerText = product.pivot.description
}
