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
  clone.find('.quantity')[0].value = '';
  if(clone.find('.price')[0])
    clone.find('.price')[0].value = '';
  if(clone.find('.description')[0])
    clone.find('.description')[0].value = '';
  clone.find('.remove-product-select').click(function (event) {
    event.preventDefault();
    $(this).closest('.product-container').eq(0).remove();
  });
  clone.appendTo(".products-container");
});

let lastUser = {name: $('#personal-name')[0].value, email: $('#personal-email')[0].value};

$('#updateUserData').submit(function (event) {
  event.preventDefault();
  let userId = $('#personal-id')[0].innerText
  let entity = {
    _token: $('.modal [name="_token"]')[0].value,
    name: $('#personal-name')[0].value,
    email: $('#personal-email')[0].value,
  }
  sendEntityPutAjax(entity, `user/update/${userId}`);
})

$('#changePassword').submit(function (event) {
  event.preventDefault();
  let userId = $('#personal-id')[0].innerText
  let entity = {
    _token: $('.modal [name="_token"]')[0].value,
    last_password: $('#personal-last_password')[0].value,
    password: $('#personal-password')[0].value,
    password_confirmation: $('#personal-password_confirmation')[0].value,
  }
  sendEntityPutAjax(entity, `user/change-password/${userId}`);
})

function sendEntityPutAjax (data, entityPath = '') {
  $.ajax({
    type: 'PUT',
    data,
    url: `/api/${entityPath}`,
    success: (data) => {
      window.location.reload()
    },
    error: (errorEvent) => {
      closeModalErrors();
      let errors = errorEvent.responseJSON;
      Object.keys(errors).forEach((error) => {
        console.log(errorEvent)
        $(`#personal-${error}`).eq(0).addClass('is-invalid');
        $(`#personal-${error}`).eq(0).next('small')[0].innerText = errors[error][0];
      });
    }
  })
}

$('.close-modal-personal').click(function (event) {

  $('#personal-name')[0].value = lastUser.name
  $('#personal-email')[0].value = lastUser.email
  $('#personal-last-password')[0].value = ''
  $('#personal-password')[0].value = ''
  $('#personal-password-confirmation')[0].value = ''
  closeModalErrors();

})

function closeModalErrors () {
  $('.is-invalid').toArray().forEach((element) => {
    $(element).removeClass('is-invalid');
  });
  $('.form-text').toArray().forEach((element) => {
    element.innerText = '';
  });
}