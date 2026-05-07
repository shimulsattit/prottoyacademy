$(function () {
    var table = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/portal/stuff",
        columns: [
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
        
  });