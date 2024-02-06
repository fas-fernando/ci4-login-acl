<div class="form-group">
    <label class="form-control-label">Nome completo</label>
    <input type="text" name="username" placeholder="Insira o nome completo" class="form-control" value="<?= esc($user->username) ?>">
</div>
<div class="form-group">
    <label class="form-control-label">Email</label>
    <input type="email" name="email" placeholder="Insira o email de acesso" class="form-control" value="<?= esc($user->email) ?>">
</div>
<div class="form-group">
    <label class="form-control-label">Senha</label>
    <input type="password" name="password" placeholder="Senha de acesso" class="form-control">
</div>
<div class="form-group">
    <label class="form-control-label">Confirmar senha</label>
    <input type="password" name="password_confirmation" placeholder="Confirme a senha de acesso" class="form-control">
</div>

<div class="custom-control custom-checkbox">
    <input type="hidden" name="status" value="0">
    <input type="checkbox" name="status" value="1" class="custom-control-input" id="status" <?php if($user->status == true) : ?> checked <?php endif ?>>
    <label class="custom-control-label" for="status">Status do usuário</label>
</div>