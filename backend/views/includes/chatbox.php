<!-- /////////////////////////////////////// -->
    
<div class="card  card-success card-sm card-outline direct-chat direct-chat-primary chatcard">
              <div class="card-header">
              <audio class="d-none messageaudio">
              <source src="/media/anxious-586.mp3"  type="audio/mpeg">
              </audio> 
                <div class="card-tools">
                  <span class="mr-2 text-primary sender" style="font-size:11px"></span>
              <span class="dropdown" data-toggle="tooltip" data-title="Current Threads">
                <a data-toggle="dropdown" href="#" >
               <i class="fa fa-envelope" style="color:gray"></i><sup class="bg-danger rounded-pill total">0</sup>
  
               </a>

               <div class="dropdown-menu dropdown-menu-lg threads">
          
           
         
         </div>
      </span>
   
                  
                  <button type="button" class="btn btn-tool tonebtn" data-toggle="tooltip" data-title="mute/unmute sound">
                    <i class="fas fa-volume-up tonecontrol"></i>
                  </button>
                  <button type="button" class="btn btn-tool exp" data-toggle="tooltip" data-title="Expand">
                    <i class="fa fa-expand"></i>
                  </button>
                  <button type="button" class="btn btn-tool"  data-widget="chat-pane-toggle" data-toggle="tooltip" data-title="Online people">
                    <i class="fas fa-comments"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" id="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" data-title="Close">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <!-- Conversations are loaded here -->
                <div class="direct-chat-messages">
                 
                  <div class="jumbotron" style="background:none !important"><h3 class="text-md"><small style="color:rgb(119, 119, 119)">Choose Thread</small></h3></div>
                </div>
                <!--/.direct-chat-messages-->

                <!-- Contacts are loaded here -->
                <div class="direct-chat-contacts">
                <nav class="navbar" style="position:absolute;top:1%;width:100%">
                <ul class="navbar-nav ml-auto p-0 m-0" style="height:10px">
                <li class="nav-item p-0">
       
        <div class="navbar-search-block" style="height:10px">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar contactsearch" type="search" placeholder="Search" aria-label="Search">
            </div>
          </form>
        </div>
      </li></ul></nav>
   
          <i data-widget="navbar-search" class="btn  btn-primary btn-sm text-white round fas fa-search " style="position:absolute;bottom:60%;right:0" role="button" data-toggle="tooltip" data-title="Search"></i>
  
                  <i class="fa fa-refresh btn-sm btn-primary" id="viewall" data-toggle="tooltip" data-title="Load All" style="position:absolute;right:0;bottom:50%;cursor:pointer"></i>
                  <ul class="contacts-list">
                    
                    <!-- End Contact Item -->
                  </ul>
                  <!-- /.contatcts-list -->
                </div>
                <!-- /.direct-chat-pane -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <div class="input-group">
                    <span class="btn btn-sm btn-primary mr-1" id="clearthread" data-toggle="tooltip" data-title="Clear Thread"><i class="fa fa-trash"></i></span>
                    <input type="text" name="message" rows="2" placeholder="Message ..." class="form-control mytext"> </input>
                    <span class="btn btn-sm btn-primary ml-1" id="sendtext" data-toggle="tooltip" data-title="Send Text"><i class="fa fa-paper-plane"></i></span>
                  </div>
    
              </div>
              <!-- /.card-footer-->
            </div>
            <!--/.direct-chat -->