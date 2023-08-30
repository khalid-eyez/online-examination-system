<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/student.css',
        'plugins/icheck-bootstrap/icheck-bootstrap.min.css',
        'plugins/jqvmap/jqvmap.min.css',
        'css/adminlte.min.css',
        'plugins/overlayScrollbars/css/OverlayScrollbars.min.css',
        'plugins/jquery-ui/jquery-ui.min.css',
        'plugins/daterangepicker/daterangepicker.css',
        'plugins/summernote/summernote-bs4.min.css',
        'plugins/fontawesome-free/css/all.min.css',
        'plugins/datatables-bs4/css/dataTables.bootstrap4.min.css',
        'plugins/datatables-responsive/css/responsive.bootstrap4.min.css',
        'plugins/datatables-buttons/css/buttons.bootstrap4.min.css',
        'plugins/fileinput/css/fileinput.min.css',
        'plugins/pace-progress/themes/orange/pace-theme-flat-top.css',
        'plugins/sweetalert2/sweetalert2.min.css',
        'js/select2/css/select2.min.css',
        'css/buttons.css'
      
        
    ];
    public $js = [
        'js/student.js',
        'js/sweetalert2.all.min.js',
        'plugins/jquery-ui/jquery-ui.min.js',
        'plugins/bootstrap/js/bootstrap.bundle.min.js',
        'plugins/chart.js/Chart.min.js',
        'plugins/sparklines/sparkline.js',
        'plugins/jqvmap/jquery.vmap.min.js',
        'plugins/jqvmap/maps/jquery.vmap.usa.js',
        'plugins/jquery-knob/jquery.knob.min.js',
        'plugins/moment/moment.min.js',
        'plugins/daterangepicker/daterangepicker.js',
        'plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js',
        'js/adminlte.js',
        'plugins/datatables/jquery.dataTables.min.js',
        'plugins/datatables-bs4/js/dataTables.bootstrap4.min.js',
        'js/tablesbutton.min.js',
        'js/select2/js/select2.min.js',
        'plugins/datatables-responsive/js/dataTables.responsive.min.js',
        'plugins/datatables-responsive/js/responsive.bootstrap4.min.js',
        'plugins/datatables-buttons/js/dataTables.buttons.min.js',
        'plugins/jszip/jszip.min.js',
        'plugins/pdfmake/pdfmake.min.js',
        'plugins/pdfmake/vfs_fonts.js',
        'js/buttons.html5.min.js',
        'js/print.js',
        'plugins/dropzone/dropzone.js',
        'plugins/fileinput/js/fileinput.min.js',
        'plugins/pace-progress/pace.min.js',
        'plugins/sweetalert2/sweetalert2.min.js',
        'js/tooltip.js',
       
        


        //'js/dashboard.js',
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}
