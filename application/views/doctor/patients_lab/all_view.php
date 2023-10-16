<?php $this->load->view('doctor/patients_lab/header_template_view'); ?>

<div class="row">
    <div class="col-md-12">
        <table class="table table-border table-striped custom-table datatable_patients_lab mb-0 compact">
            <thead class="thead-dark">
            <tr>
				<th class="align-text-top" width="15%"><?= lang("index_fname_th"); ?><br></th>
				<th class="align-text-top" width="15%">Манзил<br></th>
				<th class="align-text-top" width="10%"><?= lang("index_ID_th"); ?><br></th>
                <th class="align-text-top" width="10%"><?= lang("index_age_th"); ?></th>
                <th class="align-text-top" width="15%"><?= lang("index_phone_th"); ?></th>
                <th class="align-text-top" width="15%"> <?= lang("index_last_payment"); ?></th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<?php $this->load->view('doctor/patients_lab/footer_template_view'); ?>
