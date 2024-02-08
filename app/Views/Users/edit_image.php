<?= $this->extend("Layout/main") ?>

<!-- Titulo Vindo do Controller e exibindo no Layout Main -->
<?= $this->section("title") ?>

<?= $title ?>

<?= $this->endSection() ?>

<!-- Estilos que estão sendo enviado para o Layout Main -->
<?= $this->section("styles") ?>

<?= $this->endSection() ?>

<!-- Conteúdo que está sendo enviado para o Layout Main -->
<?= $this->section("content") ?>

<div class="row">
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="block">
            <div class="block-body">
                <!-- Exibirá os retornos do backend -->
                <div id="response"></div>

                <?= form_open_multipart('/', ['id' => 'form'], ['id' => "$user->id"]) ?>
                    <div class="form-group">
                        <label class="form-control-label">Selecione uma imagem</label>
                        <input type="file" name="avatar" class="form-control">
                    </div>

                    <div class="form-group mt-5 mb-4">
                        <input type="submit" id="btn-save" value="Salvar" class="btn btn-primary">
                        <a href="<?= site_url("users/show/$user->id") ?>" class="btn btn-secondary ml-3">Voltar</a>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<!-- Scripts que estão sendo enviado para o Layout Main -->
<?= $this->section("scripts") ?>

<script>
    $(document).ready(function() {
        $("#form").on("submit", function(event) {
            event.preventDefault();

            $.ajax({
                type: 'POST',
                url: '<?= site_url('users/upload') ?>',
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
                        window.location.href = "<?= site_url("users/show/$user->id") ?>";
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