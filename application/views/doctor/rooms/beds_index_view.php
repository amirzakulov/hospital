<div class="row">
    <div class="col-sm-4 col-3">
        <h4 class="page-title"><?= $title; ?></h4>
    </div>
    <div class="col-sm-5 col-6 font-weight-bold font-18">
        <?= $room["number"] ." / ". $room["rtype_name"] ." / ". number_format($room["price"], 0, 0, " ");?>

    </div>
    <div class="col-sm-3 col-3 text-right m-b-20"></div>
</div>
<?= $breadcrumbs; ?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered custom-table mb-0 datatable">
                <thead class="thead-dark">
                <tr>
                    <th width="" class="text-center">Ётоқ</th>
                    <th width="" class="text-center">Нархи</th>
                    <th width="" class="text-center">Бандлик</th>
                    <th width="" class="text-center">Кундан</th>
                    <th width="" class="text-center">Кунгача</th>
                    <th width="" class="text-center">Бемор</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($beds as $bed) { ?>
                    <tr id="js_row_<?= $bed["id"] ?>">
                        <td class="text-center"><?= $bed["name"]; ?></td>
                        <td class="text-center"><?= number_format($bed["price"], 0, 0, " "); ?></td>
                        <td class="text-center js_busy">
                            <?php $text_color = "fa-circle-o";
                                if($bed["busy"] == 1){
                                    $text_color = "fa-circle text-danger";
                                } else if($bed["busy"] == 2) {
                                    $text_color = "fa-circle text-info";
                                }
                            ?>
                            <span title="Ётоқ <?= $bed["name"]; ?>" class="fa mr-1 <?= $text_color; ?>"></span>
                        </td>
                        <td class="text-center js_start_date"><?= !is_null($bed["start_date"]) ? date("d.m.Y", strtotime($bed["start_date"])) : ""; ?></td>
                        <td class="text-center js_end_date"><?= !is_null($bed["end_date"]) ? date("d.m.Y", strtotime($bed["end_date"])) : ""; ?></td>
                        <td class="text-center js_patient_name"><?= $bed["last_name"]." ".$bed["first_name"] ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
