$(document).ready(function () {
  $('#dtEntityTable').DataTable({
    "paging": false,
    columnDefs: [{
      orderable: false,
      targets: 5
    }]
  });
  $('.dataTables_length').addClass('bs-select');
});

let id = 0, showId = 0
$('.show-entity-button').click(function (event) {
  event.preventDefault()
  showId = $(this).data('value-id')
  getEntityAjax(showId, (data) => {
    $('#showUserID')[0].innerText = data.id
    $('#showUserName')[0].innerText = data.name
    $('#showUserEmail')[0].innerText = data.email
    $('#showUserRole')[0].innerText = data.role
    $('#showUserBalance')[0].innerText = data.balance
    $('#showUserCreated')[0].innerText = data.created_at.split('T')[0]

    $('#showModal').modal()
  })
})

$('.edit-entity-button').click(function (event) {
  event.preventDefault()
  id = $(this).data('value-id')
  getEntityAjax(id, (data) => {
    $('#userName')[0].innerText = data.name
    $('#userEmail')[0].innerText = data.email
    $('#role')[0].value = data.role
    $('#balance')[0].value = data.balance

    $('#modalAdd').modal()
  })
})

function getEntityAjax (dataId, success) {
  $.ajax({
    type: 'GET',
    url: `/api/user/${dataId}`,
    success
  })
}

$('.close-modal-button').click(function (event) {
  $('.is-invalid').toArray().forEach((element) => {
    $(element).removeClass('is-invalid');
  });
  $('.form-text').toArray().forEach((element) => {
    element.innerText = '';
  });
})

$('.form-submit').submit(function (event) {
    event.preventDefault();
    let entity = {
      _token: $('.modal [name="_token"]')[0].value,
      role: $('#role')[0].value,
      balance: $('#balance')[0].value,
    }
    sendEntityAjax(entity, "PUT", `/${id}`);
})


$('.delete-entity-button').click(function (event) {
  event.preventDefault();
  let deleteId = $(this).data('value-id');
  sendEntityAjax({}, "DELETE", `/${deleteId}`);
})

function sendEntityAjax (data, type, entityPath = '') {
  $.ajax({
    type,
    data,
    url: `/api/user${entityPath}`,
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
