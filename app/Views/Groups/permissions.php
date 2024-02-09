<?= $this->extend("Layout/main") ?>

<!-- Titulo Vindo do Controller e exibindo no Layout Main -->
<?= $this->section("title") ?>

<?= $title ?>

<?= $this->endSection() ?>

<!-- Estilos que estão sendo enviado para o Layout Main -->
<?= $this->section("styles") ?>

<link href="<?= site_url("resources/plugin/selectize/selectize.bootstrap4.css") ?>" rel="stylesheet">

<style>
    .selectize-input,
    .selectize-control.single .selectize-input.input-active {
        background: #2d3035 !important;
    }

    .selectize-dropdown,
    .selectize-input,
    .selectize-input input {
        color: #777;
    }

    .selectize-input {
        border: 1px solid #444951;
        border-radius: 0;
    }
</style>

<?= $this->endSection() ?>

<!-- Conteúdo que está sendo enviado para o Layout Main -->
<?= $this->section("content") ?>

<div class="row">
    <div class="col-lg-8">
        <div class="user-block block">
            <?php if (empty($availablePermissions)) : ?>
                <p class="contributions text-warning mt-0">
                    Esse grupo já possui todas as permissões disponíveis
                </p>
            <?php else : ?>
                <!-- Exibirá os retornos do backend -->
                <div id="response"></div>

                <?= form_open('/', ['id' => 'form'], ['id' => "$group->id"]) ?>

                    <div class="form-group">
                        <label class="form-control-label">Selecione as permições</label>
                        <select name="permission_id[]" class="selectize" multiple>
                            <option value="">Escolha...</option>
                            <?php foreach ($availablePermissions as $permission) : ?>
                                <option value="<?= $permission->id ?>"><?= esc($permission->name) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="form-group mt-5 mb-4">
                        <input type="submit" id="btn-save" value="Salvar" class="btn btn-primary">
                        <a href="<?= site_url("groups/show/$group->id") ?>" class="btn btn-secondary ml-3">Voltar</a>
                    </div>
                <?= form_close() ?>
            <?php endif ?>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="user-block block">
            <?php if (empty($group->permissions)) : ?>
                <p class="contributions text-warning mt-0">
                    Esse grupo ainda não possui permissões de acesso
                </p>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Permissões</th>
                                <th class="float-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($group->permissions as $permission) : ?>
                                <tr>
                                    <td><?= esc($permission->name) ?></td>
                                    <td>
                                        <?php $attr = ['onSubmit' => "return confirm('Tem certeza da exclusão dessa permissão?')"] ?>

                                        <?= form_open("groups/removepermission/$permission->main_id", $attr) ?>
                                            <button type="submit" class="btn btn-danger btn-sm float-right">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        <?= form_close() ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <?= $group->pager->links() ?>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<!-- Scripts que estão sendo enviado para o Layout Main -->
<?= $this->section("scripts") ?>

<script src="<?= site_url("resources/plugin/selectize/selectize.min.js") ?>"></script>

<script>
    $(document).ready(function() {
        $('.selectize').selectize({
            sortField: 'text'
        });

        $("#form").on("submit", function(event) {
            event.preventDefault();

            $.ajax({
                type: 'POST',
                url: '<?= site_url('groups/storepermissions') ?>',
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#response").html('');
                    $("#btn-save").val('Carregando...');
                },
                success: function(response) {
                    $("#btn-save").val('Salvar');
                    $("#btn-save").removeAttr('disabled');
                    $("[name=csrf_order]").val(response.token);

                    if (!response.error) {
                        window.location.href = "<?= site_url("groups/permissions/$group->id") ?>";
                    } else {
                        $("#response").html('<div class="alert alert-danger">' + response.error + '</div>');

                        if (response.errors_model) {
                            $.each(response.errors_model, function(key, value) {
                                $("#response").append('<ul class="list-unstyled"><li class="text-danger">' + value + '</li></ul>');
                            });
                        }
                    }
                },
                error: function() {
                    alert('Não foi possível processar a solicitação. Por favor, entre em contato com o suporte técnico.');
                    $("#btn-save").val('Salvar');
                    $("#btn-save").removeAttr('disabled');
                },
            });
        });

        $('#form').submit(function() {
            $(this).find(':submit').attr('disabled', 'disabled');
        });
    });
</script>

<?= $this->endSection() ?>