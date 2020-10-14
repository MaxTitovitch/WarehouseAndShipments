$(document).ready(function () {
    $('#dtEntityTable').DataTable({
        "paging": true,
        order: [[ 0, "desc" ]],
        columnDefs: [{
          orderable: false,
          targets: 6
        }]
    });
    $('.dataTables_length').addClass('bs-select');
});

let id = 0, showId = 0, table = null;
$('.show-entity-button').click(function (event) {
  event.preventDefault()
    if (table) {
        table.destroy();
    }
  showId = $(this).data('value-id')
  getEntityAjax(showId, (data) => {
      // console.log(data)
    $('#showUserID')[0].innerText = data.id;
    $('#showUserSuite')[0].innerText = data.suite;
    $('#showUserName')[0].innerText = data.name;
    $('#showUserEmail')[0].innerText = data.email;
    $('#showUserRole')[0].innerText = data.role;
    $('#showUserBalance')[0].innerText = data.balance;
    $('#showUserCreated')[0].innerText = data.created_at.split('T')[0];
    $('#balanceHistoryArea').eq(0).empty();
    // data.balance_histories = data.balance_histories.reverse().slice(0, 15);
    // console.log(data.balance_histories)
    data.balance_histories.forEach((history) =>{
        addHistory( $('#balanceHistoryArea')[0], history)
    });
      table = $('#dtEntityTableShow').DataTable({
          "paging": true,
          retrieve: true,
          order: [[ 0, "desc" ]],
      });
      $('.dataTables_length').addClass('bs-select');
    $('#showModal').modal()
  })
})

let balanceAddId = 0;
$('.balance-entity-button').click(function (event) {
  event.preventDefault();
  balanceAddId = $(this).data('value-id');
  $('#userNameBalance')[0].innerText = $(this).data('value-name');
  $('#modalBalance').modal();
})

function addHistory(container, history) {
    let tr = document.createElement('tr');
    let tBalance = document.createElement('td');
    tBalance.innerHTML = '<span class="font-weight-bold">$</span>' + history.current_balance;
    tr.appendChild(tBalance);
    let tTransitionCost = document.createElement('td');
    tTransitionCost.innerHTML = '<span class="font-weight-bold">$</span>' + history.transaction_cost;
    tr.appendChild(tTransitionCost);
    let tType = document.createElement('td');
    tType.innerText = history.type;
    tType.classList.add(history.type === "Credit" ? 'text-success' : 'text-danger')
    tr.appendChild(tType);
    let tComment = document.createElement('td');
    tComment.innerText = history.comment;
    tr.appendChild(tComment);
    let tCreated = document.createElement('td');
    tCreated.innerText = history.created_at.split('T')[0];
    tr.appendChild(tCreated);
    container.appendChild(tr);
}

$('.edit-entity-button').click(function (event) {
  event.preventDefault()
  id = $(this).data('value-id')
  getEntityAjax(id, (data) => {
    $('#userName')[0].innerText = data.name
    $('#userEmail')[0].innerText = data.email
    $('#role')[0].value = data.role
    // $('#balance')[0].value = data.balance

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
      // balance: $('#balance')[0].value,
    }
    sendEntityAjax(entity, "PUT", `/${id}`);
})

$('#formBalance').submit(function (event) {
    event.preventDefault();
    let entity = {
      _token: $('.modal [name="_token"]')[0].value,
      balance: $('#creditAmount')[0].value,
      comment: $('#creditComment')[0].value,
    }
    sendEntityAjax(entity, "PUT", `/add-balance/${balanceAddId}`);
})


$('.delete-entity-button').click(function (event) {
  event.preventDefault();
  if(confirm('This user will be deleted. Shall we continue?')) {
      let deleteId = $(this).data('value-id');
      sendEntityAjax({}, "DELETE", `/${deleteId}`);
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
    url: `/api/user${entityPath}`,
    success: (data) => {
      window.location.reload()
    },
    error: (errorEvent) => {
      let errors = errorEvent.responseJSON;
      Object.keys(errors).forEach((error) => {
          try {
              $(`#${error}`).eq(0).addClass('is-invalid');
              $(`#${error}`).eq(0).next('small')[0].innerText = errors[error][0];
          } catch ($ex){
              console.log(error)
          }
      });
    }
  })
}
