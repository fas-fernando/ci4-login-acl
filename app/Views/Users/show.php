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
    <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <div class="block">
            <div class="text-center">
                <?php if ($user->avatar == null) : ?>
                    <img src="<?= site_url('resources/img/user_default.png') ?>" class="card-img-top" style="width:90%" alt="user_default">
                <?php else : ?>
                    <img src="<?= site_url("users/image/$user->avatar") ?>" class="card-img-top" style="width:90%" alt="<?= esc($user->avatar) ?>">
                <?php endif ?>

                <a href="<?= site_url("users/editimage/$user->id") ?>" class="btn btn-outline-primary btn-sm mt-3">Alterar Avatar</a>
            </div>

            <hr class="border-secondary">

            <h5 class="card-title"><?= esc($user->username) ?></h5>
            <p class="card-text"><strong>E-mail:</strong> <?= esc($user->email) ?></p>
            <p class="card-text"><strong>Criado:</strong> <?= $user->created_at ?></p>
            <p class="card-text"><strong>Atualizado:</strong> <?= $user->updated_at ?></p>

            <hr class="border-secondary">
            <div class="btn-group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    Ações
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?= site_url("users/edit/$user->id") ?>">Editar</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Separated link</a>
                </div>
            </div>
            <a href="<?= site_url("users") ?>" class="btn btn-secondary ml-3">Voltar</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<!-- Scripts que estão sendo enviado para o Layout Main -->
<?= $this->section("scripts") ?>

<?= $this->endSection() ?>