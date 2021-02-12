<?php
if (isset($_POST['Discipline'])){

}else{
//SQL Connect Discipline.
$sql = 'select top 1000 [PDP].[dbo].[tDelaysDiscipline].* from [PDP].[dbo].[tDelaysDiscipline]';
$sqlargs = array();
require_once 'config/db_query.php'; 
$Dis =  sqlQuery($sql,$sqlargs);
?>

<!-- form start-->
<div class="card">
    <div class="card-header bg-success">
        Discipline
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="Discipline"> Discipline</label>
                <select type="text" class="form-control" id="Discipline" name="Discipline" required>
                    <option value="">Please Select</option>
                    <?php
                    foreach ($Dis[0] as $DisRec) {
                       echo '<option value="'.$DisRec['DisciplineId'].'">'.$DisRec['DisciplineDescription'].'</option>';
                    }
                    ?>
                    <option value="#ADD">Add New Discipline</option>
                </select>
            </div>
        </div>
    </div>
</div>
<!-- form end -->
<?php }?>