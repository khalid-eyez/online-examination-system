
<div class="site-index">

    <div class="body-content">
        <!-- Content Wrapper. Contains page content -->
     <div class="container-fluid table-responsive">
      <table style="width:100%;border:none" cellspacing="4" cellpadding="4">
      <?php
      //print_r($tokens);return false;
       for($i=0;$i<count($tokens);$i+=2)
       {
        ?>
         <tr>
          <td style="border:1px solid lightblue; border-radius:3px;margin:3px;padding:4px; text-align:center;font-size:24px;"><?=strtoupper(base64_decode($tokens[$i]['token']))?></td>
          <?php

          if(isset($tokens[$i+1]['token']))
          {
          ?>
          <td style="border:1px solid lightblue; border-radius:3px;margin:3px;padding:4px; text-align:center;font-size:24px;"><?=strtoupper(base64_decode($tokens[$i+1]['token']))?></td>
          <?php
          }
          ?>
       </tr>
        <?php
       }
      ?>
      </table>
     </div>
    
       

    </div>

</div>
     

