$('document').ready(function(){



    //the labels
    
    $('label').css('fontSize','11px');
      //handling assignment questions
      $('#labqnumber').keyup(function(){
        //$('#totm').val(0);
       $('#labquestions').html("");
       var noq=parseInt($(this).val());
       for(var n=1; n<=noq; n++){
       var d="q"+n;
       var qobj='<input id="q'+n+'" class="form-control" type="text" placeholder="Q'+n+'" name="q_max[]" style="width:50px;height:50px;border-color:#ccc; border-radius:7px;float:left;margin:1px;font-size:12px" />';
       $('#labquestions').append(qobj);
       
       //adding there validation
       $("#labform").yiiActiveForm("add",{
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
       
       $('#labquestions').on('keyup','input',function(){
       var num_item=$('#labquestions').children().length;
       var total_marks=0;
       for(var t=0;t<num_item;t++)
       {
           
           
               var qm=0;
               if(isNaN(parseFloat($('#labquestions').children().eq(t).val()))){qm=0;}
               else{ qm=parseFloat($('#labquestions').children().eq(t).val());}
               total_marks=total_marks+qm;
        
             
           
       }
       
       if(isNaN(total_marks)){total_marks=parseFloat(total_marks);}
       $('#labtotm').val(total_marks);
       maxima=total_marks;
    
       })
       
      $('#labtotm').blur(function(){
    
     if($('#labqnumber').val()!="" && parseInt($('#labqnumber').val())>0)
     {
       
       if(parseFloat($('#labtotm').val())!=maxima)
       {
        Swal.fire(
                  'input error!',
                  'the total maxima must be equal to the total of all questions maxima',
                  'error'
        );
           if(maxima>0){ $('#labtotm').val(maxima); }else{ $('#labtotm').val(''); }
       }
        
    
     }
    
      });
    
      //####################handling the assignment format
    
      $('#labFormat').change(function(){
      var selected=$(this).val();
    
      if(selected=="typed")
      {
       
       var content='<textarea class="form-control form-control-sm" id="the_lab" placeholder="Type your assignment" name="the_assignment" style="height:300px"></textarea>';
       if($('#labfilechoose').length<=0)
       {
       $('#labformatt').css('height','fit-content');
       $('#labformatt').css('height','-moz-fit-content');
       $('#labformatt').css('height','-webkit-fit-content');
       $('#labformatt').append(content);
       
      //adding validations
      $("#labform").yiiActiveForm("add",{
      'id': 'the_lab', 
      'name': 'the_assignment',
      'container': '#labformatt',
      'input': '#the_lab',
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
        $('#labfilechoose').replaceWith(content);
        //validations
        $("#labform").yiiActiveForm("add",{
          'id': 'the_lab', 
          'name': 'the_assignment',
          'container': '#labformatt',
          'input': '#the_lab',
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
        var content='<p id="labfilechoose"><label for="labcustomFile" class="custom-file-label col-form-label-sm">select assignment file</label><input type="file" id="labcustomfile" class="form-control form-control-sm custom-file-input" name="assFile"></input></p>';
        if($('#the_lab').length<=0)
       {
        $('#labformatt').append(content);
        $('#labformatt').css('height','fit-content');
        $('#labformatt').css('height','-moz-fit-content');
        $('#labformatt').css('height','-webkit-fit-content');
        //the validations
        $("#labform").yiiActiveForm("add",{
          'id': 'labcustomfile', 
          'name': 'assFile',
          'container': '#labfilechoose',
          'input': '#labcustomfile',
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
        $('#the_lab').replaceWith(content);
            //the validations
        $("#labform").yiiActiveForm("add",{
          'id': 'labcustomfile', 
          'name': 'assFile',
          'container': '#labfilechoose',
          'input': '#labcustomfile',
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
    
      $('#labtypearea').on('change','#labtype',function(){
      var selected=$(this).val();
      
      if(selected=="groups"){
    
        if($('#labstudents2').length>0)
        {
          $('#labstudents2').parent().remove();
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
                      var data=JSON.parse(resp);
                      var labgens='<div class="col-md-4"><label for="gentypes" style="font-size:11px">Generation types</label><select id="labgentypes" name="gentypes" class="form-control form-control-sm">';
                      var labgenelem='<option value="0" disabled selected>--select generation type--</option>';
                      var labgencloase='</select></div>';
    
                      for(var item in data)
                      {
                        var option='<option value="'+item+'">'+data[item]+'</option>';
                        labgenelem+=option;
    
                      }
    
                      labgens=labgens+labgenelem+labgencloase;
    
                        if($('#labgentypes').length<=0)
                        {
                        $('#labtypearea').append(labgens);
                     
                         //adding there validation
                          $("#labform").yiiActiveForm("add",{
                                'id': 'labgentypes', 
                                'name': 'gentypes',
                                'container': '#labtypearea',
                                'input': '#labgentypes',
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
                       if($('#labgengroups').length>0)
                        {
                          $('#labgengroups').parent().remove();
    
                        }
                        if($('#labstudents2').length>0)
                        {
                          $('#labstudents2').parent().remove();
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
                    success: function(res){
                      var data1=JSON.parse(res);
                      var labgens='<div class="col-md-4"><label for="gentypes" style="font-size:11px">Generation types</label><select id="labgentypes" name="gentypes" class="form-control form-control-sm">';
                      var labgenelem='<option value="0" disabled selected>--select generation type--</option>';
                      var labgenclose='</select></div>';
    
                      for(var item in data1)
                      {
                        var option='<option value="'+item+'">'+data1[item]+'</option>';
                        labgenelem+=option;
    
                      }
    
                      labgens=labgens+labgenelem+labgenclose;
    
                        if($('#labgentypes').length<=0)
                        {
                        $('#labtypearea').append(labgens);
                       
                        //validation
                        $("#labform").yiiActiveForm("add",{
                                'id': 'labgentypes', 
                                'name': 'gentypes',
                                'container': '#labtypearea',
                                'input': '#labgentypes',
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
        if($('#labgengroups').length>0)
        {
          $('#labgengroups').parent().remove();
    
        }
        if($('#labgentypes').length>0)
        {
          $('#labgentypes').parent().remove();
    
        }
        if($('#labstudents2').length>0)
        {
          $('#labstudents2').parent().remove();
        }
    
      }
      else if(selected=="students"){
    
        if($('#labgengroups').length>0)
        {
          $('#labgengroups').parent().remove();
    
        }
        if($('#labgentypes').length>0)
        {
          $('#labgentypes').parent().remove();
    
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
                      var stdelem='<div class="col-md-4"><label for="students2" style="font-size:11px">Students</label><select id="labstudents2" name="mystudents[]" class="form-control form-control-sm" multiple="multiple" data-placeholder="Select students">';
                      var stdelemmid='';
                      var stdelem2='</select></div>';
    
                      for(var item in data)
                      {
                        var option='<option value="'+item+'">'+item+'</option>';
                        stdelemmid+=option;
    
                      }
    
                      stdelem=stdelem+stdelemmid+stdelem2;
                        if($('#labstudents2').length<=0)
                        {
                        $('#labtypearea').append(stdelem);
                        $('#labstudents2').select2();
                        $("#labform").yiiActiveForm("add",{
                                'id': 'labstudents2', 
                                'name': 'mystudents[]',
                                'container': '#labtypearea',
                                'input': '#labstudents2',
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
        if($('#labgengroups').length>0)
        {
          $('#labgengroups').parent().remove();
    
        }
        if($('#labgentypes').length>0)
        {
          $('#labgentypes').parent().remove();
    
        }
        if($('#labstudents2').length>0)
        {
          $('#labstudents2').parent().remove();
        }
      }
      });
      //adding select 2 effects
    
      //the groups
      $('#labtypearea').on('change','#labgentypes',function(){
      var selected=parseInt($(this).val());
      if($('#labtype').val()=="groups"){
        
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
                      var data=JSON.parse(resp);
                      var grpelem='<div class="col-md-4" id="labgroups"><label for="gengroups" id="grplabel" style="font-size:11px">Groups</label><select id="labgengroups" name="gengroups[]" class="form-control form-control-sm myselects" multiple="multiple" data-placeholder="Select groups">';
                      var grpelemmid='';
                      var grpelem2='</select></div>';
    
                      for(var item in data)
                      {
                        var option='<option value="'+item+'">'+data[item]+'</option>';
                        grpelemmid+=option;
    
                      }
    
                      grpelem=grpelem+grpelemmid+grpelem2;
                      if(selected!=0){
                      if($('#labgengroups').length<=0)
                        {
                        $('#labtypearea').append(grpelem);
                        $('.myselects').select2();
                        $("#labform").yiiActiveForm("add",{
                                'id': 'labgengroups', 
                                'name': 'gengroups[]',
                                'container': '#labtypearea',
                                'input': '#labgengroups',
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
                          $('#labgroups').replaceWith(grpelem);
                          $('.myselects').select2();
                          $("#labform").yiiActiveForm("add",{
                                'id': 'labgengroups', 
                                'name': 'gengroups[]',
                                'container': '#labgroups',
                                'input': '#labgengroups',
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
                        if($('#labgengroups').length>0)
                        {
                          $('#labgroups').remove();
    
                        }
                        }
                    }
                    });
    
     
    
      }
      else
      {
        if($('#labgengroups').length>0)
        {
          $('#labgroups').remove();

        }
      }
    
    
    
      })
    
    })