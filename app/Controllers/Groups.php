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
                'show'        => ($group->show == true ? '<i class="fa fa-eye text-secondary"></i>&nbsp;Exibir Grupo' : '<i class="fa fa-eye-slash text-danger"></i>&nbsp;Não Exibir Grupo' ),
            ];
        }

        $returnData = [
            'data' => $data,
        ];

        return $this->response->setJSON($returnData);
    }
}
