$(document).ready(function(){

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

    $('#import').on('click', function(){
        $('body').addClass('busy');
        $('#spinner').addClass('spinner');
        uploadFile();
    });
    
    function uploadFile(){
        $.ajax({
            url: "/import",
            type: "POST",
            data:  $("#formFile").val(),
        });
    }

});