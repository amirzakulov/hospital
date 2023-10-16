<?php $this->load->view('doctor/patients/header_template_view'); ?>

<div class="row">
    <div class="col-md-12">
        <table class="table table-border table-striped custom-table datatable_patients mb-0 compact">
            <thead class="thead-dark">
            <tr>
                <th class="align-text-top" width="10%"><?= lang("index_ID_th"); ?><br></th>
                <th class="align-text-top" width="25%"><?= lang("index_fname_th"); ?><br></th>
                <th class="align-text-top" width="10%"><?= lang("create_user_dob_label"); ?></th>
                <th class="align-text-top" width="15%"><?= lang("index_phone_th"); ?></th>
                <th class="align-text-top" width="15%"> <?= lang("index_last_payment"); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($patients as $i_patient) {?>
            <tr>
                <td class="font-weight-bold"><?= $i_patient["username"]; ?></td>

                <td>
                    <a href="<?= site_url("doctor/patients/patient_info_all/".$i_patient["patient_id"]) ?>"><?= $i_patient["last_name"] ." ". $i_patient["first_name"] ." ". $i_patient["surname"];?></a>
                    <div class="doc-prof doc-prof--mb0">
                        <i class="fa fa-map-marker text-danger"></i> <?= $i_patient["region_name"] .", ". $i_patient["city_name"] .", ". $i_patient["address"]; ?>
                    </div>
                </td>
                <td><?= is_null($i_patient["dob"]) ? "":date("Y", strtotime($i_patient["dob"]));?></td>
                <td><?= is_null($i_patient["phone"]) ? "":phone_number_format($i_patient["phone"]);?></td>
                <td><?= date("d M Y", strtotime($i_patient["created_date"]));?></td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->load->view('doctor/patients/footer_template_view'); ?>
