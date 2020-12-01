; (function ($) {
    $(document).ready(function () {
        $(".action-button").on('click', function (event) {
            event.preventDefault();
            let task = $(this).data('task');
            window[task]();
        });
});
})(jQuery);


function loco_ajax_call(){
    let $=jQuery;
    var subscribeData =$('#subscription-form').serialize();
    //submit form using ajax
    $.post(
        locoData.ajax_url,
        { 'action': 'loco_email_response', 'data': subscribeData },function(response){

            if(response=="You are now subscribed"){
                //if success 
                $('#fomr-msg').removeClass('error');
                $('#fomr-msg').addClass('success');
                //set message text
                $('#form-msg').text(response);
                //clear field
                $('#name').val('');
                $('#email').val('');
            }else{
                //if error 
                $('#fomr-msg').removeClass('success');
                $('#fomr-msg').addClass('error');
                if(response.responseText!==''){
                    //set message text
                    $('#form-msg').text(response.responseText);
                }else{
                    //set message text
                    $('#form-msg').text('Message Not sent');
                }
                
            }
        }
    );
}

