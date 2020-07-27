
$('.edit-entity-button').click(function (event) {
  event.preventDefault();
  let id = $(this).data('value-id');
  $.ajax({
    type: "GET",
    url: `/api/shipment/${id}`,
    success: (data) => {
      $('#tracking_number')[0].value = data.tracking_number;
      $('#shipping_company')[0].value = data.shipping_company;
      $('#comment')[0].value = data.comment;
      if($('#received')[0]) {
        $('#received')[0].value = data.received;
      }
      if($('#shipped')[0]) {
        $('#shipped')[0].value = data.shipped;
      }
      let element = $('.product-container').eq(0);
      addProduct(element, data.products.shift())
      for (let i = 0; i < data.products.length; i++) {
        let clone = element.clone();
        addProduct(clone, data.products[i]);
        clone.appendTo(".products-container")
      }
      $('#modalAdd').modal();
    }
  });
});

$('.close-modal-button').click(function (event) {
  $('#tracking_number')[0].value = '';
  $("#shipping_company :first").attr('selected', 'true');
  $('#comment')[0].value = '';
  if($('#received')[0]) {
    $('#received')[0].value = '';
  }
  if($('#shipped')[0]) {
    $('#shipped')[0].value = '';
  }
  $('.product-container:not(:first-child)').toArray().forEach((productsSelect) => {
    productsSelect.remove();
  });
  $(".product-select :first").attr('selected', 'true');
  $('.product-container').find('.quantity')[0].value = '';
});

function addProduct(element, product) {
  element.find('.product-select')[0].value = product.id;
  element.find('.quantity')[0].value = product.pivot.quantity;
}

$('.save-changes').click(function (event) {
  event.preventDefault();
  if(this.innerText === 'Create') {
    createEntity();
  } else {
    editEntity();
  }
});

function createEntity () {

}

function editEntity () {

}