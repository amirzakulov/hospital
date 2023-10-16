<style>
    /*span {display: block;}*/
</style>
<div class="row">
    <div class="col-md-12">
        <div>name: <?= $myfile["filename"]; ?></div>
        <a href="<?= site_url("uploads/services/".$myfile["filename"]) ?>"><?= $myfile["filename"]; ?></a>
    </div>
    <div class="col-md-6"><?= $myfile["result"]; ?></div>
</div>
