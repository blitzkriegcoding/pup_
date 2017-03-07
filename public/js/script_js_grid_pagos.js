function getPaymentsExcel()
{
    
    $.ajax({
        type: "POST",
        url: "/admin/get_payments",
        data: {'dt_start': $('#date_start').val(), 'dt_end':$('#date_end').val() },
        beforeSend: function() 
            {
                $("#download_button").empty();
            },                         
        statusCode: {
            200: function()
            {
                $("#download_button").append("<a href='#' class='btn btn-success' target='_self'> Descargar Pagos </a>");
            }        
        },
        statusCode: {
            422:function(data)
            {
                $.each(data.responseJSON, function(i,k){                                    
                    toastr.error(k);
                });                
            }
        }
        statusCode: {
            500: function(data)
            {
                toastr.error('Ha ocurrido un error interno en el servidor, favor contacte a su administrador');
            }
        }

    });
}

