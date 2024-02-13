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

<div class="col-lg-12">
    <div class="info d-flex align-items-center">
        <div class="content">
            <div class="logo">
                <h1><?= $title ?></h1>
            </div>
            <p>Acesse seu e-mail e clique no link de recuperação. Caso não encontre na caixa de entrada verifique no span ou lixo eletrônico</p>
        </div>
    </div>
</div>

<div class="col-lg-6 bg-white d-none">
    <div class="form d-flex align-items-center">
        <div class="content">
            
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<!-- Scripts que estão sendo enviado para o Layout Main -->
<?= $this->section("scripts") ?>

<?= $this->endSection() ?>