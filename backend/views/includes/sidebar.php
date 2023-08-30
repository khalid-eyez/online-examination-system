<?php 
use yii\helpers\Url;
?>
<nav class="mt-2 text-dark" style="height:57%;">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
           <li class="nav-item">
            <a href="<?= Url::to(['/portal/dashboard']) ?>" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Dashboard
                
              </p>
            </a>
          </li>
       

              <!-- START OF SYS_ADMIN ROLE -->
            <?php if(Yii::$app->user->can('SYS_ADMIN') || Yii::$app->user->can('SUPER_ADMIN')): ?>
            <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-user-cog"></i>
              <p>
                Users
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= Url::toRoute('/instructormanage/instructor-list') ?>" class="nav-link">
                 <i class="fas fa-chalkboard-teacher nav-icon"></i>
                  <p>Instructors & HODs</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="<?= Url::toRoute('/studentmanage/student-list') ?>" class="nav-link">
                  <i class="fas fa-user-graduate nav-icon"></i>
                  <p>Students</p>
                </a>
              </li>
              
            </ul>
            <li class="nav-item">
                <a href="<?= Url::toRoute('/admin/receipts') ?>" class="nav-link">
                  <i class="fas fa-receipt nav-icon"></i>
                  <p>Receipts</p>
                </a>
              </li>
            <li class="nav-item">
                <a href="<?= Url::toRoute('/departmentmanage/index') ?>" class="nav-link">
                  <i class="fa fa-building nav-icon"></i>
                  <p>Departments</p>
                </a>
              </li>
                <li class="nav-item">
                    <a href="<?= Url::toRoute('/admin/activity-logs') ?>" class="nav-link">
                        <i class="fas fa-history nav-icon"></i>
                        <p>Activity Logs</p>
                    </a>
                </li>
          </li>
            <?php endif ?> <!-- END OF SYS_ADMIN ROLE-->
               <!--START OF SUPER ADMIN ROLE -->
          <?php if(Yii::$app->user->can('SUPER_ADMIN')):?>
     
           
            <li class="nav-item">
                <a href="<?= Url::toRoute('/users/admin-list') ?>" class="nav-link">
                  <i class="fas fa-user-secret nav-icon"></i>
                  <p>Admins</p>
                </a>
        </li>
    
        <li class="nav-item">
                <a href="<?= Url::toRoute('/collegemanage/index') ?>" class="nav-link">
                  <i class="fa fa-university nav-icon"></i>
                  <p>Colleges</p>
                </a>
              </li>

        <li class="nav-item">
            <a href="<?= Url::toRoute('/admin/academic-year') ?>" class="nav-link">
              <i class="nav-icon fa fa-calendar"></i>
              <p>
                 Academic Year
              </p>
            </a>
       
          </li>
          <li class="nav-item">
            <a href="<?= Url::toRoute('/admin/config') ?>" class="nav-link">
              <i class="nav-icon fa fa-cogs"></i>
              <p>
                Configurations
              </p>
            </a>
       
          </li>

          <li class="nav-item">
            <a href="<?= Url::toRoute('/admin/storage') ?>" class="nav-link">
              <i class="nav-icon fa fa-hdd-o"></i>
              <p>
                Storage
              </p>
            </a>
       
          </li>

          <li class="nav-item">
            <a href="<?= Url::toRoute('/admin/system-modules') ?>" class="nav-link">
              <i class="nav-icon fa fa-cubes"></i>
              <p>
                Modules
              </p>
            </a>
       
          </li>
          
              <?php endif ?> <!---END OF SUPER ADMIN ROLE-->
            <!-- ======================================================= -->

              <!-- START OF INSTRUCTOR ROLE -->   
          
            <?php if(Yii::$app->user->can('INSTRUCTOR')): ?>
         
          <li class="nav-item">
                <a href="<?= Url::toRoute('/portal/instructor/courses') ?>" class="nav-link">
                  <i class="fas fa-chalkboard-teacher nav-icon"></i>
                  <p>Self-assign Courses</p>
                </a>
              </li>
            <?php  endif ?>  <!-- END OF INSTRUCTOR ROLE -->

              <!-- START OF STUDENT ROLE -->
              <?php if(Yii::$app->user->can('STUDENT')): ?>
                <li class="nav-item">
                <a href="<?= Url::toRoute('/portal/student/courses') ?>" class="nav-link">
                  <i class="fas fa-book nav-icon"></i>
                  <p>My Curriculum</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= Url::toRoute('/portal/student/carrycourse') ?>" class="nav-link">
                  <i class="fas fa-file nav-icon"></i>
                  <p>Carry Courses</p>
                </a>
              </li>
              <?php endif ?> <!-- END OF STUDENT ROLE -->

          <!--START OF HOD ROLE -->
          <?php if(Yii::$app->user->can('INSTRUCTOR & HOD')): ?>
            <li class="nav-item">
                <a href="<?= Url::toRoute('/instructor/courses') ?>" class="nav-link">
                  <i class="fas fa-chalkboard-teacher nav-icon"></i>
                  <p>Self-assign Courses</p>
                </a>
              </li>
          
            <li class="nav-item">
                <a href="<?= Url::toRoute('/instructor/student-list') ?>" class="nav-link">
                <i class="fas fa-users"></i>
                  <p>Manage Students</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="<?= Url::toRoute('/instructor/create-program') ?>" class="nav-link">
                <i class="fas fa-award"></i>
                  <p>Manage Programs</p>
                </a>
              </li>


              <!-- ################################################ -->

          <li class="nav-item">
          <a href="<?= Url::toRoute('/instructor/create-course') ?>" class="nav-link">
            <i class="fas fa-book-open"></i>
              <p>
                Manage Courses
              </p>
            </a>
          </li>


              <!-- ################################################ -->

              <!-- <li class="nav-item">
                <a href="<?= Url::toRoute('/instructor/create-course') ?>" class="nav-link">
                  <i class="fas fa-book nav-icon"></i>
                  <p>Manage Courses</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= Url::toRoute('/instructor/assign-course') ?>" class="nav-link">
                <i class="fas fa-tasks"></i>
                  <p>Assign Course </p>
                </a>
              </li> -->

              <li class="nav-item">
                <a href="<?= Url::toRoute('/instructor/instructor-course') ?>" class="nav-link">
                <i class="fas fa-users"></i>
                  <p>Instructors </p>
                </a>
              </li>

             
              <?php endif ?> <!-- END OF STUDENT ROLE -->
        
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
      <?php if(Yii::$app->user->can('INSTRUCTOR & HOD') || Yii::$app->user->can('INSTRUCTOR') || Yii::$app->user->can('STUDENT') ): ?>
      <div class="card contactcard">
    <div class="card-header text-sm">
      Have a feedback or need support?<span class="text-success"><i class="fab fa-whatsapp"></i></span><span class="text-success"><i class="fa fa-phone"></i></span><span class="text-success"><i class="fas fa-sms"></i></span>
          </div>
          <div class="card-body">
            <div class="row text-sm">
              <div class="col-md-12">(+255) 755189736</div>
          </div>
          <div class="row text-sm">
              <div class="col-md-12">(+255) 784085190</div>
          </div>
          </div>

          </div>
          <?php endif ?>
    </div>
    <!-- /.sidebar -->
  
    <div class="sidebar-custom">
      <a href="#" class="btn btn-link"><i class="fas fa-cogs"></i></a>
      <a href="#" class="btn btn-secondary hide-on-collapse pos-right">Help</a>
    </div>
