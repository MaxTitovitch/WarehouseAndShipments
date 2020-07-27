$('#import-open').click(function (event) {
  $('#import-input').click();
});

$('#import-input').change(function (event) {
  $('#import-submit').click();
});


$('[type="date"]').toArray().forEach((element) => {
    let dateMin = new Date().setFullYear(new Date().getFullYear()-10);
    let dateMax = new Date().setFullYear(new Date().getFullYear()+10);
    element.setAttribute('min', new Date(dateMin).toISOString().split('T')[0]);
    element.setAttribute('max', new Date(dateMax).toISOString().split('T')[0]);
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

