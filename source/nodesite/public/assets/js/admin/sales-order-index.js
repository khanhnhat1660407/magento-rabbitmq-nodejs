
 var notyf = new Notyf({
    duration: 3000,
    position: {
      x: 'right',
      y: 'top',
    },
    ripple: false,
    dismissible: true
});

$(document).ready(function (){
    const SYNC_ORDER_RESULT = 'sync-order-result';
    const LET_SYNC_ORDER    = 'let-sync-order';
    var salesOrderTable     = $('#sales-order-table');
    //Disable alert when search failed, syntax error
    $.fn.dataTable.ext.errMode = 'none';
    var oTable = salesOrderTable.dataTable({
        "serverSide": true,
        "scrollX": false,
        "pagingType": "simple_numbers",
        "searching": false,
        "paging": true,
        "processing": true,
        "order": [[ 6, 'desc' ]],
        "responsive": true,
        "dom": 'rt<"bottom"flp>',
        "language": {
            "processing":'<div id="spiner-container">' +
                         '     <div class="spinner-border" role="status">' +
                         '          <span class="sr-only">Loading...</span>' +
                         '     </div>' +
                         '</div>'
        },
        "fnPreDrawCallback": function() {
            return true;
        },
        "fnDrawCallback": function() {
            $(".dataTables_scrollHeadInner").css({"width":"100%"});
            $(".table ").css({"width":"100%;"});
            $(".bottom").addClass("row mt-3")
            $(".dataTables_length").addClass("col-sm-12 col-md-3 m-0")
            $(".dataTables_paginate").addClass("col-sm-12 col-md-6 m-0")
            $(".paging_simple_numbers").addClass("col-sm-12 col-md-12 m-0")
            return true;
        },
        "initComplete": function(settings, json) {
            var api = this.api();
        },
        "fixedColumns": true,
        "ajax": {
            "url": 'http://localhost:9000/api/order-management/get-all-order',
            "dataType": "json",
            "type": "GET",
            'error': function (jqXHR, exception) {},
            statusCode: {
                200: function() {
                    console.log('OK 200')
                },
                400: function() {
                    console.log('Bad Request 400');
                    $('#error-notification').remove();
                    $('#message-line')
                        .after(
                            '<div id="error-notification" class="alert alert-danger alert-dismissible">' +
                            '   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                            '   <h4><i class="icon fa fa-times"></i>Bad request </h4>' +
                            '</div>');
                },
               
                500: function() {
                    console.log('Internal server error 500');
                    $('#error-notification').remove();
                    $('#message-line')
                        .after(
                            '<div id="error-notification" class="alert alert-danger alert-dismissible">' +
                            '   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                            '   <h4><i class="icon fa fa-times"></i>Internal server error </h4>' +
                            '</div>');
                }
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "order_id" },
            { 
                "data": "shipping_address",
                "render": function ( data, type, row, meta ) {
                    return data ? '<span>'+data.firstname + ' ' + data.lastname + '</span>' : '';
                }
            },
            { "data": "email" },
            { "data": "currency_id" },
            { "data": "order_status" },
            { 
                "data": "createdAt",
                "render": function ( data, type, row, meta ) {
                    var date = new Date(data);
                    let year = date.getFullYear();
                    let month = date.getMonth() <= 9 ? `0${date.getMonth()}` : date.getMonth();
                    let day = date.getDate() <= 9 ? `0${date.getDate()}` : date.getDate();
                    let hours = date.getHours() <= 9 ? `0${date.getHours()}` : date.getHours();
                    let minutes = date.getMinutes() <= 9 ? `0${date.getMinutes()}` : date.getMinutes();
                    return data ? '<span>'+  `${year}-${month}-${day} ${hours}:${minutes}` + '</span>' : '';
                }
            },
            { "data": "is_synced" },
            { "data": "sync_result" },
           
        ],
        "columnDefs": [
            {
                "className": "text-center",
                'sortable': false,
                'searchable': true,
                "targets": [0]
            },
            {
                'sortable': false,
                'searchable': true,
                "targets": [1]
            },
            {
                'sortable': false,
                'searchable': true,
                "targets": [2]
            },
            {
                "className": "",
                'sortable': false,
                'searchable': false,
                "targets": [3]
            },
            {
                'sortable': false,
                'searchable': false,
                "targets": [4]
            },
            {
                'sortable': false,
                'searchable': false,
                "targets": [5]
            },
            {
                'sortable': false,
                'searchable': true ,
                "targets": [6]
            },
            {
                'sortable': false,
                'searchable': true ,
                "targets": [7]
            },
        ]
    });

    function reloadTableData() {
        var salesOrderTableDataTable = salesOrderTable.DataTable();
        salesOrderTableDataTable.ajax.reload();
    }
    var socket = io();

    socket.on('test',function(msg){alert(msg)});
    // GENRATE ORDER
    $('#generateOrderBtn').click(function () {
        socket.emit('generate-ten-order', true);
        Swal.fire({
            html: '<h2>Generating<span>...</span></h2>',
            onBeforeOpen:function () {
                Swal.showLoading()
            },
            width: 500,
        });
    });


    //// SYNC ORDER DONE
    socket.on('generate-ten-order-done',function(msg)  {
        Swal.close();
        if(msg){
            Swal.fire(
                {
                    title: 'Generate 10 order successful',
                    type: 'success',
                    confirmButtonClass: 'btn btn-success mt-2',
                    width: 500,
                }
            ).then(function (result) {
                location.reload();
            });
        } else {
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
                confirmButtonClass: 'btn btn-confirm mt-2',
                width: 500,
            }).then(function (result) {
                location.reload();
            });
        }
        
    });


    // SYNC ORDER 
    $('#syncOrderBtn').click(function () {
        socket.emit('let-sync-order', true);
        Swal.fire({
            html: '<h2>Syncing<span>...</span></h2>',
            onBeforeOpen:function () {
                Swal.showLoading()
            },
            width: 500,
        });
    });

    //LISTEN CREATE ORDER RESULT
    socket.emit('listen-sync-order-result', true);
    
    //// SYNC ORDER DONE
    socket.on('sync-order-done',function(msg)  {
        Swal.close();
        if(msg.result){
            Swal.fire(
                {
                    title: 'All orders have been synchronized',
                    type: 'success',
                    width: 500,
                }
            ).then(function (result) {
                // location.reload();
            });
        } else {
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
                width: 500,
            });
            console.log(msg.message);
        }
        
    });

    socket.on('test',function(result)  {
        alert(result);
    });


    //SHOW RESULT
    socket.on('sync-order-result',function(result)  {
        if(result.message){
            console.log(result.message);
            if(result.error){
                var info = result.message.split("|");
                notyf.error({
                    message: `${info[0]}<br>Sync failed: ${info[1]}`,
                    dismissible: true,
                    className: 'toast-sync-order-result'
                })
            } else {
                var info = result.message.split("|");
                notyf.success({
                    message: `<strong>${info[0]}</strong><br>Sync successful: ${info[1]}`,
                    dismissible: true,
                    className: 'toast-sync-order-result'
                })
            }
        } else {
            console.log(result);
        }
    })
});
