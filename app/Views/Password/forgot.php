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
            <p>Informe seu e-mail para receber uma chave de recuperação se senha!</p>
        </div>
    </div>
</div>

<div class="col-lg-6 bg-white">
    <div class="form d-flex align-items-center">
        <div class="content">
            <?= form_open('/', ['id' => 'form', 'class' => 'form-validate']) ?>
            <div id="response"></div>

            <div class="form-group">
                <input id="email" type="email" name="email" required data-msg="Por favor, informe seu e-mail" class="input-material">
                <label for="email" class="label-material">Informe seu e-mail</label>
            </div>
            <input type="submit" id="btn-forgot" class="btn btn-primary mb-3" value="Enviar">
            <?= form_close() ?>

            <a href="<?= site_url('login') ?>" class="forgot-pass">Voltar ao login</a>
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
                url: '<?= site_url('password/reset') ?>',
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#response").html('');
                    $("#btn-forgot").val('Carregando...');
                },
                success: function(response) {
                    $("#btn-forgot").val('Enviar');
                    $("#btn-forgot").removeAttr('disabled');
                    $("[name=csrf_order]").val(response.token);

                    if (!response.error) {
                        window.location.href = "<?= site_url('password/sendreset') ?>";
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
                    $("#btn-forgot").val('Enviar');
                    $("#btn-forgot").removeAttr('disabled');
                },
            });
        });

        $('#form').submit(function() {
            $(this).find(':submit').attr('disabled', 'disabled');
        });
    });
</script>

<?= $this->endSection() ?>