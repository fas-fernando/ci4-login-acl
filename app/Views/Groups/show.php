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
    <div class="col-lg-4">
        <div class="user-block block">
            <h5 class="card-title"><?= esc($group->name) ?></h5>
            <p class="card-text"><strong>Descrição:</strong> <?= esc($group->description) ?></p>
            <p class="contributions mt-0"><?= $group->showSituation() ?></p>
            <p class="card-text"><strong>Criado:</strong> <?= $group->created_at->humanize() ?></p>
            <p class="card-text"><strong>Atualizado:</strong> <?= $group->updated_at->humanize() ?></p>

            <hr class="border-secondary">
            <div class="btn-group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    Ações
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?= site_url("groups/edit/$group->id") ?>">Editar</a>
                    <div class="dropdown-divider"></div>
                    <?php if($group->deleted_at == null) : ?>
                        <a class="dropdown-item" href="<?= site_url("groups/delete/$group->id") ?>">Excluir</a>
                    <?php else : ?>
                        <a class="dropdown-item" href="<?= site_url("groups/restore/$group->id") ?>">Restaurar</a>
                    <?php endif ?>
                </div>
            </div>
            <a href="<?= site_url("groups") ?>" class="btn btn-secondary ml-3">Voltar</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<!-- Scripts que estão sendo enviado para o Layout Main -->
<?= $this->section("scripts") ?>

<?= $this->endSection() ?>