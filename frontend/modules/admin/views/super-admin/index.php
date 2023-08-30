<?php
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'Dashboard';
?>
<div class="site-index">

    

    <div class="body-content">
            <!-- Content Wrapper. Contains page content -->
   
       <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon elevation-0"><i class="fa fa-user-circle text-success"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Users</span>
                <span class="info-box-number">
                  <?=$users?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3 ">
            <a href="<?= Url::toRoute('instructormanage/instructor-list') ?>" class="text-dark">
            <div class="info-box mb-3 ">
            <span class="info-box-icon elevation-0"><i class="fa fa-chalkboard-teacher text-success"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Instructors</span>
                <span class="info-box-number"><?=$instructors?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            </a>
            <!-- /.info-box -->
          </div>

          <div class="col-12 col-sm-6 col-md-3">
          <a href="<?= Url::toRoute('student-crud/index') ?>" class="text-dark">
            <div class="info-box mb-3">
            <span class="info-box-icon elevation-0"><i class="fa fa-user-graduate text-success"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Students</span>
                <span class="info-box-number"><?=$students?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            </a>
            <!-- /.info-box -->
          </div>
      </div><!--/. container-fluid -->

    </div>
</div>
