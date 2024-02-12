<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\User;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class Users extends BaseController
{
    private $userModel;
    private $groupUserModel;
    private $groupModel;

    public function __construct()
    {
        $this->userModel = model('App\Models\UserModel');
        $this->groupUserModel = model('App\Models\GroupUserModel');
        $this->groupModel = model('App\Models\GroupModel');
    }

    public function index()
    {
        $data = [
            'title' => 'Lista de usuários',
        ];

        return view('Users/index', $data);
    }

    public function getUsers()
    {
        if(!$this->request->isAJAX()) return redirect()->back();

        $attr = [
            'id',
            'username',
            'email',
            'status',
            'avatar',
            'deleted_at',
        ];

        $users = $this->userModel->select($attr)->withDeleted()->orderBy('id', 'DESC')->findAll();

        $data = [];

        foreach($users as $user) {
            if($user->avatar != null) {
                $avatar = [
                    'src'   => site_url("users/avatar/$user->avatar"),
                    'class' => 'rounded-circle img-fluid',
                    'alt'   => esc($user->username),
                    'width' => '50',
                ];
            } else {
                $avatar = [
                    'src'   => site_url("resources/img/user_default.png"),
                    'class' => 'rounded-circle img-fluid',
                    'alt'   => 'user_default',
                    'width' => '50',
                ];
            }

            $username = esc($user->username);

            $data[] = [
                'avatar'   => $user->avatar = img($avatar),
                'username' => anchor("users/show/$user->id", $username, 'title="Exibir usuário ' . $username . '"'),
                'email'    => esc($user->email),
                'status'   => $user->showSituation(),
            ];
        }

        $returnData = [
            'data' => $data,
        ];

        return $this->response->setJSON($returnData);
    }

    public function create()
    {
        $user = new User();

        $data = [
            'title' => 'Criação do usuário',
            'user'  => $user,
        ];

        return view("Users/create", $data);
    }

    public function store()
    {
        if(!$this->request->isAJAX()) return redirect()->back();

        $returnData['token'] = csrf_hash();

        $post = $this->request->getPost();

        $user = new User($post);

        if($this->userModel->protect(false)->insert($user)) {
            $btnNewUser = anchor("users/create", "Novo usuário", ['class' => 'btn btn-warning btn-sm mt-3']);

            session()->setFlashdata('success', "Dados salvos com sucesso <br> $btnNewUser");

            $returnData['id'] = $this->userModel->getInsertID();
            
            return $this->response->setJSON($returnData);
        }

        $returnData['error'] = 'Por favor, verifique os erros abaixo e tente novamente';
        $returnData['errors_model'] = $this->userModel->errors();

        return $this->response->setJSON($returnData);
    }

    public function show(int $id = null)
    {
        $user = $this->getUserOr404($id);

        $data = [
            'title' => 'Info do usuário ' . esc($user->username),
            'user'  => $user,
        ];

        return view("Users/show", $data);
    }

    public function edit(int $id = null)
    {
        $user = $this->getUserOr404($id);

        $data = [
            'title' => 'Editar usuário ' . esc($user->username),
            'user'  => $user,
        ];

        return view("Users/edit", $data);
    }

    public function update()
    {
        if(!$this->request->isAJAX()) return redirect()->back();

        $returnData['token'] = csrf_hash();

        $post = $this->request->getPost();

        $user = $this->getUserOr404($post['id']);

        if(empty($post['password'])) {
            unset($post['password']);
            unset($post['password_confirmation']);
        }

        $user->fill($post);

        if($user->hasChanged() == false) {
            $returnData['info'] = 'Não há dados para serem atualizados';
            return $this->response->setJSON($returnData);
        }

        if($this->userModel->protect(false)->save($user)) {
            session()->setFlashdata('success', 'Dados salvos com sucesso');
            
            return $this->response->setJSON($returnData);
        }

        $returnData['error'] = 'Por favor, verifique os erros abaixo e tente novamente';
        $returnData['errors_model'] = $this->userModel->errors();

        return $this->response->setJSON($returnData);
    }

    public function delete(int $id = null)
    {
        $user = $this->getUserOr404($id);

        if($user->deleted_at != null) return redirect()->back()->with('info', "Esse usuário já encontra-se excluído");

        if($this->request->getMethod() === 'post') {
            $this->userModel->delete($user->id);

            if($user->avatar != null) $this->removeImageForFileSystem($user->avatar);

            $user->avatar = null;
            $user->status = false;

            $this->userModel->protect(false)->save($user);

            return redirect()->to(site_url('users'))->with('success', "Usuário $user->username deletado com sucesso");
        }

        $data = [
            'title' => 'Exclusão do usuário ' . esc($user->username),
            'user'  => $user,
        ];

        return view("Users/delete", $data);
    }

    public function restore(int $id = null)
    {
        $user = $this->getUserOr404($id);

        if($user->deleted_at == null) return redirect()->back()->with('info', "Apenas usuários excluídos podem ser recuperados");

        $user->deleted_at = null;
        $this->userModel->protect(false)->save($user);

        return redirect()->back()->with('success', "Usuário $user->username recuperado com sucesso");
    }

    public function editImage(int $id = null)
    {
        $user = $this->getUserOr404($id);

        $data = [
            'title' => 'Alterar imagem do usuário ' . esc($user->username),
            'user'  => $user,
        ];

        return view("Users/edit_image", $data);
    }

    public function upload()
    {
        if(!$this->request->isAJAX()) return redirect()->back();

        $returnData['token'] = csrf_hash();

        $validation = service('validation');

        $rules = [
            'avatar' => 'uploaded[avatar]|max_size[avatar,1024]|ext_in[avatar,png,jpg,jpeg,webp]',
        ];

        $messages = [
            'avatar' => [
                'uploaded' => 'Por favor, escolha uma imagem',
                'max_size' => 'A imagem deve ser de no máximo 1024mb',
                'ext_in'   => 'As extenssões permitidas são png, jpg, jpeg e webp'
            ],
        ];

        $validation->setRules($rules, $messages);

        if(!$validation->withRequest($this->request)->run()) {
            $returnData['error']        = 'Por favor, verifique os erros abaixo e tente novamente';
            $returnData['errors_model'] = $validation->getErrors();

            return $this->response->setJSON($returnData);
        }

        $post = $this->request->getPost();

        $user = $this->getUserOr404($post['id']);

        $avatar = $this->request->getFile('avatar');

        list($width, $height) = getimagesize($avatar->getPathName());

        if($width < "300" || $height < "300") {
            $returnData['error']        = 'Por favor, verifique os erros abaixo e tente novamente';
            $returnData['errors_model'] = ['dimension' => 'A imagem não pode ser menor do que 300x300 pixels'];

            return $this->response->setJSON($returnData);
        }

        $pathImage = $avatar->store('users');

        $pathImage = WRITEPATH . "uploads/$pathImage";

        $this->manipulateImage($pathImage, $user->id);

        $oldImage = $user->avatar;

        $user->avatar = $avatar->getName();

        $this->userModel->save($user);

        if($oldImage != null) $this->removeImageForFileSystem($oldImage);

        session()->setFlashdata('success', 'Imagem atualizada com sucesso');

        return $this->response->setJSON($returnData);
    }

    public function groups(int $id = null)
    {
        $user = $this->getUserOr404($id);

        $user->groups = $this->groupUserModel->getGroupsUsers($user->id, 10);
        $user->pager = $this->groupUserModel->pager;

        $data = [
            'title' => 'Gerenciando os grupos de acesso do usuário ' . esc($user->username),
            'user'  => $user,
        ];

        if(in_array(2, array_column($user->groups, 'group_id')))
            return redirect()
            ->to(site_url("users/show/$user->id"))
            ->with('info', 'Esse usuário é um cliente. Portanto não pode pertencer a outro grupo');

        if(in_array(1, array_column($user->groups, 'group_id'))) {
            $user->full_control = true;
            return view("Users/groups", $data);
        }

        $user->full_control = false;

        if(!empty($user->groups)) {
            $groupsExists = array_column($user->groups, 'group_id');

            $data['availableGroups'] = $this->groupModel->where('id !=', 2)->whereNotIn('id', $groupsExists)->findAll();
        } else {
             $data['availableGroups'] = $this->groupModel->where('id !=', 2)->findAll();
        }

        return view("Users/groups", $data);
    }

    public function storeGroups ()
    {
        if(!$this->request->isAJAX()) return redirect()->back();

        $returnData['token'] = csrf_hash();

        $post = $this->request->getPost();

        $user = $this->getUserOr404($post['id']);

        if (empty($post['group_id'])) {
            $returnData['error'] = 'Por favor, verifique os erros abaixo e tente novamente';
            $returnData['errors_model'] = ['group_id' => 'Escolha um ou mais grupos para salvar'];

            return $this->response->setJSON($returnData);
        }

        if (in_array(2, $post['group_id'])) {
            $returnData['error'] = 'Por favor, verifique os erros abaixo e tente novamente';
            $returnData['errors_model'] = ['group_id' => 'O grupo de <strong class="text-white">Clientes</strong> não pode ser atribuído de forma manual'];

            return $this->response->setJSON($returnData);
        }

        if (in_array(1, $post['group_id'])) {
            $groupAdmin = [
                'user_id'  => $user->id,
                'group_id' => 1,
            ];

            $this->groupUserModel->insert($groupAdmin);

            $this->groupUserModel->where('group_id !=', 1)->where('user_id', $user->id)->delete();

            session()->setFlashdata('success', 'Dados salvos com sucesso');
            session()->setFlashdata('info', 'Notamos que o Grupo Administrador foi informado, portanto, não há necessidade de informar outros grupos, pois apenas o Administrador será associado ao usuário');

            return $this->response->setJSON($returnData);
        }

        $groupPush = [];

        foreach($post['group_id'] as $group) {
            array_push($groupPush, [
                'user_id'  => $user->id,
                'group_id' => $group,
            ]);
        }

        $this->groupUserModel->insertBatch($groupPush);

        session()->setFlashdata('success', 'Dados salvos com sucesso');

        return $this->response->setJSON($returnData);
    }

    public function removeGroup(int $main_id = null)
    {
        if ($this->request->getMethod() === 'post') {
            $groupUser = $this->getGroupUserOr404($main_id);

            if($groupUser->group_id == 2)
                return redirect()->to(site_url("users/show/$groupUser->user_id"))->with('info', 'Não é permitida a exclusão do usuário do grupo Clientes');
        
            $this->groupUserModel->delete($main_id);

            return redirect()->back()->with('success', 'Grupo removido com sucesso');
        }

        return redirect()->back();
    }

    public function editPassword()
    {
        $data = [
            'title' => 'Editar senha de acesso',
        ];

        return view("Users/edit_password", $data);
    }

    public function updatePassword()
    {
        if(!$this->request->isAJAX()) return redirect()->back();

        $returnData['token'] = csrf_hash();

        $current_password = $this->request->getPost('current_password');

        $user = user_logged();

        if($user->checkPassword($current_password) === false) {
            $returnData['error']        = 'Por favor, verifique os erros abaixo e tente novamente';
            $returnData['errors_model'] = ['current_password' => 'Senha atual invalida'];

            return $this->response->setJSON($returnData);
        }

        $user->fill($this->request->getPost());

        if($user->hasChanged() === false) return $returnData['info'] = 'Não há dados para atualizar';

        if($this->userModel->save($user)) {
            $returnData['success'] = 'Senha atualizada com sucesso';
            // session()->setFlashdata('success', 'Dados salvos com sucesso');
            
            return $this->response->setJSON($returnData);
        }

        $returnData['error'] = 'Por favor, verifique os erros abaixo e tente novamente';
        $returnData['errors_model'] = $this->userModel->errors();

        return $this->response->setJSON($returnData);
    }

    private function getUserOr404(int $id = null)
    {
        $user = $this->userModel->withDeleted(true)->find($id);

        if(!$id || !$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o usuário $id");
        }

        return $user;
    }

    private function getGroupUserOr404(int $main_id = null)
    {
        $groupUser = $this->groupUserModel->find($main_id);

        if(!$main_id || !$groupUser) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o registro de associação ao grupo de acesso $main_id");
        }

        return $groupUser;
    }

    private function removeImageForFileSystem(string $avatar)
    {
        $pathImage = WRITEPATH . "uploads/users/$avatar";

        if(is_file($pathImage)) {
            unlink($pathImage);
        }
    }

    public function avatar(string $avatar = null)
    {
        if($avatar != null) $this->showFile('users', $avatar);
    }

    private function manipulateImage(string $pathImage, int $user_id)
    {
        service('image')
            ->withFile($pathImage)
            ->fit(300, 300, 'center')
            ->save($pathImage);

        $yearCurrent = date('Y');

        \Config\Services::image('imagick')
            ->withFile($pathImage)
            ->text("3C Services $yearCurrent - User ID $user_id", [
                'color'      => '#fff',
                'opacity'    => 0.5,
                'withShadow' => false,
                'hAlign'     => 'center',
                'vAlign'     => 'bottom',
                'fontSize'   => 10,
            ])
            ->save($pathImage);
    }
}