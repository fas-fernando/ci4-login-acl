<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Group;
use CodeIgniter\HTTP\ResponseInterface;

class Groups extends BaseController
{
    private $groupModel;

    public function __construct()
    {
        $this->groupModel = model('App\Models\GroupModel');
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
        if(!$this->request->isAJAX()) return redirect()->back();

        $attr = [
            'id',
            'name',
            'description',
            'show',
            'deleted_at',
        ];

        $groups = $this->groupModel->select($attr)->withDeleted()->orderBy('id', 'DESC')->findAll();

        $data = [];

        foreach($groups as $group) {

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

    public function show(int $id = null)
    {
        $group = $this->getGroupOr404($id);

        $data = [
            'title' => 'Info do grupo ' . esc($group->name),
            'group' => $group,
        ];

        return view("Groups/show", $data);
    }

    private function getGroupOr404(int $id = null)
    {
        $group = $this->groupModel->withDeleted(true)->find($id);

        if(!$id || !$group) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o grupo $id");
        }

        return $group;
    }
}
