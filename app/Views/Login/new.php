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
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
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
                <label for="email" class="label-material">E-mail</label>
            </div>
            <div class="form-group">
                <input id="password" type="password" name="password" required data-msg="Por favor, informe sua senha" class="input-material">
                <label for="password" class="label-material">Senha</label>
            </div>
            <input type="submit" id="btn-login" class="btn btn-primary mb-3" value="Entrar">
            <?= form_close() ?>

            <a href="<?= site_url('forgot') ?>" class="forgot-pass">Esqueceu sua senha?</a>
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
                url: '<?= site_url('login/store') ?>',
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $("#response").html('');
                    $("#btn-login").val('Carregando...');
                },
                success: function(response) {
                    $("#btn-login").val('Entrar');
                    $("#btn-login").removeAttr('disabled');
                    $("[name=csrf_order]").val(response.token);

                    if (!response.error) {
                        window.location.href = "<?= site_url() ?>" + response.redirect;
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
                    $("#btn-login").val('Entrar');
                    $("#btn-login").removeAttr('disabled');
                },
            });
        });

        $('#form').submit(function() {
            $(this).find(':submit').attr('disabled', 'disabled');
        });
    });
</script>

<?= $this->endSection() ?>