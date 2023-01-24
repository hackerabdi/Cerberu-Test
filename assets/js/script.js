$(document).ready(function(){

    var toastMixin = Swal.mixin({
        toast: true,
        icon: 'success',
        title: 'General Title',
        animation: false,
        position: 'top-right',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      });

    $( "#startDate" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function( selectedDate ) {
            var date = $(this).datepicker("getDate");
            date.setDate(date.getDate() + 7);
            $("#endDate" ).datepicker("setDate", date);
            $("#endDate" ).datepicker( "option", "minDate", date );
            $("#endDate" ).datepicker( "option", "maxDate", date );
        }
      });
      $( "#endDate" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1,
        maxDate: '0'
      });

    /* Begin importing files */  
    $('#import').on('click', function(){
        $('body').addClass('busy');
        $('#spinner').addClass('spinner');
        $("#logs").html('');
        import_files();
    });
    
    function import_files(){
     
        $.ajax({
            url: "/import",
            type: "POST",
            success: function(data){
                fillControl(jQuery.parseJSON(data));
                $('body').removeClass('busy');
                $('#spinner').removeClass('spinner');              
            },
            error: function(){

            }
        });
    }
    function fillControl(data){
        data.forEach(element => {
            $("#logs")
            .append("<li class='list-group-item'>"+element['topic']+" - "+element['message']+"</li>");
            $('#logs').find(".list-group-item:last").slideDown(20000);
        });

    }

    /* End of importing files */

});