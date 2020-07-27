$('#import-open').click(function (event) {
  $('#import-input').click();
});

$('#import-input').change(function (event) {
  $('#import-submit').click();
});

$(document).ready(() => {
  let products = [];
  $.ajax({
    type: "GET",
    url: "/api/product",
    success: (data) => {
      data.forEach((element) => {
        products.push({[element.id]: element.name});
      })
      let productSelect = $('.product-select')[0];
      if(productSelect) {
        products.forEach((element) => {
          productSelect.appendChild(`<option value="${element.id}">${element.name}<option/>`)
        })
      }
    }
  });
});