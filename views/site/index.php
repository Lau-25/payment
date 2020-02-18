<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            
            <? foreach($params as $value){
                echo $value->nickname . ' - ' . $value->balance . '<br/>';
            };
            ?>
        </div>

    </div>
</div>
