<?php 
use yii\helpers\Url;
?>
<nav class="mt-2 text-success bg-white">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
   
       

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
                <a href="<?= Url::toRoute('/admin/instructormanage/instructor-list') ?>" class="nav-link">
                 <i class="fas fa-chalkboard-teacher nav-icon"></i>
                  <p>Instructors</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fas fa-user-graduate nav-icon"></i>
                  <p>
                    Students
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                <a href="<?= Url::toRoute('/register') ?>" class="nav-link">
                 <i class="fa fa-user-plus nav-icon"></i>
                  <p>Register Student</p>
                </a>
                </li>
                <li class="nav-item">
                <a href="<?= Url::toRoute('/admin/student-crud/index') ?>" class="nav-link">
                 <i class="fa fa-list nav-icon"></i>
                  <p>Students' List</p>
                </a>
                </li>
                </ul>
              </li>
              
            </ul>
        
      
              
          </li>
            <?php endif ?> <!-- END OF SYS_ADMIN ROLE-->
          <?php if(Yii::$app->user->can('INSTRUCTOR')): ?>
       
              <li class="nav-item ">
                <a href="<?= Url::toRoute('/assessments/online-assessments/class-quizes') ?>" class="nav-link text-success">
                  <i class="fa fa-pen nav-icon"></i>
                  <p>
                    Examinations
                  </p>
                </a>
              </li>
                
          <li class="nav-item">
                <a href="<?= Url::to(['/assessments/online-assessments/new-quiz']) ?>" class="nav-link text-success">
                 <i class="fa fa-plus-circle nav-icon"></i>
                  <p>
                  Exam authoring
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= Url::to(['/assessments/online-assessments/questions-bank']) ?>" class="nav-link text-success">
                 <i class="fa fa-bank nav-icon"></i>
                  <p>
                  Questions Bank
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= Url::to(['/assessments/module/index']) ?>" class="nav-link text-success">
                 <i class="fa fa-cubes nav-icon"></i>
                  <p>
                  Exam Modules
                  </p>
                </a>
              </li>
            <?php  endif ?>  <!-- END OF INSTRUCTOR ROLE -->

              <!-- START OF STUDENT ROLE -->
              <?php if(Yii::$app->user->can('STUDENT')): ?>
                <li class="nav-item">
                <a href="<?= Url::toRoute('/assessments/online-assessments/verify-token') ?>" class="nav-link">
                  <i class="fa fa-pen nav-icon"></i>
                  <p>Take exam</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= Url::toRoute('/assessments/online-assessments/student-quizes') ?>" class="nav-link">
                  <i class="fas fa-book nav-icon"></i>
                  <p>My exams</p>
                </a>
              </li>
             
              <?php endif ?> <!-- END OF STUDENT ROLE -->

        </ul>
        
      </nav>
     
    </div>
    <!-- /.sidebar -->
  
    <div class="sidebar-custom bg-white" style="border:none!important">
   
    </div>
