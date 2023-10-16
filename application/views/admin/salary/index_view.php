<?php $this->load->view('admin/salary/header_template_view'); ?>
<?= form_open(site_url("admin/salary"), array()) ?>
    <div class="row">
        <div class="col-sm-5">
            <div class="form-group">
                <?php echo lang('salary_start_date', 'start_date');?>
                <div class="cal-icon"><?php echo form_input($start_date);?></div>
                <div class="invalid-feedback"><?php echo form_error('start_date'); ?></div>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="form-group">
                <?php echo lang('salary_end_date', 'end_date');?>
                <div class="cal-icon"><?php echo form_input($end_date);?></div>
                <div class="invalid-feedback"><?php echo form_error('end_date'); ?></div>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group mt-3">
                <button type="submit" name="show_report" class="btn btn-primary btn-lg mt-1 mb-2">Yuborish</button>
            </div>
        </div>
    </div>
<?php form_close(); ?>
<div class="row">
    <div class="col-md-12">
        <table class="table table-border table-striped custom-table datatable_patients mb-0 compact">
            <thead class="thead-dark">
            <tr class="text-center">
                <th class="align-text-top" width="22%"><?= lang("index_fname_th"); ?></th>
                <th class="align-text-top" width="">Тушум</th>
                <th class="align-text-top text-center" width="">Ш.Улуш (%)</th>
                <th class="align-text-top" width="">Ш.Улуш (сўм)</th>
                <th class="align-text-top" width="20%">Тўланди</th>
                <th class="align-text-top" width="">Клиника қарзи</th>
                <th class="text-right align-text-top" width="5%"><?= lang("general_actions"); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php $tushum = $ulush = $tulandi = $klinika_qarzi = 0; ?>
            <?php foreach ($doctors as $doctor) { ?>
            <tr class="text-center" id="js_row_">
                <td class="font-weight-bold text-left">
                    <a href="<?= site_url("admin/salary/doctor_cash/".$doctor["doctor_id"]."/".$sdate."/".$edate); ?>"><?= $doctor["last_name"] ." ". $doctor["first_name"]?></a>
                </td>
                <td><?= $doctor["earning"]["total"] ?></td>
                <td><?= $doctor["agreement"] ?></td>
                <td><?= $doctor["earning"]["dShareSum"] ?>
                    <input type="hidden" name="dShareSum" value="<?= $doctor["earning"]["dShareSum"] ?>">
                </td>
                <td>
                    <div class="js_tulangan_summa_content">
                        <span class="js_tulangan_summa"><?= $paid_sum = empty($doctor["earning"]["paid_sum"]) ? 0:$doctor["earning"]["paid_sum"]; ?></span>
                        <input type="hidden" name="tulangan_summa" value="<?= $paid_sum = empty($doctor["earning"]["paid_sum"]) ? 0:$doctor["earning"]["paid_sum"]; ?>">
                        <span class="fa fa-credit-card-alt text-primary pull-right mr-3 js_doctor_bill"></span>
                    </div>
                    <div class="d-none js_doctor_bill_input">
                        <div class="input-group input-group--doctor_bill">
                            <input class="form-control js_doctor_bill_amount" type="text" name="amount">
                            <input class="form-control" type="hidden" name="doctor_id[]" value="<?= $doctor["doctor_id"]; ?>">
                            <div class="input-group-append">
                                <button class="btn btn-success js_payment_save" title="Керакли суммани киритинг!" type="button" data-url="<?= site_url("admin/salary/do_doctor_payment") ?>"><span class="fa fa-check"></span></button>
                                <button class="btn btn-danger js_payment_cancel" type="button"><span class="fa fa-remove"></span></button>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="font-weight-bold text-danger js_klinika_qarzi"><?= $clinic_debt = $doctor["earning"]["dShareSum"] - $doctor["earning"]["paid_sum"]; ?></td>
                <td class="text-right">
                    <div class="dropdown dropdown-action">
                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#"><i class="fa fa-pencil m-r-5"></i> <?= lang("general_edit"); ?></a>
                            <a class="dropdown-item js_delele_item" href="javascript:void(0);" data-href="" data-id=""><i class="fa fa-trash-o m-r-5"></i> <?= lang("general_delete"); ?></a>
                        </div>
                    </div>
                </td>
            </tr>
                <?php
                $tushum         += $doctor["earning"]["total"];
                $ulush          += $doctor["earning"]["dShareSum"];
                $tulandi        += $paid_sum;
                $klinika_qarzi  += $clinic_debt;
                ?>
            <?php } ?>


            </tbody>
            <tfoot class="thead-dark">
            <tr class="text-center">
                <th></th>
                <th><?= $tushum; ?></th>
                <th></th>
                <th><?= $ulush; ?></th>
                <th><?= $tulandi; ?></th>
                <th><?= $klinika_qarzi; ?></th>
                <th></th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
<!--    <script>-->
<!--        $('.datetimepicker1212').datetimepicker({-->
<!--            format: 'DD.MM.YYYY'-->
<!--        });-->
<!--    </script>-->
<?php $this->load->view('admin/salary/footer_template_view'); ?>