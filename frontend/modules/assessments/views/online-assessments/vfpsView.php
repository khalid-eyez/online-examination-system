<?php
use yii\bootstrap4\Breadcrumbs;
use yii\grid\GridView;
use fedemotta\datatables\DataTables;
use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\Custom;
use common\helpers\Security;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\ClassRoomSecurity;
use common\models\Instructor;
use frontend\models\AddPartner;

$this->title ="Student VFPs";


?>
 

<div class="site-index">
    <div class="body-content ">
            <!-- Content Wrapper. Contains page content -->
   
       <div class="container-fluid table-responsive">
            
            <table  style="font-size:12px" width="100%" class="table table-striped table-bordered table-hover" id="studenttable" >
		<thead>
			<tr>
       <th width="1%">
       S/no
				</th>
				<th>
				Date & Time
				</th>
        <th>
			   Action
				</th>
        <th>
			   Status
				</th>
        <th>
			   Error message |Warning| Info
				</th>
                <th>
			   Source 
				</th>
                <th>
			   Description
				</th>
			</tr>
		</thead>
		<tbody>
								<?php 
                    foreach($vfps as $index=>$vfp){
                        ?>
                  
                    <tr id="" class="<?=($vfp['Action']!="Information")?($vfp['Status']=="Successful")?"text-success":"text-danger":"text-info"?>" >
                    <td width="1%"><?=$index+1?></td>
									 	<td width="10%"><?=Html::encode($vfp['Time']);?></td>
                     <td><?=$vfp['Action']?></td>
                     <td><?=Html::encode($vfp['Status']); ?></td>
                     <td><?=Html::encode($vfp['Error Message']); ?></td>
                     <td><?=Html::encode($vfp['Source']); ?></td>
                     <td><?=Html::encode($vfp['Description']); ?></td>
                    
                   
                    
                    
						 			</tr>
						 		
									 <?php } ?>
		
			

		</tbody>
		</table>
         
    </div>
    </div>
</div>



      </div><!--/. container-fluid -->

    </div>
</div>
</div>

                
    



<?php 
$script = <<<JS
$(document).ready(function(){
  $('#studenttable').DataTable({
    responsive:true,
    dom: 'Bfrtip',
        buttons: [
            'csv',
            {
                extend: 'pdfHtml5',
                title: 'Students\' VFPs',
                'orientation':'landscape'
            },
            {
                extend: 'excelHtml5',
                title: 'Students\' VFPs'
            },
            'print',
        ]
  } );
  $('body').addClass("sidebar-collapse");
})

JS;

$this->registerJs($script);

?>
