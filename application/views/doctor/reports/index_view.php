<div class="container-fluid p-0">
    <div class="card no-shadow bg-transparent no-border rm-borders mb-3 report_page">
        <div class="card">
            <div class="p-0 row">
                <div class="col-md-12 col-lg-4">
                    <ul class="list-group list-group-flush">
                        <li class="bg-transparent list-group-item">
                            <div class="widget-content p-0">
                                <div class="widget-content-outer">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">Кўриг нархи</div>
                                            <div class="widget-subheading">сум</div>
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="widget-numbers"><?= $doctor["price"]; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-md-12 col-lg-4"></div>
                <div class="col-md-12 col-lg-4">
                    <ul class="list-group list-group-flush">
                        <li class="bg-transparent list-group-item">
                            <div class="widget-content p-0">
                                <div class="widget-content-outer">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left">
                                            <div class="widget-heading">Келишув</div>
                                            <div class="widget-subheading">%</div>
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="widget-numbers">
                                                <?= $doctor["agreement"]; ?>%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
//Bir kunlik tulovni umumin summasini topib olamiz
$total_payment = 0;
foreach ($patients as $c => $patient) {
    $total_payment += $doctor["price"];
}

//$earning = number_format(($total_payment*($doctor["agreement"]/100)), 0, ',', ' ');


?>

<div class="row">
    <div class="col-lg-6 col-md-12 col-sm-6 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title d-inline-block">Кунлик хисобот </h4>
                <?= form_open(site_url("doctor/reports"), array()) ?>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php echo lang('salary_start_date', 'start_date');?>
                            <div class="cal-icon"><?php echo form_input($start_date);?></div>
                            <div class="invalid-feedback"><?php echo form_error('start_date'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <?php echo lang('salary_end_date', 'end_date');?>
                            <div class="cal-icon"><?php echo form_input($end_date);?></div>
                            <div class="invalid-feedback"><?php echo form_error('end_date'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mt-4">
                            <button type="submit" name="show_report1" class="btn btn-primary btn-lg mt-1 mb-2">Yuborish</button>
                        </div>
                    </div>
                </div>
                <?php form_close(); ?>

            </div>
            <div class="card-block">
                <div class="table-responsive">
                    <table class="table table-reports mb-0">
                        <thead>
                        <tr>
                            <th width="5">#</th>
                            <th width="5">ID</th>
                            <th width="100">Чек</th>
                            <th width="200">Исм</th>
                            <th class="text-right">Тўлов (сўм)</th>
                            <th class="text-right">Сана</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(count($patients) > 0) {
                        foreach ($patients as $c => $patient) {?>
                            <tr>
                                <td><?= ++$c; ?></td>
                                <td><?= $patient["username"]; ?></td>
                                <td><?= "#".$patient["id"]; ?></td>
                                <td class="text-left"><h2><?= $patient["last_name"] ." ". $patient["first_name"]; ?></h2></td>
                                <td class="text-right font-weight-bold"><?= number_format($doctor["price"]); ?></td>
                                <td class="text-right"><?= date("d.m.y", strtotime($patient["created_date"])); ?></td>
                            </tr>
                        <?php }
                        } else { ?>
                            <tr>
                                <td class="text-center" colspan="6">Маълумот топилмади</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Жами: </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-right font-18" colspan="2"><?= $a = number_format($total_payment) ." * ".$doctor["agreement"] ."% = ". number_format(($total_payment*($doctor["agreement"]/100))); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-footer pl-0 pr-0 pb-0">
                <div class="container-fluid p-0">
                    <div class="card no-shadow bg-transparent no-border rm-borders mb-3 report_page">
                        <div class="card">
                            <div class="p-0 row">
                                <div class="col-md-12 col-lg-4">
                                    <ul class="list-group list-group-flush">
                                        <li class="bg-transparent list-group-item">
                                            <div class="widget-content p-0">
                                                <div class="widget-content-outer">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left">
                                                            <div class="widget-heading">Жами</div>
                                                            <div class="widget-subheading"></div>
                                                        </div>
                                                        <div class="widget-content-right">
                                                            <div class="widget-numbers text-success">
                                                                <?= number_format($earning["dShareSum"]); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-12 col-lg-4">
                                    <ul class="list-group list-group-flush">
                                        <li class="bg-transparent list-group-item">
                                            <div class="widget-content p-0">
                                                <div class="widget-content-outer">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left">
                                                            <div class="widget-heading">Тўланди</div>
                                                            <div class="widget-subheading"></div>
                                                        </div>
                                                        <div class="widget-content-right">
                                                            <div class="widget-numbers text-success">
                                                                <?= number_format($earning["paid_sum"]); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-12 col-lg-4">

                                    <ul class="list-group list-group-flush">
                                        <li class="bg-transparent list-group-item">
                                            <div class="widget-content p-0">
                                                <div class="widget-content-outer">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left">
                                                            <div class="widget-heading">Қолди</div>
                                                            <div class="widget-subheading"></div>
                                                        </div>
                                                        <div class="widget-content-right">
                                                            <div class="widget-numbers text-danger">
                                                                <?= number_format($earning["dShareSum"] - $earning["paid_sum"]); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card d-none" style="min-height: 33rem">
            <div class="card-header">
                <h4 class="card-title d-inline-block">Хафталик хисобот </h4>
                <div class="btn-group float-right">
                    <button type="button" class="btn btn-primary"><span class="fa fa-chevron-left"></span></button>
                    <button type="button" class="btn btn-primary reports_text_btn">30.04.2020 - 05.07.2020</button>
                    <button type="button" class="btn btn-primary"><span class="fa fa-chevron-right"></span></button>
                </div>
            </div>
            <div class="card-block">
                <div class="table-responsive">
                    <table class="table table-reports mb-0">
                        <thead>
                        <tr>
                            <th width="100">Сана</th>
                            <th>Беморлар сони</th>
                            <th class="text-right">Тўлов (сум)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>02.07.2020</td>
                            <td class="text-left">6</td>
                            <td class="text-right">412000</td>
                        </tr>
                        <tr>
                            <td>03.07.2020</td>
                            <td class="text-left">14</td>
                            <td class="text-right">412000</td>
                        </tr>
                        <tr>
                            <td>04.07.2020</td>
                            <td class="text-left">10</td>
                            <td class="text-right">412000</td>
                        </tr>
                        <tr>
                            <td>04.07.2020</td>
                            <td class="text-left">10</td>
                            <td class="text-right">412000</td>
                        </tr>
                        <tr>
                            <td>04.07.2020</td>
                            <td class="text-left">10</td>
                            <td class="text-right">412000</td>
                        </tr>
                        <tr>
                            <td>04.07.2020</td>
                            <td class="text-left">10</td>
                            <td class="text-right">412000</td>
                        </tr>
                        <tr>
                            <td>04.07.2020</td>
                            <td class="text-left">0</td>
                            <td class="text-right">0</td>
                        </tr>
                        <tr>
                            <th>Жами</th>
                            <th colspan="2" class="text-right">1202000</th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer pl-0 pr-0 pb-0">
                <div class="container-fluid p-0">
                    <div class="card no-shadow bg-transparent no-border rm-borders mb-3 report_page">
                        <div class="card">
                            <div class="p-0 row">
                                <div class="col-md-12 col-lg-4">
                                    <ul class="list-group list-group-flush">
                                        <li class="bg-transparent list-group-item">
                                            <div class="widget-content p-0">
                                                <div class="widget-content-outer">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left">
                                                            <div class="widget-heading">Жами</div>
                                                            <div class="widget-subheading"></div>
                                                        </div>
                                                        <div class="widget-content-right">
                                                            <div class="widget-numbers text-success">
                                                                <?= $earning; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-12 col-lg-4">
                                    <ul class="list-group list-group-flush">
                                        <li class="bg-transparent list-group-item">
                                            <div class="widget-content p-0">
                                                <div class="widget-content-outer">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left">
                                                            <div class="widget-heading">Тўланди</div>
                                                            <div class="widget-subheading"></div>
                                                        </div>
                                                        <div class="widget-content-right">
                                                            <div class="widget-numbers text-success">
                                                                0
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-12 col-lg-4">
                                    <ul class="list-group list-group-flush">
                                        <li class="bg-transparent list-group-item">
                                            <div class="widget-content p-0">
                                                <div class="widget-content-outer">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left">
                                                            <div class="widget-heading">Қолди</div>
                                                            <div class="widget-subheading"></div>
                                                        </div>
                                                        <div class="widget-content-right">
                                                            <div class="widget-numbers text-danger">
                                                                100 000
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row d-none">
    <div class="col-md-6">
        <div class="card" style="min-height: 30rem">
            <div class="card-header">
                <h4 class="card-title d-inline-block">Йиллик хисобот </h4>
                <div class="btn-group float-right">
                    <button type="button" class="btn btn-primary"><span class="fa fa-chevron-left"></span></button>
                    <button type="button" class="btn btn-primary reports_text_btn">2020</button>
                    <button type="button" class="btn btn-primary"><span class="fa fa-chevron-right"></span></button>
                </div>
            </div>
            <div class="card-block">
                <div class="table-responsive">
                    <!--                    <div class="pl-3 font-weight-bold text-primary">02.03.2020</div>-->
                    <table class="table table-reports mb-0">
                        <thead>
                        <tr>
                            <th width="100">Ой</th>
                            <th>Беморлар сони</th>
                            <th class="text-right">Тўлов (сум)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Январь</td>
                            <td class="text-left">6</td>
                            <td class="text-right">412000</td>
                        </tr>
                        <tr>
                            <td>Февраль</td>
                            <td class="text-left">14</td>
                            <td class="text-right">412000</td>
                        </tr>
                        <tr>
                            <td>Март</td>
                            <td class="text-left">10</td>
                            <td class="text-right">412000</td>
                        </tr>
                        <tr>
                            <td>Апрель</td>
                            <td class="text-left">10</td>
                            <td class="text-right">412000</td>
                        </tr>
                        <tr>
                            <td>Май</td>
                            <td class="text-left">10</td>
                            <td class="text-right">412000</td>
                        </tr>
                        <tr>
                            <td>Июнь</td>
                            <td class="text-left">10</td>
                            <td class="text-right">412000</td>
                        </tr>
                        <tr>
                            <td>Июль</td>
                            <td class="text-left">0</td>
                            <td class="text-right">0</td>
                        </tr>
                        <tr>
                            <th>Жами</th>
                            <th colspan="2" class="text-right">1202000</th>
                        </tr>
                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>
</div>
