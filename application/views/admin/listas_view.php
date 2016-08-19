<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<div class="row">
    <div class="panel panel-default" id="lista_semestre">
        <div class="panel-heading" data-count="<?php echo count($semestres)?>">
            <button type="button" class="btn btn-default btn-sm pull-left">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </button>
            <button type="button" class="btn btn-default btn-sm pull-right">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </button>
            <div>
            <?php 
            $count = 1;
            if(is_array($semestres)){
                foreach($semestres as $semestre){
                    if($semestre_actual === false && $count === 1){
                        echo '<h3 class="actual title_'.$count.'" data-index="'.$count.'" data-id="'.$semestre['id'].'">'.$semestre['semestre'].'</h3>';
                    }else if($semestre_actual !== false && ($semestre['id'] === $semestre_actual['id'])){
                        echo '<h3 class="actual title_'.$count.'" data-index="'.$count.'" data-id="'.$semestre['id'].'">'.$semestre['semestre'].'</h3>';
                    }else{
                        echo '<h3 style="display:none" class="title_'.$count.'" data-index="'.$count.'" data-id="'.$semestre['id'].'">'.$semestre['semestre'].'</h3>';
                    }
                    $count++;
                }
            }
            ?>
            </div>
        </div>
        <div class="panel-body">
            
        </div>
    </div>
</div>