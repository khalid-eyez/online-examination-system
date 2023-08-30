$('document').ready(function(){



    //the labels
    
    $('label').css('fontSize','11px');
      //handling assignment questions

      //initially
      $('#questions').html("");
      var noq=parseInt($('#qnumber').val());
      //alert(noq);
      
      for(var n=1; n<=noq; n++){
      var d="q"+n;
      var qobj='<input id="q'+n+'" class="form-control" type="text" placeholder="Q'+n+'" name="q_max[]" style="width:50px;height:50px;border-color:#ccc; border-radius:7px;float:left;margin:1px;font-size:12px" />';
      $('#questions').append(qobj);
      
    
   
      }
      $('#qnumber').keyup(function(){
        //$('#totm').val(0);
       $('#questions').html("");
       var noq=parseInt($(this).val());
       for(var n=1; n<=noq; n++){
       var d="q"+n;
       var qobj='<input id="q'+n+'" class="form-control" type="text" placeholder="Q'+n+'" name="q_max[]" style="width:50px;height:50px;border-color:#ccc; border-radius:7px;float:left;margin:1px;font-size:12px" />';
       $('#questions').append(qobj);
       
       //adding there validation
       $("#assform").yiiActiveForm("add",{
             'id': 'q'+n, 
             'name': 'q_max[]',
             'container': '.questions',
             'input': '#q'+n,
             "error": ".help-block",
             "validate": function(attribute, value, messages, deferred,$form) {
    
                 yii.validation.required(value, messages, {
                     "message": "cannot be blank."
                 });
             }
         });
    
       }
       
       })
       
       var maxima=0;
       
       $('#questions').on('keyup','input',function(){
       var num_item=$('#questions').children().length;
       var total_marks=0;
       for(var t=0;t<num_item;t++)
       {
           
           
               var qm=0;
               if(isNaN(parseFloat($('#questions').children().eq(t).val()))){qm=0;}
               else{ qm=parseFloat($('#questions').children().eq(t).val());}
               total_marks=total_marks+qm;
        
             
           
       }
       
       if(isNaN(total_marks)){total_marks=parseFloat(total_marks);}
       $('#totm').val(total_marks);
       maxima=total_marks;
    
       })
       
      $('#totm').blur(function(){
    
     if($('#qnumber').val()!="" && parseInt($('#qnumber').val())>0)
     {
       
       if(parseFloat($('#totm').val())!=maxima)
       {
        Swal.fire(
                  'input error!',
                  'the total maxima must be equal to the total of all questions maxima',
                  'error'
        );
           if(maxima>0){ $('#totm').val(maxima); }else{ $('#totm').val(''); }
       }
        
    
     }
    
      });
    
      //####################handling the assignment format
    
      $('#assFormat').change(function(){
      var selected=$(this).val();
    
      if(selected=="typed")
      {
       
       var content='<textarea class="form-control form-control-sm" id="the_assignment" placeholder="Type your assignment" name="the_assignment" style="height:400px"></textarea>';
       if($('#filechoose').length<=0)
       {
        $('#assformatt').css('height','fit-content');
        $('#assformatt').css('height','-moz-fit-content');
        $('#assformatt').css('height','-webkit-fit-content');
       $('#assformatt').append(content);
       
      //adding validations
      $("#assform").yiiActiveForm("add",{
      'id': 'the_assignment', 
      'name': 'the_assignment',
      'container': '#assformatt',
      'input': '#the_assignment',
      "error": ".invalid-feedback",
      "validate": function(attribute, value, messages, deferred,$form) {
    
          yii.validation.required(value, messages, {
              "message": "assignment cannot be blank."
          });
      }
      });
       }
       else
       {
        $('#filechoose').replaceWith(content);
        //validations
        $("#assform").yiiActiveForm("add",{
          'id': 'the_assignment', 
          'name': 'the_assignment',
          'container': '#assformatt',
          'input': '#the_assignment',
          "error": ".invalid-feedback",
          "validate": function(attribute, value, messages, deferred,$form) {
    
          yii.validation.required(value, messages, {
              "message": "assignment cannot be blank."
          });
      }
      });
       }
    
    
      }
      else
      {
        var content='<p id="filechoose"><label for="customFile" class="custom-file-label col-form-label-sm">select assignment file</label><input type="file" id="customfile" class="form-control form-control-sm custom-file-input" name="assFile"></input></p>';
        if($('#the_assignment').length<=0)
       {
        $('#assformatt').append(content);
        $('#assformatt').css('height','fit-content');
        $('#assformatt').css('height','-moz-fit-content');
        $('#assformatt').css('height','-webkit-fit-content');
        //the validations
        $("#assform").yiiActiveForm("add",{
          'id': 'customfile', 
          'name': 'assFile',
          'container': '#filechoose',
          'input': '#customfile',
          "error": ".invalid-feedback",
          "validate": function(attribute, value, messages, deferred,$form) {
    
          yii.validation.required(value, messages, {
              "message": "assignment cannot be blank."
          });
      }
      });
    
       }
       else
       {
        $('#the_assignment').replaceWith(content);
            //the validations
        $("#assform").yiiActiveForm("add",{
          'id': 'customfile', 
          'name': 'assFile',
          'container': '#filechoose',
          'input': '#customfile',
          "error": ".invalid-feedback",
          "validate": function(attribute, value, messages, deferred,$form) {
    
          yii.validation.required(value, messages, {
              "message": "assignment cannot be blank."
          });
      }
      });
       }
      }
    
      });
    
      //the end of questions handling###############################
    
      //handling the assignment chosing
    
      $('#asstypearea').on('change','#asstype',function(){
      var selected=$(this).val();
      
      if(selected=="groups"){
    
        if($('#students2').length>0)
        {
          $('#students2').parent().remove();
        }
    
      //creating the generation type element
      $.ajax({
                    url: '/instructor/get-gentypes',
                    type: "POST",
                    data:"hey",  
                    error: function(xhr,tStatus,e){
                        if(!xhr){
                            alert(tStatus+"   "+e.message);
                        }else{
                            alert("else: "+e.message); 
                        }
                        },
                    success: function(resp){
                      var data=$.parseJSON(resp);
                      var genelem='<div class="col-md-4"><label for="gentypes" style="font-size:11px">Generation types</label><select id="gentypes" name="gentypes" class="form-control form-control-sm">';
                      var genelemmid='<option value="0" disabled selected>--select generation type--</option>';
                      var genelem2='</select></div>';
    
                      for(var item in data)
                      {
                        var option='<option value="'+item+'">'+data[item]+'</option>';
                            genelemmid+=option;
    
                      }
    
                      genelem=genelem+genelemmid+genelem2;
    
                        if($('#gentypes').length<=0)
                        {
                        $('#asstypearea').append(genelem);
                     
                         //adding there validation
                          $("#assform").yiiActiveForm("add",{
                                'id': 'gentypes', 
                                'name': 'gentypes',
                                'container': '#asstypearea',
                                'input': '#gentypes',
                                "error": ".help-block",
                                "validate": function(attribute, value, messages, deferred,$form) {
    
                                    yii.validation.required(value, messages, {
                                        "message": "generation type cannot be blank."
                                    });
                                }
                                });
                        }
    
                        }
                    });
      }
      else if(selected=="allgroups")
      {
                       if($('#gengroups').length>0)
                        {
                          $('#gengroups').parent().remove();
    
                        }
                        if($('#students2').length>0)
                        {
                          $('#students2').parent().remove();
                        }
                   
    
         //creating the generation type element
      $.ajax({
                    url: '/instructor/get-gentypes',
                    type: "POST",
                    data:"",  
                    error: function(xhr,tStatus,e){
                        if(!xhr){
                            alert(tStatus+"   "+e.message);
                        }else{
                            alert("else: "+e.message); 
                        }
                        },
                    success: function(resp){
                      var data=$.parseJSON(resp);
                      var genelem='<div class="col-md-4"><label for="gentypes" style="font-size:11px">Generation types</label><select id="gentypes" name="gentypes" class="form-control form-control-sm">';
                      var genelemmid='<option value="0" disabled selected>--select generation type--</option>';
                      var genelem2='</select></div>';
    
                      for(var item in data)
                      {
                        var option='<option value="'+item+'">'+data[item]+'</option>';
                            genelemmid+=option;
    
                      }
    
                      genelem=genelem+genelemmid+genelem2;
    
                        if($('#gentypes').length<=0)
                        {
                        $('#asstypearea').append(genelem);
                       
                        //validation
                        $("#assform").yiiActiveForm("add",{
                                'id': 'gentypes', 
                                'name': 'gentypes',
                                'container': '#asstypearea',
                                'input': '#gentypes',
                                "error": ".help-block",
                                "validate": function(attribute, value, messages, deferred,$form) {
    
                                    yii.validation.required(value, messages, {
                                        "message": "generation type cannot be blank."
                                    });
                                }
                                });
                        }
    
                        }
                    });
    
    
    
      }
      else if(selected=="allstudents")
      {
        if($('#gengroups').length>0)
        {
          $('#gengroups').parent().remove();
    
        }
        if($('#gentypes').length>0)
        {
          $('#gentypes').parent().remove();
    
        }
        if($('#students2').length>0)
        {
          $('#students2').parent().remove();
        }
    
      }
      else if(selected=="students"){
    
        if($('#gengroups').length>0)
        {
          $('#gengroups').parent().remove();
    
        }
        if($('#gentypes').length>0)
        {
          $('#gentypes').parent().remove();
    
        }
         
       //finding all students for this course
    
       $.ajax({
                    url: '/instructor/get-students',
                    type: "POST",
                    data:"",  
                    error: function(xhr,tStatus,e){
                        if(!xhr){
                            alert(tStatus+"   "+e.message);
                        }else{
                            alert("else: "+e.message); 
                        }
                        },
                    success: function(resp){
                      var data=$.parseJSON(resp);
                      var stdelem='<div class="col-md-4" id="studentarea"><label for="students2" style="font-size:11px">Students</label><select id="students2" name="mystudents[]" class="form-control form-control-sm" multiple="multiple" data-placeholder="Select students">';
                      var stdelemmid='';
                      var stdelem2='</select></div>';
    
                      for(var item in data)
                      {
                        var option='<option value="'+item+'">'+item+'</option>';
                        stdelemmid+=option;
    
                      }
    
                      stdelem=stdelem+stdelemmid+stdelem2;
                        if($('#students2').length<=0)
                        {
                        $('#asstypearea').append(stdelem);
                        $('#students2').select2();
                        $("#assform").yiiActiveForm("add",{
                                'id': 'students2', 
                                'name': 'mystudents[]',
                                'container': '#studentarea',
                                'input': '#students2',
                                "error": ".help-block",
                                "validate": function(attribute, value, messages, deferred,$form) {
    
                                    yii.validation.required(value, messages, {
                                        "message": "students cannot be blank."
                                    });
                                }
                                });
                        }
                    
    
                        }
                    });
    
    
      }
      else
      {
        if($('#gengroups').length>0)
        {
          $('#gengroups').parent().remove();
    
        }
        if($('#gentypes').length>0)
        {
          $('#gentypes').parent().remove();
    
        }
        if($('#students2').length>0)
        {
          $('#students2').parent().remove();
        }
      }
      });
      //adding select 2 effects
    
      //the groups
      $('#asstypearea').on('change','#gentypes',function(){
      var selected=parseInt($(this).val());
      if($('#asstype').val()=="groups"){
        
      //creating the generation type element
        //creating the generation type element
        $.ajax({
                    url: '/instructor/get-groups',
                    type: "GET",
                    data:{'genid':selected},  
                    error: function(xhr,tStatus,e){
                        if(!xhr){
                            alert(tStatus+"   "+e.message);
                        }else{
                            alert(" "+e.message); 
                        }
                        },
                    success: function(resp){
                      var data=$.parseJSON(resp);
                      var grpelem='<div class="col-md-4" id="groups"><label for="gengroups" id="grplabel" style="font-size:11px">Groups</label><select id="gengroups" name="gengroups[]" data-placeholder="Select groups" class="form-control form-control-sm mysel select2"  multiple="multiple">';
                      var grpelemmid='';
                      var grpelem2='</select></div>';
    
                      for(var item in data)
                      {
                        var option='<option value="'+item+'">'+data[item]+'</option>';
                        grpelemmid+=option;
    
                      }
    
                      grpelem=grpelem+grpelemmid+grpelem2;
                      if(selected!=0){
                      if($('#gengroups').length<=0)
                        {
                        $('#asstypearea').append(grpelem);
                        $('.mysel').select2();
                        $("#assform").yiiActiveForm("add",{
                                'id': 'gengroups', 
                                'name': 'gengroups[]',
                                'container': '#asstypearea',
                                'input': '#gengroups',
                                "error": ".help-block",
                                "validate": function(attribute, value, messages, deferred,$form) {
    
                                    yii.validation.required(value, messages, {
                                        "message": "groups cannot be blank."
                                    });
                                }
                                });
    
                        }
                        else
                        {
                          $('#groups').replaceWith(grpelem);
                          $('.mysel').select2();
                          $("#assform").yiiActiveForm("add",{
                                'id': 'gengroups', 
                                'name': 'gengroups[]',
                                'container': '#asstypearea',
                                'input': '#gengroups',
                                "error": ".help-block-error",
                                "validate": function(attribute, value, messages, deferred,$form) {
    
                                    yii.validation.required(value, messages, {
                                        "message": "groups cannot be blank."
                                    });
                                }
                                });
                        }
                        }
                        else
                        {
                        if($('#gengroups').length>0)
                        {
                          $('#groups').remove();
    
                        }
                        }
                    }
                    });
    
     
    
      }
      else
      {
        if($('#gengroups').length>0)
        {
          $('#gengroups').remove();
        }
      }
    
    
    
      })
      $('#select2').select2();
      $('#assignstudents').select2();
      $('#assignstudents2').select2();
      $('#assignstudents3').select2();
      
    
    })