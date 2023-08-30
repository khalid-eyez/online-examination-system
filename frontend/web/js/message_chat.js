$('document').ready(function(){
$(function() {
    $('.message').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
           sendTxtMessage($(this).val());
        }
    });
    $('.btnSend').click(function(){
           sendTxtMessage($('.message').val());
    });
    
     
    
    });	///end of jquery
    
  
    function sendTxtMessage(message){
        var messageTxt = message.trim();
        if(messageTxt!=''){
            //console.log(message);
            // DisplayMessage(messageTxt);
            
                    var receiver_id = $('#ReciverId_txt').val();
                    $.ajax({
                              dataType : "json",
                              type : 'post',
                              data : {messageTxt : messageTxt, receiver_id : receiver_id },
                              url: '/instructor/create-chat',
                              success:function(data)
                              {
                                  //GetChatHistory(receiver_id)		 
                              },
                              error: function (jqXHR, status, err) {
                                  // alert('Local error callback');
                              }
                         });
                        
            
            
            ScrollDown();
            $('.message').val('');
            $('.message').focus();
        }else{
            $('.message').focus();
        }
    }
    
})