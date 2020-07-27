let products = [];
$(document).ready(() => {
  $.ajax({
    type: "GET",
    url: "/api/product",
    success: (data) => {
      products = data;
      addProducts($('.product-select')[0]);
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
  clone.find('.remove-product-select').click(function (event) {
    event.preventDefault();
    $(this).closest('.product-container').eq(0).remove();
  });
  clone.appendTo(".products-container");
});



