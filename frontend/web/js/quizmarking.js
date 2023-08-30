$(document).ready(function(){

    var file_id="";
    var filename="";
    var record="";
    var pos="";
    var maxima=0; //used by the questions score handler
    var qscores=[];
    var qids=[];
    var obj=$('#fileobj');
    var qid="";
    var currentcourse=$("#coursecode").text();
    var currentassignment=$("#assidt").text();
    var rw="";
    var crow="";
    var colum="";
    var regno="";
    var submit="";
    var quiz="";
    var savedfileid=currentassignment+"fileid";
   file_id=localStorage.getItem(savedfileid);
   record=$('#'+file_id).parent();
   
 

    var last_course=localStorage.getItem(currentcourse);
    var last_ass=localStorage.getItem(currentassignment);
  
  //as the assignment match, we may pick the last entry
  if(currentcourse==last_course && currentassignment==last_ass)
  {
    file_id=localStorage.getItem(savedfileid);
    record=$('#'+file_id).parent();
    rw=$('tr').index(record);
    crow=$("tr:eq("+rw+")");
    filename=crow.children('td').eq(1).attr('id');
    submit=crow.children('td').eq(0).attr('id');
    quiz=crow.children('td').eq(0).attr('quiz');

    //setting the localstorage data
    localStorage.setItem(savedfileid,file_id);
    localStorage.setItem('filename',filename);
    localStorage.setItem(currentcourse,currentcourse);
    localStorage.setItem(currentassignment,currentassignment);
    
    
      record.css('backgroundColor',"lightblue");
      
      regno=crow.children('td').eq(1).text();
      if(filename!=null)
      {
        obj.attr("src","/storage/submit/"+$.trim(filename));
      }
      
      crow.css('backgroundColor',"lightblue");
      putmarkables(submit,quiz);
      animateassheader();
 
        
}
else{
   //gone through the local storage, no last one found, we set up the first record as en entry record
   rw=1;
   crow=$("tr:eq("+rw+")");
   colum=crow.find($('td')[0]);
   file_id=colum.attr('id');
   //filename=colum.text();
   filename = crow.children('td').eq(1).attr('id');
   regno=crow.children('td').eq(1).text();
 
   
   // now displaying the current entry
  if(filename!="" && file_id!=""){
   obj.attr("src","/storage/submit/"+$.trim(filename));
   animateassheader();
   
   crow.css('backgroundColor',"lightblue");
  }
  submit=crow.children('td').eq(0).attr('id');
  quiz=crow.children('td').eq(0).attr('quiz');
  putmarkables(submit,quiz);
}

function animateassheader()
{
      $("#currentass").css("width", "0%");
      $('#currentass').text(regno);
      $('#currentass').css('font-size','0px');
      $("#currentass").animate({width: "90%"},{queue:false});
      $("#currentass").animate({fontSize: "15px"},{queue:false});
}

  //handling enter key clicks: the current entry is recorded and we set up the next one
  $('body').keyup(function(e){
  var kcode = e.keyCode || e.which;
  if(kcode == 13) { 
    //taking all the questions score
    var num_item=$('.qmarking').length;
    for(var t=0;t<num_item;t++)
    {
        if($('.qmarking').eq(t).find('.score'))
        {
         if($('.qmarking').eq(t).find('.score').val()==""){Swal.fire("question no "+(t+1)+" is not marked","","error"); return false;}
         else
         {
            var qm=0;
            if(isNaN(parseFloat($('.qmarking').eq(t).find('.score').val()))){qm=0;}
            else{ qm=$('.qmarking').eq(t).find('.score').val();}
            qid=$('.qmarking').eq(t).find('.score').attr('id');
            qscores.push(qm);
            qids.push(qid);
       }
      }
        
    }
   //end taking all the questions score
   //if(parseFloat($('#scoremark').val())!=maxima && $('#scoremark').val()!=""){Swal.fire("the total score must be equal to the total of questions scores","","error");}
   //else{
   var marks="";
   //we call the marking script for the current entry
   if(file_id!=""){ //test again and again
   if($('#scoremark').val()==""){Swal.fire("please mark each question then click 'enter'","","info");}
   else{
   var tot_marks=parseFloat($('#tot').val());
   if(parseFloat($('#scoremark').val())>tot_marks){Swal.fire("marks provided exceed the maximum scoring","","error"); $('#scoremark').val("");}
   else{
   marks=parseFloat($('#scoremark').val());
   $('.savespin').removeClass('d-none');
   $.post("",{ score:marks, subid:file_id,_csrf: yii.getCsrfToken()},returnAnswer)
   
   //done sending the current user, we point to the next one
   .done(function(){
    getmarkedperc()
   $('.savespin').addClass('d-none');
   record.css('backgroundColor',"");
   
   //console.log($("tr:eq("+rw+")"));
   if($(".mytable tr:eq("+rw+")").is($('.mytable tr').last())){
    Swal.fire("end of the list","","info");
   //emptying them
   $('#scoremark').val("");
   var num_item=$('.qmarking').length;
   for(var t=0;t<num_item;t++)
   {
       
     $('.qmarking').eq(t).find('.score').val("");  
   }
   
   }
   else{
   rw++;
   crow=$("tr:eq("+rw+")");
   colum=crow.children('td').eq(0);
   file_id=colum.attr('id');
   filename = crow.children('td').eq(1).attr('id');
   crow.css('backgroundColor',"lightblue");
   obj.attr("src","/storage/submit/"+$.trim(filename));
   regno=crow.children('td').eq(1).text();
   submit=crow.children('td').eq(0).attr('id');
   quiz=crow.children('td').eq(0).attr('quiz');
   putmarkables(submit,quiz);
   animateassheader();
   
   //Putting the last entry into the local storage
   localStorage.setItem(savedfileid,file_id);
   localStorage.setItem('filename',filename);
   localStorage.setItem(currentcourse,currentcourse);
   localStorage.setItem(currentassignment,currentassignment);
   
   $('#scoremark').val("");
   //emptying the questions score
   maxima=0; 
   var num_item=$('.qmarking').length;
    for(var t=0;t<num_item;t++)
    {
        
      $('.qmarking').eq(t).find('.score').val("");  
    }
   }
   //emptying the scores buffer
   qscores=[];
   })
   .fail(function(data) {
   $('.savespin').addClass('d-none');
   Swal.fire("Error",data.responseText,"error");
   //return false;
   })
   .always(function() {
   //Putting the last entry into the local storage
   $('.savespin').addClass('d-none');
   localStorage.setItem(savedfileid,file_id);
   localStorage.setItem('filename',filename);
   localStorage.setItem(currentcourse,currentcourse);
   localStorage.setItem(currentassignment,currentassignment);
   
   $('#scoremark').val("");
   //emptying the questions score
   maxima=0; 
   var num_item=$('.qmarking').length;
    for(var t=0;t<num_item;t++)
    {
        
      $('.qmarking').eq(t).find('.score').val("");  
    }
   });
   function returnAnswer(answer)
   {
   
   }
   crow.css("background-color","");
   }
   
   }
   }
  
  }
 
  });
 //Save and move button clicked
 var numsaveclicks=0;
 $('#savemove').click(function(){
  numsaveclicks++;
 //taking all the questions score
 var num_item=$('.qmarking').length;
 for(var t=0;t<num_item;t++)
 {
     if($('.qmarking').eq(t).find('.score'))
     {
      if($('.qmarking').eq(t).find('.score').val()==""){Swal.fire("question no "+(t+1)+" is not marked","","error"); return false;}
      else
      {
         var qm=0;
         if(isNaN(parseFloat($('.qmarking').eq(t).find('.score').val()))){qm=0;}
         else{ qm=$('.qmarking').eq(t).find('.score').val();}
         qid=$('.qmarking').eq(t).find('.score').attr('id');
         qscores.push(qm);
         qids.push(qid);
    }
   }
     
 }
//end taking all the questions score
//if(parseFloat($('#scoremark').val())!=maxima && $('#scoremark').val()!=""){Swal.fire("the total score must be equal to the total of questions scores","","error");}
//else{
var marks="";
//we call the marking script for the current entry
if(file_id!=""){ //test again and again
if($('#scoremark').val()==""){Swal.fire("please mark each question then click 'enter'","","info");}
else{
var tot_marks=parseFloat($('#tot').val());
if(parseFloat($('#scoremark').val())>tot_marks){Swal.fire("marks provided exceed the maximum scoring","","error"); $('#scoremark').val("");}
else{
marks=parseFloat($('#scoremark').val());
$('.savespin').removeClass('d-none');
$.post("",{ score:marks, subid:file_id,_csrf: yii.getCsrfToken()},returnAnswer)

//done sending the current user, we point to the next one
.done(function(){
  getmarkedperc();
$('.savespin').addClass('d-none');
record.css('backgroundColor',"");
//console.log($("tr:eq("+rw+")"));
if($(".mytable tr:eq("+rw+")").is($('.mytable tr').last())){
 Swal.fire("end of the list","","info");
//emptying them
$('#scoremark').val("");
$('.comment').val("");
var num_item=$('.qmarking').length;
for(var t=0;t<num_item;t++)
{
    
  $('.qmarking').eq(t).find('.score').val("");  
}

}
else{
rw++;
crow=$("tr:eq("+rw+")");
colum=crow.children('td').eq(0);
file_id=colum.attr('id');
filename = crow.children('td').eq(1).attr('id');
crow.css('backgroundColor',"lightblue");
obj.attr("src","/storage/submit/"+$.trim(filename));
regno=crow.children('td').eq(1).text();
submit=crow.children('td').eq(0).attr('id');
quiz=crow.children('td').eq(0).attr('quiz');
putmarkables(submit,quiz);
animateassheader();

//Putting the last entry into the local storage
localStorage.setItem(savedfileid,file_id);
localStorage.setItem('filename',filename);
localStorage.setItem(currentcourse,currentcourse);
localStorage.setItem(currentassignment,currentassignment);

$('#scoremark').val("");
//emptying the questions score
maxima=0; 
var num_item=$('.qmarking').length;
 for(var t=0;t<num_item;t++)
 {
     
   $('.qmarking').eq(t).find('.score').val("");  
 }
}
//emptying the scores buffer
qscores=[];
})
.fail(function(data) {
$('.savespin').addClass('d-none');
Swal.fire("Error",data.responseText,"error");
//return false;
})
.always(function() {
//Putting the last entry into the local storage
$('.savespin').addClass('d-none');
localStorage.setItem(savedfileid,file_id);
localStorage.setItem('filename',filename);
localStorage.setItem(currentcourse,currentcourse);
localStorage.setItem(currentassignment,currentassignment);

$('#scoremark').val("");
//emptying the questions score
maxima=0; 
var num_item=$('.qmarking').length;
 for(var t=0;t<num_item;t++)
 {
     
   $('.qmarking').eq(t).find('.score').val("");  
 }
});
function returnAnswer(answer)
{

}
crow.css("background-color","");
}

}
}


//}
  
  if(numsaveclicks==3){Swal.fire("Did you know?","Using keyboard \"ENTER KEY\" may make you mark more faster and become more productive","info")}
  });
 //handling next arrow key clicked;
$('body').keyup(function(e){
var code=e.keyCode || e.which;
if(code==39 || code==40)
{
 crow.css('backgroundColor',"");
 if($(".mytable tr:eq("+rw+")").is($('.mytable tr').last())){
   Swal.fire("end of the list","","info");
  //emptying them
  $('#scoremark').val("");
  $('.comment').val("");
  var num_item=$('.qmarking').length;
  for(var t=0;t<num_item;t++)
  {
      
    $('.qmarking').eq(t).find('.score').val("");  
  }
 
  }
 else{rw++; crow=$("tr:eq("+rw+")");}
 colum=crow.children('td').eq(0);
 file_id=colum.attr('id');
 filename=crow.children('td').eq(1).attr('id');
 
 crow.css('backgroundColor',"lightblue");
 if($.trim(filename)!="")
 {
 obj.attr("src","/storage/submit/"+$.trim(filename));
 }
 //emptying them

 $('#scoremark').val("");
 $('.comment').val("");
 var num_item=$('.qmarking').length;
 for(var t=0;t<num_item;t++)
 {
   $('.qmarking').eq(t).find('.score').val("");  
 }
 regno=crow.children('td').eq(1).text();
 submit=crow.children('td').eq(0).attr('id');
 quiz=crow.children('td').eq(0).attr('quiz');
 putmarkables(submit,quiz);
 animateassheader();
 
 
 //Putting the last entry into the local storage
 localStorage.setItem(savedfileid,file_id);
 localStorage.setItem('filename',filename);
 localStorage.setItem(currentcourse,currentcourse);
 localStorage.setItem(currentassignment,currentassignment);
 
 crow=$("tr:eq("+rw+")"); //set the current row
 $('#scoremark').val("");
}
 });
 
 //Next button clicked
 var numnextclicks=0;
$('#skipnext').click(function(){
  numnextclicks++;
   crow.css('backgroundColor',"");
   if($(".mytable tr:eq("+rw+")").is($('.mytable tr').last())){
     Swal.fire("end of the list","","info");
    //emptying them
    $('#scoremark').val("");
    var num_item=$('.qmarking').length;
    for(var t=0;t<num_item;t++)
    {
        
      $('.qmarking').eq(t).find('.score').val("");  
    }
   
    }
   else{rw++; crow=$("tr:eq("+rw+")");}
   colum=crow.children('td').eq(0);
   file_id=colum.attr('id');
   filename=crow.children('td').eq(1).attr('id');
   crow.css('backgroundColor',"lightblue");
   if($.trim(filename)!="")
   {
   obj.attr("src","/storage/submit/"+$.trim(filename));
   }
   //emptying them
  
   $('#scoremark').val("");
   $('.comment').val("");
   var num_item=$('.qmarking').length;
   for(var t=0;t<num_item;t++)
   {
       
     $('.qmarking').eq(t).find('.score').val("");  
   }
   regno=crow.children('td').eq(1).text();
   submit=crow.children('td').eq(0).attr('id');
   quiz=crow.children('td').eq(0).attr('quiz');
   putmarkables(submit,quiz);
   animateassheader();
   
   
   //Putting the last entry into the local storage
   localStorage.setItem(savedfileid,file_id);
   localStorage.setItem('filename',filename);
   localStorage.setItem(currentcourse,currentcourse);
   localStorage.setItem(currentassignment,currentassignment);
   
   crow=$("tr:eq("+rw+")"); //set the current row
   $('#scoremark').val("");
   $('.comment').val("");
   if(numnextclicks==3){Swal.fire("Did you know?","Using keyboard \"ARROW KEYS\" may make you more faster and productive","info")}
   });
 
 //prev arrow key clicked
 $('body').keyup(function(d){
var code=d.keyCode || d.which;
if(code==37 || code==38)
{
 crowpr=$("tr:eq("+rw+")");
 if($(".mytable tr:eq("+rw+")").is($('.mytable tr:eq(1)'))){
    Swal.fire("no previous record","","info");
    $('#scoremark').val("");
    $('.comment').val("");
    var num_item=$('.qmarking').length;
    for(var t=0;t<num_item;t++)
    {
        
      $('.qmarking').eq(t).find('.score').val("");  
    }
    $('.comment').val("");
   }
 else{rw--; crow=$("tr:eq("+rw+")");}
 crowpr.css("background-color","");
 colum=crow.children('td').eq(0);
 file_id=colum.attr('id');
 filename=crow.children('td').eq(1).attr('id');
 crow.css('backgroundColor',"lightblue");
 if($.trim(filename)!="")
 {
 obj.attr("src","/storage/submit/"+$.trim(filename));
 }
 //emptying them
 $('#scoremark').val("");
 $('.comment').val("");
 var num_item=$('.qmarking').length;
 for(var t=0;t<num_item;t++)
 {  
   $('.qmarking').eq(t).find('.score').val("");  
 }
 regno=crow.children('td').eq(1).text();
 submit=crow.children('td').eq(0).attr('id');
 quiz=crow.children('td').eq(0).attr('quiz');
 putmarkables(submit,quiz);
 animateassheader();
 //Putting the last entry into the local storage
 localStorage.setItem(savedfileid,file_id);
 localStorage.setItem('filename',filename);
 localStorage.setItem(currentcourse,currentcourse);
 localStorage.setItem(currentassignment,currentassignment);
 
 crow=$("tr:eq("+rw+")"); //set the current row
 //crow.css("background-color","");
 $('#scoremark').val("");
 $('.comment').val("");
}
 });

 // prev button clicked
  var numbackclicks=0;
  //handling prev 
  $('#skipback').click(function(){
    numbackclicks++;
     crowpr=$("tr:eq("+rw+")");
     if($(".mytable tr:eq("+rw+")").is($('.mytable tr:eq(1)'))){
        Swal.fire("no previous record","","info");
        $('#scoremark').val("");
        $('.comment').val("");
        var num_item=$('.qmarking').length;
        for(var t=0;t<num_item;t++)
        { 
          $('.qmarking').eq(t).find('.score').val("");  
        }
       }
     else{rw--; crow=$("tr:eq("+rw+")");}
     crowpr.css("background-color","");
     colum=crow.children('td').eq(0);
     file_id=colum.attr('id');
     filename=crow.children('td').eq(1).attr('id');
     crow.css('backgroundColor',"lightblue");
     if($.trim(filename)!="")
     {
     obj.attr("src","/storage/submit/"+$.trim(filename));
     }
     //emptying them
     $('#scoremark').val("");
     $('.comment').val("");
     var num_item=$('.qmarking').length;
     for(var t=0;t<num_item;t++)
     {
         
       $('.qmarking').eq(t).find('.score').val("");  
     }
     regno=crow.children('td').eq(1).text();
     submit=crow.children('td').eq(0).attr('id');
     quiz=crow.children('td').eq(0).attr('quiz');
     putmarkables(submit,quiz);
     animateassheader();
     
     
     //Putting the last entry into the local storage
     localStorage.setItem(savedfileid,file_id);
     localStorage.setItem('filename',filename);
     localStorage.setItem(currentcourse,currentcourse);
     localStorage.setItem(currentassignment,currentassignment);
     
     crow=$("tr:eq("+rw+")"); //set the current row
     //crow.css("background-color","");
     $('#scoremark').val("");
     $('.comment').val("");
    
     if(numbackclicks==3){Swal.fire("Did you know?","Using keyboard \"ARROW KEYS\" may make you more faster and productive","info")}
     });
 //row clicking handler
 $('tr').click(function(){

  if(!($(this).is($('.mytable tr:eq(0)')))){
   
 crow.css("background-color",""); //the current record is cleared
 rw=$('tr').index($(this));
 file_id=$(this).children('td').eq(0).attr('id');
 filename=$(this).children('td').eq(1).attr('id');
 regno=$(this).children('td').eq(1).text();
 crow=$("tr:eq("+rw+")");
 submit=$(this).children('td').eq(0).attr('id');
 quiz=$(this).children('td').eq(0).attr('quiz');
 //updating UI
 obj.attr("src","/storage/submit/"+$.trim(filename));
 putmarkables(submit,quiz);
 animateassheader();
 //showing the clicked record
 crow.css('backgroundColor',"lightblue");
 
 $('#scoremark').val("");
 $('.comment').val("");
 
 
  }
 
 })
 
 

 //putmarkables(submit,quiz);
 
 
 //handling questions marking
 
 
    
    $('.markables').on('keyup','input',function(){
    var currentscore=!isNaN(parseFloat($(this).val()))?parseFloat($(this).val()):"";
    var maxscore=parseFloat($(this).parent().find('.maxscore').val());
    if(!isNaN(maxscore))
    {
    if(currentscore<=maxscore)
    {
    var num_item=$('.qmarking').length;

    
    //var max=$('#maxima input').length;
    var total_marks=0;
    var totalquestionsscore=0;
    for(var t=0;t<num_item;t++)
    {
     var qm=0;
     
    if(isNaN(parseFloat($('.qmarking').eq(t).find('.score').val()))){
      qm=0;
    }
    else{ 
      qm=parseFloat($('.qmarking').eq(t).find('.score').val());
    }

    total_marks=total_marks+qm;   
    }
    if(isNaN(total_marks)){total_marks=parseFloat(total_marks);}
    $('#scoremark').val(total_marks);
    maxima=total_marks;
   }
   else
   {
      Swal.fire("","exceeding the max question scoring"+maxscore,"error");
      $(this).val("");
   }
  }
  else
  {
    var num_item=$('.qmarking').length;

    
    //var max=$('#maxima input').length;
    var total_marks=0;
    var totalquestionsscore=0;
    for(var t=0;t<num_item;t++)
    {
     var qm=0;
     
    if(isNaN(parseFloat($('.qmarking').eq(t).find('.score').val()))){
      qm=0;
    }
    else{ 
      qm=parseFloat($('.qmarking').eq(t).find('.score').val());
    }

    total_marks=total_marks+qm;   
    }
    if(isNaN(total_marks)){total_marks=parseFloat(total_marks);}
    $('#scoremark').val(total_marks);
    maxima=total_marks;
  }
   })
    
   $('#scoremark').blur(function(){
    if(parseFloat($('#scoremark').val())!=maxima || isNaN(parseFloat($('#scoremark').val())))
    {
        //alert(isNaN(parseFloat($('#scoremark').val())));
        Swal.fire("","the total score must be equal to the total of questions scores","error");
        if(maxima>0){$('#scoremark').val(maxima);}else{$('#scoremark').val("");$('.comment').val("");}
    }
     
 
 
 
 
   });
 
 //draggable marking controls

 $('#markcontrol2').draggable({cursor: 'move',containment: 'window',cancel:'.btn'});

 //on presentation mode the view should be draggable

 $('#presentationmodeviewer').draggable({cursor: 'move',containment: 'window',cancel:'.btn'});

 
 var height=$('.studenttable').parent().css('height');
 $('.studenttable').css('max-height',height);

 //getting the percentage of the marked assignments
 function getmarkedperc()
 {
   var data={
     assessmentID:currentassignment
   }
   data[yii.getCsrfParam()]=yii.getCsrfToken();
 $.post("get-marked-perc",data)
 .done(function(an){
  $('#markedperc').text(an+ "%");
 })
}
getmarkedperc();

function putmarkables(sub,quiz)
{
  
 var data={
   submit:sub,
   quiz:quiz
 }
 data[yii.getCsrfParam()]=yii.getCsrfToken();
$.post("get-markables",data)
.done(function(an){
 markablecontentall="";
 var i=0;
 var tot=0;
 if(an.markables.markablesnum!=null || an.markables.markablesnum!=0)
 {
 for(var index in an.markables.markables)
 {
  
  var question=an.markables.markables[index];
  tot+=parseFloat(question.score_correct);
  i++;
  var markablecontent='<div id="marks" class="row qmarking">';
  markablecontent+='<div id="mrow" class="col-md-12" style="margin-top:7px">';
  markablecontent+='<div class="form-group">';
  markablecontent+='<input type="text" class="form-control score text-bold text-center" id="" placeholder="Q'+(question.qnum)+' ['+question.score_correct+']['+question.score_incorrect+']" value="">';
  markablecontent+='</input><input type="text" class="form-control maxscore text-center text-bold" value="'+question.score_correct+'" readonly></input></div>';
  markablecontent+='</div>';
  markablecontent+='</div>';
  markablecontentall+=markablecontent;
 }
 $('#tot').val(tot);
 $('.markables').html(markablecontentall);
}
})


}

 });