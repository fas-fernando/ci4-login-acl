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
    <div class="col-lg-6">
        <div class="block">
            <div class="block-body">
                <?= form_open("groups/delete/$group->id") ?>
                    <div class="alert alert-warning" role="alert">
                        <h4 class="alert-heading">Atenção</h4>
                        <p>Tem certeza que deseja excluir o grupo - <?= esc($group->name) ?></p>
                        <hr>
                        <p class="mb-0">Clique no botão excluir para confirmar</p>
                    </div>

                    <div class="form-group mt-5 mb-4">
                        <input type="submit" id="btn-save" value="Excluir" class="btn btn-primary">
                        <a href="<?= site_url("groups/show/$group->id") ?>" class="btn btn-secondary ml-3">Cancelar</a>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<!-- Scripts que estão sendo enviado para o Layout Main -->
<?= $this->section("scripts") ?>

<?= $this->endSection() ?>