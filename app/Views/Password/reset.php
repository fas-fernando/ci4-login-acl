<?= $this->extend("Layout/main_auth") ?>

<!-- Titulo Vindo do Controller e exibindo no Layout Main -->
<?= $this->section("title") ?>

<?= $title ?>

<?= $this->endSection() ?>

<!-- Estilos que estão sendo enviado para o Layout Main -->
<?= $this->section("styles") ?>

<?= $this->endSection() ?>

<!-- Conteúdo que está sendo enviado para o Layout Main -->
<?= $this->section("content") ?>

<div class="col-lg-6">
    <div class="info d-flex align-items-center">
        <div class="content">
            <div class="logo">
                <h1><?= $title ?></h1>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-6 bg-white">
    <div class="form d-flex align-items-center">
        <div class="content">
            <?= form_open('/', ['id' => 'form', 'class' => 'form-validate'], ['token' => $token]) ?>
            <div id="response"></div>

            <div class="form-group">
                <input id="password" type="password" name="password" required data-msg="Por favor, informe sua nova senha" class="input-material">
                <label for="password" class="label-material">Nova senha</label>
            </div>
            <div class="form-group">
                <input id="password_confirmation" type="password" name="password_confirmation" required data-msg="Por favor, confirme sua nova senha" class="input-material">
                <label for="password_confirmation" class="label-material">Confirme nova senha</label>
            </div>
            <input type="submit" id="btn-reset" class="btn btn-primary mb-3" value="Salvar nova senha">
            <?= form_close() ?>
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
                url: '<?= site_url('password/updatepassword') ?>',
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#response").html('');
                    $("#btn-reset").val('Carregando...');
                },
                success: function(response) {
                    $("#btn-reset").val('Salvar nova senha');
                    $("#btn-reset").removeAttr('disabled');
                    $("[name=csrf_order]").val(response.token);

                    if (!response.error) {
                        window.location.href = "<?= site_url('login') ?>";
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
                    $("#btn-reset").val('Salvar nova senha');
                    $("#btn-reset").removeAttr('disabled');
                },
            });
        });

        $('#form').submit(function() {
            $(this).find(':submit').attr('disabled', 'disabled');
        });
    });
</script>

<?= $this->endSection() ?>