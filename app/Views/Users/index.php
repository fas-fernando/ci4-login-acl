<?= $this->extend("Layout/main") ?>

<?= $this->section("title") ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section("styles") ?>

<link href="https://cdn.datatables.net/v/bs4/dt-1.13.8/r-2.5.0/datatables.min.css" rel="stylesheet">

<?= $this->endSection() ?>

<?= $this->section("content") ?>

<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-8">
        <div class="block">
            <div class="table-responsive">
                <table class="table table-striped table-sm" id="ajaxTable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Avatar</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section("scripts") ?>

<script src="https://cdn.datatables.net/v/bs4/dt-1.13.8/r-2.5.0/datatables.min.js"></script>

<script>
    new DataTable('#ajaxTable', {
        ajax: "<?= site_url('users/getusers') ?>",
        columns: [
            {
                data: 'avatar'
            },
            {
                data: 'username'
            },
            {
                data: 'email'
            },
            {
                data: 'status'
            },
        ]
    });
</script>

<?= $this->endSection() ?>