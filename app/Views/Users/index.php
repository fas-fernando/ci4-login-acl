<?= $this->extend("Layout/main") ?>

<?= $this->section("title") ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section("styles") ?>

<link href="https://cdn.datatables.net/v/bs4/dt-1.13.8/r-2.5.0/datatables.min.css" rel="stylesheet">

<?= $this->endSection() ?>

<?= $this->section("content") ?>

<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="block">
            <a href="<?= site_url('users/create') ?>" class="btn btn-primary mb-5">Novo usuário</a>

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
    $(document).ready(function() {
        const DATATABLE_PTBR = {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "Pesquisar",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            },
            "select": {
                "rows": {
                    "_": "Selecionado %d linhas",
                    "0": "Nenhuma linha selecionada",
                    "1": "Selecionado 1 linha"
                }
            }
        }

        new DataTable('#ajaxTable', {
            "oLanguage": DATATABLE_PTBR,
            ajax: "<?= site_url('users/getusers') ?>",
            columns: [{
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
            ],
            "deferRender": true,
            "processing": true,
            "responsive": true,
            "pagingType": $(window).width() < 768 ? "simple" : "simple_numbers" ,
        });
    });
</script>

<?= $this->endSection() ?>