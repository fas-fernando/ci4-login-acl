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
    <?php if($group->id < 3) : ?>
        <div class="col-lg-12">
            <div class="alert alert-info" role="alert">
                <h4 class="alert-heading">Importante!</h4>
                <p>O grupo <strong><?= esc($group->name) ?></strong> não pode ser editado ou excluído, pois o mesmo não pode ter suas permissões revogadas.</p>
                <hr>
                <p class="mb-0">Não se preocupe, pois os demais grupos podem ser editados ou removidos.</p>
            </div>
        </div>
    <?php endif ?>

    <div class="col-lg-4">
        <div class="user-block block">
            <h5 class="card-title"><?= esc($group->name) ?></h5>
            <p class="card-text"><strong>Descrição:</strong> <?= esc($group->description) ?></p>
            <p class="contributions mt-0">
                <?= $group->showSituation() ?>
                <?php if ($group->deleted_at == null) : ?>
                    <a tabindex="0" class="ml-3" role="button" data-toggle="popover" data-trigger="focus" title="Importante" data-content="Esse grupo <?= ($group->show == true ? 'será' : 'não será') ?> exibido como opção na hora de definir um <strong>Responsável técnico</strong> pela ordem de serviço."><i class="fa fa-question-circle fa-lg text-warning"></i></a>
                <?php endif ?>
            </p>
            <p class="card-text"><strong>Criado:</strong> <?= $group->created_at->humanize() ?></p>
            <p class="card-text"><strong>Atualizado:</strong> <?= $group->updated_at->humanize() ?></p>

            <hr class="border-secondary">
            <?php if ($group->id > 2) : ?>
                <div class="btn-group mr-3">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Ações
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?= site_url("groups/edit/$group->id") ?>">Editar</a>
                        <div class="dropdown-divider"></div>
                        <?php if ($group->deleted_at == null) : ?>
                            <a class="dropdown-item" href="<?= site_url("groups/delete/$group->id") ?>">Excluir</a>
                        <?php else : ?>
                            <a class="dropdown-item" href="<?= site_url("groups/restore/$group->id") ?>">Restaurar</a>
                        <?php endif ?>
                    </div>
                </div>
            <?php endif ?>
            <a href="<?= site_url("groups") ?>" class="btn btn-secondary">Voltar</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<!-- Scripts que estão sendo enviado para o Layout Main -->
<?= $this->section("scripts") ?>

<?= $this->endSection() ?>