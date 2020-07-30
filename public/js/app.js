let products = [];
$(document).ready(() => {
  $.ajax({
    type: "GET",
    url: "/api/product",
    success: (data) => {
      products = data;
      if($('.product-select').toArray().length !== 0) {
        addProducts($('.product-select')[0]);
      }
    }
  });
});

function addProducts(productSelect) {
  if(productSelect) {
    products.forEach((element) => {
      let option = document.createElement('option');
      option.innerText = element.name;
      option.value = element.id;
      productSelect.appendChild(option);
    })
  }
}

$('#import-open').click(function (event) {
    $('#import-input').click();
});

$('#import-input').change(function (event) {
    $('#import-submit').click();
});

$('[type="date"]').toArray().forEach((element) => {
    let dateMin = new Date().setFullYear(new Date().getFullYear() - 10);
    let dateMax = new Date().setFullYear(new Date().getFullYear() + 10);
    element.setAttribute('min', new Date(dateMin).toISOString().split('T')[0]);
    element.setAttribute('max', new Date(dateMax).toISOString().split('T')[0]);
});

$('.add-product-select').click(function (event) {
  event.preventDefault();
  let clone = $(".product-container").eq(0).clone();
  let select = clone.find('.product-select').eq(0);
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
  select.select2( {
      placeholder: "Select Product"
  } );
});

$('#updateUserData').click(function (event) {
  event.preventDefault();

});

