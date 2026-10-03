<?php

namespace App\Controllers;

use App\Models\UserModel;

class UsersController extends BaseController
{
    private UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    public function index()
    {
        return view('staff/index', [
            'users' => $this->users->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function createForm()
    {
        return view('staff/create');
    }

    public function create()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'full_name' => 'required|max_length[100]',
            'password' => 'required|min_length[8]',
            'avatar' => 'permit_empty|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png]|max_size[avatar,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $avatar = $this->uploadAvatar();

        $this->users->insert([
            'username'  => trim($this->request->getPost('username')),
            'full_name' => trim($this->request->getPost('full_name')),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'avatar'    => $avatar,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/staff')->with('success', 'Staff account created.');
    }

    public function edit(int $id)
    {
        return view('staff/edit', [
            'user' => $this->users->find($id),
        ]);
    }

    public function update(int $id)
    {
        $rules = [
            'full_name' => 'required|max_length[100]',
            'password'  => 'permit_empty|min_length[8]',
            'avatar'    => 'permit_empty|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png]|max_size[avatar,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = $this->users->find($id);

        $data = [
            'full_name' => trim($this->request->getPost('full_name')),
        ];

        $password = $this->request->getPost('password');

        if ($password) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $avatar = $this->uploadAvatar();

        if ($avatar) {
            $data['avatar'] = $avatar;
        }

        $this->users->update($id, $data);

        return redirect()->to('/staff')->with('success', 'Staff account updated.');
    }

    public function delete(int $id)
    {
        if ((int) session()->get('user_id') === $id) {
            return redirect()->to('/staff')->with('error', 'You cannot delete your own account.');
        }

        $this->users->delete($id);

        return redirect()->to('/staff')->with('success', 'Staff account deleted.');
    }

    private function uploadAvatar(): ?string
    {
        $file = $this->request->getFile('avatar');

        if (! $file || ! $file->isValid()) {
            return null;
        }

        $directory = FCPATH . 'uploads/avatars';

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $newName = $file->getRandomName();
        $file->move($directory, $newName);

        return $newName;
    }
}