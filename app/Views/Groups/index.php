<?= $this->extend("Layout/main") ?>

<?= $this->section("title") ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section("styles") ?>

<link href="<?= site_url("resources/plugin/datatable/datatables-combinado.min.css") ?>" rel="stylesheet">

<?= $this->endSection() ?>

<?= $this->section("content") ?>

<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="block">
            <a href="<?= site_url('groups/create') ?>" class="btn btn-primary mb-5">Novo Grupo</a>

            <div class="table-responsive">
                <table class="table table-striped table-sm" id="ajaxTable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Situação</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section("scripts") ?>

<script src="<?= site_url("resources/plugin/datatable/datatables-combinado.min.js") ?>"></script>

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
            ajax: "<?= site_url('groups/getgroups') ?>",
            columns: [
                {
                    data: 'name'
                },
                {
                    data: 'description'
                },
                {
                    data: 'show'
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