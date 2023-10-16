<div class="row">
    <div class="col-lg-8 col-md-12 col-sm-6 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title d-inline-block">Хисобот </h4>
                <?= form_open(site_url("doctor/patients_lab/reports"), array()) ?>
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
                            <th width="100">Чек</th>
                            <th width="200">Исм</th>
                            <th class="text-left">Сана</th>
                            <th class="text-left">Тўлов (сўм)</th>
                            <th class="text-left">VitaMed</th>
                            <th class="text-left">Клиника</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
						$total_payment = 0;
						$total_partner_payment = 0;
						$total_clinic = 0;
                        if(count($patients) > 0) {

                        foreach ($patients as $c => $patient) {?>
                            <?php //Berilgan muddat orasidagi tulovni umumin summasini topib olamiz
                            $total_payment += $patient["total"];
                            $total_partner_payment += $patient["total_partner"];
                            $total_clinic += $patient["clinic"];
                            ?>
                            <tr>
                                <td><?= ++$c; ?></td>
                                <td><?= "#".$patient["payment_id"]; ?></td>
                                <td class="text-left"><h2><?= $patient["last_name"] ." ". $patient["first_name"]; ?></h2></td>
                                <td class="text-left"><?= date("d.m.Y", strtotime($patient["created_date"])); ?></td>
                                <td class="text-left font-weight-bold"><?= number_format($patient["total"]); ?></td>
                                <td class="text-left font-weight-bold"><?= number_format($patient["total_partner"]); ?></td>
                                <td class="text-left font-weight-bold"><?= number_format($patient["clinic"]); ?></td>
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
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-left font-18"><?= number_format($total_payment); ?></th>
                            <th class="text-left font-18"><?= number_format($total_partner_payment); ?></th>
                            <th class="text-left font-18"><?= number_format($total_clinic); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div class="col-md-6">

    </div>
</div>


