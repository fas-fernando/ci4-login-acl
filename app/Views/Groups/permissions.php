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
    <div class="col-lg-8">

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
                                <th>Permissão</th>
                                <th class="float-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($group->permissions as $permission) : ?>
                                <tr>
                                    <td><?= esc($permission->name) ?></td>
                                    <td>
                                        <a href="#" class="btn btn-danger btn-sm float-right">
                                            <i class="fa fa-trash"></i>
                                        </a>
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

<?= $this->endSection() ?>