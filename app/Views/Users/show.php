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
    <div class="col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
        <div class="block">
            <div class="text-center">
                <?php if ($user->avatar == null) : ?>
                    <img src="<?= site_url('resources/img/user_default.png') ?>" class="card-img-top" style="width:300px; height:300px" alt="user_default">
                <?php else : ?>
                    <img src="<?= site_url("users/image/$user->avatar") ?>" class="card-img-top" style="width:300px; height:300px" alt="<?= esc($user->avatar) ?>">
                <?php endif ?>

                <div>
                    <a href="<?= site_url("users/editimage/$user->id") ?>" class="btn btn-outline-primary btn-sm mt-3">Alterar Avatar</a>
                </div>
            </div>

            <hr class="border-secondary">

            <h5 class="card-title"><?= esc($user->username) ?></h5>
            <p class="card-text"><strong>E-mail:</strong> <?= esc($user->email) ?></p>
            <p class="card-text"><strong></strong><?= ($user->status == true) ? '<i class="text-success fa fa-unlock"></i> <span class="text-success">Ativo</span>' : '<i class="text-danger fa fa-lock"></i> <span class="text-danger">Inativo</span>' ?></p>
            <p class="card-text"><strong>Criado:</strong> <?= $user->created_at->humanize() ?></p>
            <p class="card-text"><strong>Atualizado:</strong> <?= $user->updated_at->humanize() ?></p>

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