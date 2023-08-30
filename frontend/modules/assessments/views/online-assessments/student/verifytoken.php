<?php
use yii\helpers\Url;

$this->title = 'Verify token';

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js"></script>

<div class="card card-lg shadow bg-white rounded" style="font-family:'Lucida Bright'">
    <div class="card-header text-center bg-success p-1">
     <i class="fa fa-lock"></i> Enter Access Token
    </div>
    <div class="card-body">
    <div class="container-fluid">
            <form id="verifytoken" method="post">
              <input type="text" name="token" class="form-control" placeholder="Access Token"></input>
              <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
              <button type="submit" class="btn btn-success btn-sm float-right p-2 m-3">
              <i class="fa fa-check"></i> Verify and Continue
              </button>
             </form>
                   </div>
          
    </div>
</div>
</div>


