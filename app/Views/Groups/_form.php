<div class="form-group">
    <label class="form-control-label">Nome do grupo</label>
    <input type="text" name="name" placeholder="Insira o nome do grupo de acesso" class="form-control" value="<?= esc($group->name) ?>">
</div>
<div class="form-group">
    <label class="form-control-label">Descrição</label>
    <input type="text" name="description" placeholder="Insira uma descrição do grupo" class="form-control" value="<?= esc($group->description) ?>">
</div>

<div class="custom-control custom-checkbox">
    <input type="hidden" name="show" value="0">
    <input type="checkbox" name="show" value="1" class="custom-control-input" id="show" <?php if ($group->show == true) : ?> checked <?php endif ?>>
    <label class="custom-control-label" for="show">Exibir grupo de acesso</label>
    <a tabindex="0" class="ml-3" role="button" data-toggle="popover" data-trigger="focus" title="Importante" data-content="Se esse grupo for defnido como <strong class='text-danger'>Exibir grupo de acesso</strong> ele será mostrado na hora de definir um <strong>Responsável técnico</strong> pela ordem de serviço."><i class="fa fa-question-circle fa-lg text-warning"></i></a>
</div>