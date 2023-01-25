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
      /* Begin Filter Data */
    $( "#startDate" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        onClose: function( selectedDate ) {
            var date = $(this).datepicker("getDate");
            if(date != null){
                date.setDate(date.getDate() + 7);
                $("#endDate" ).datepicker("setDate", date);
                $("#endDate" ).datepicker( "option", "minDate", date );
                $("#endDate" ).datepicker( "option", "maxDate", date );
                $.datepicker.formatDate("yy-mm-dd", date);
                $('body').addClass('busy');
                $('#spinner').addClass('spinner');
                filterData(
                  $.datepicker.formatDate("yy-mm-dd", $(this).datepicker("getDate")), 
                  $.datepicker.formatDate("yy-mm-dd", $("#endDate").datepicker("getDate")));
            }
        }
      });
      $( "#endDate" ).datepicker({
        defaultDate: "+1w",
        changeMonth: false,
        numberOfMonths: 1,
        maxDate: '0'
      });

      function filterData(startDate, endDate){
          $.ajax({
              url: "/filter_data",
              type: "POST",
              data: {
                  startDate: startDate,
                  endDate: endDate
              },
              success: function(data){
                  fillData(jQuery.parseJSON(data));
                  $('body').removeClass('busy');
                  $('#spinner').removeClass('spinner');           
              },
              error: function(){

              }
          });
      }

      function fillData(lolo){        
          $.each(lolo,  function(key, val) {
            $.each(val, function( index, value ) {
              $("."+key+index).html(value);
            });
          });
      }
      /* End filter Data */

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
                fillLogs(jQuery.parseJSON(data));
                $('body').removeClass('busy');
                $('#spinner').removeClass('spinner');              
            },
            error: function(){

            }
        });
    }
    function fillLogs(data){
        data.forEach(element => {
            $("#logs")
            .append("<li class='list-group-item'>"+element['topic']+" - "+element['message']+"</li>");
            $('#logs').find(".list-group-item:last").slideDown(20000);
        });

    }
    /* End of importing files */

});