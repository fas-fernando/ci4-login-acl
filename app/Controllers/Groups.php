<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Group;
use CodeIgniter\HTTP\ResponseInterface;

class Groups extends BaseController
{
    private $groupModel;
    private $groupPermissionModel;
    private $permissionModel;

    public function __construct()
    {
        $this->groupModel = model('App\Models\GroupModel');
        $this->groupPermissionModel = model('App\Models\GroupPermissionModel');
        $this->permissionModel = model('App\Models\PermissionModel');
    }

    public function index()
    {
        $data = [
            'title' => 'Lista de Grupos',
        ];

        return view('Groups/index', $data);
    }

    public function getGroups()
    {
        if (!$this->request->isAJAX()) return redirect()->back();

        $attr = [
            'id',
            'name',
            'description',
            'show',
            'deleted_at',
        ];

        $groups = $this->groupModel->select($attr)->withDeleted()->orderBy('id', 'DESC')->findAll();

        $data = [];

        foreach ($groups as $group) {

            $name = esc($group->name);

            $data[] = [
                'name'        => anchor("groups/show/$group->id", $name, 'title="Exibir grupo ' . $name . '"'),
                'description' => esc($group->description),
                'show'        => $group->showSituation(),
            ];
        }

        $returnData = [
            'data' => $data,
        ];

        return $this->response->setJSON($returnData);
    }

    public function create()
    {
        $group = new Group();

        $data = [
            'title' => 'Criação do grupo',
            'group' => $group,
        ];

        return view("Groups/create", $data);
    }

    public function store()
    {
        if (!$this->request->isAJAX()) return redirect()->back();

        $returnData['token'] = csrf_hash();

        $post = $this->request->getPost();

        $group = new Group($post);

        if ($this->groupModel->insert($group)) {
            $btnNewGroup = anchor("groups/create", "Novo grupo", ['class' => 'btn btn-warning btn-sm mt-3']);

            session()->setFlashdata('success', "Dados salvos com sucesso <br> $btnNewGroup");

            $returnData['id'] = $this->groupModel->getInsertID();

            return $this->response->setJSON($returnData);
        }

        $returnData['error'] = 'Por favor, verifique os erros abaixo e tente novamente';
        $returnData['errors_model'] = $this->groupModel->errors();

        return $this->response->setJSON($returnData);
    }

    public function show(int $id = null)
    {
        $group = $this->getGroupOr404($id);

        $data = [
            'title' => 'Info do grupo ' . esc($group->name),
            'group' => $group,
        ];

        return view("Groups/show", $data);
    }

    public function edit(int $id = null)
    {
        $group = $this->getGroupOr404($id);

        if ($group->id < 3)
            return redirect()->back()->with('attention', 'O grupo <strong>' . esc($group->name) . '</strong> não pode ser editado ou excluído');

        $data = [
            'title' => 'Editar grupo ' . esc($group->name),
            'group' => $group,
        ];

        return view("Groups/edit", $data);
    }

    public function update()
    {
        if (!$this->request->isAJAX()) return redirect()->back();

        $returnData['token'] = csrf_hash();

        $post = $this->request->getPost();

        $group = $this->getGroupOr404($post['id']);

        if ($group->id < 3) {
            $returnData['error'] = 'Por favor, verifique os erros abaixo e tente novamente';
            $returnData['errors_model'] = ['group' => 'O grupo <strong class="text-white">' . esc($group->name) . '</strong> não pode ser editado ou excluído'];

            return $this->response->setJSON($returnData);
        }

        $group->fill($post);

        if ($group->hasChanged() == false) {
            $returnData['info'] = 'Não há dados para serem atualizados';
            return $this->response->setJSON($returnData);
        }

        if ($this->groupModel->save($group)) {
            session()->setFlashdata('success', 'Dados salvos com sucesso');

            return $this->response->setJSON($returnData);
        }

        $returnData['error'] = 'Por favor, verifique os erros abaixo e tente novamente';
        $returnData['errors_model'] = $this->groupModel->errors();

        return $this->response->setJSON($returnData);
    }

    public function delete(int $id = null)
    {
        $group = $this->getGroupOr404($id);

        if ($group->id < 3)
            return redirect()->back()->with('attention', 'O grupo <strong>' . esc($group->name) . '</strong> não pode ser editado ou excluído');

        if ($group->deleted_at != null) return redirect()->back()->with('info', "Esse grupo já encontra-se excluído");

        if ($this->request->getMethod() === 'post') {
            $this->groupModel->delete($group->id);

            return redirect()->to(site_url('groups'))->with('success', 'Grupo <strong>' . esc($group->name) . '</strong> deletado com sucesso');
        }

        $data = [
            'title' => 'Exclusão do grupo ' . esc($group->name),
            'group'  => $group,
        ];

        return view("Groups/delete", $data);
    }

    public function restore(int $id = null)
    {
        $group = $this->getGroupOr404($id);

        if ($group->deleted_at == null) return redirect()->back()->with('info', "Apenas grupos excluídos podem ser recuperados");

        $group->deleted_at = null;
        $this->groupModel->protect(false)->save($group);

        return redirect()->back()->with('success', 'Grupo <strong>' . $group->name . '</strong> recuperado com sucesso');
    }

    public function permissions(int $id = null)
    {
        $group = $this->getGroupOr404($id);

        if ($group->id < 3)
            return redirect()->back()->with('info', 'Não é necessário atribuir ou remover permissões de acesso para o grupo de <strong>' . esc($group->name) . '</strong>');

        if ($group->id > 2) {
            $group->permissions = $this->groupPermissionModel->getPermissionsGroup($group->id, 10);
            $group->pager = $this->groupPermissionModel->pager;
        }

        $data = [
            'title' => 'Gerenciando as permissões do grupo ' . esc($group->name),
            'group' => $group,
        ];

        if (!empty($group->permissions)) {
            $permissionsExists = array_column($group->permissions, 'permission_id');

            $data['availablePermissions'] = $this->permissionModel->whereNotIn('id', $permissionsExists)->findAll();
        } else {
            $data['availablePermissions'] = $this->permissionModel->findAll();
        }

        return view("Groups/permissions", $data);
    }

    public function storePermissions()
    {
        if (!$this->request->isAJAX()) return redirect()->back();

        $returnData['token'] = csrf_hash();

        $post = $this->request->getPost();

        $group = $this->getGroupOr404($post['id']);

        if (empty($post['permission_id'])) {
            $returnData['error'] = 'Por favor, verifique os erros abaixo e tente novamente';
            $returnData['errors_model'] = ['permission_id' => 'Escolha uma ou mais permissões para salvar'];

            return $this->response->setJSON($returnData);
        }

        $permissionPush = [];

        foreach($post['permission_id'] as $permission) {
            array_push($permissionPush, [
                'group_id'      => $group->id,
                'permission_id' => $permission,
            ]);
        }

        $this->groupPermissionModel->insertBatch($permissionPush);

        session()->setFlashdata('success', 'Dados salvos com sucesso');

        return $this->response->setJSON($returnData);
    }

    public function removePermission(int $main_id = null)
    {
        if ($this->request->getMethod() === 'post') {
            $this->groupPermissionModel->delete($main_id);

            return redirect()->back()->with('success', 'Permissão removida com sucesso');
        }

        return redirect()->back();
    }

    private function getGroupOr404(int $id = null)
    {
        $group = $this->groupModel->withDeleted(true)->find($id);

        if (!$id || !$group) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o grupo $id");
        }

        return $group;
    }
}
