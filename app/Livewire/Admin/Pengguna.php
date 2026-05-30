<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Pengguna extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    // Modal
    public bool $showModal       = false;
    public bool $showDeleteModal = false;
    public ?int $editId          = null;
    public ?int $deleteId        = null;

    // Alert
    public string $message     = '';
    public string $messageType = 'success';

    // Form fields
    public string $name     = '';
    public string $email    = '';
    public string $password = '';
    public string $role     = 'teknisi';

    public function updatingSearch(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->resetFormFields();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $user = User::findOrFail($id);

        $this->editId   = $id;
        $this->name     = $user->name;
        $this->email    = $user->email;
        $this->password = '';
        $this->role     = $user->role;
        $this->showModal= true;
    }

    public function save(): void
    {
        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($this->editId ?? 'NULL'),
            'role'  => 'required|in:admin,teknisi',
        ];

        if (!$this->editId) {
            $rules['password'] = 'required|min:8';
        } elseif ($this->password) {
            $rules['password'] = 'min:8';
        }

        $this->validate($rules, [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.unique'      => 'Email sudah digunakan oleh pengguna lain.',
            'password.required' => 'Password wajib diisi untuk pengguna baru.',
            'password.min'      => 'Password minimal 8 karakter.',
            'role.required'     => 'Role wajib dipilih.',
        ]);

        $data = [
            'name'  => $this->name,
            'email' => $this->email,
            'role'  => $this->role,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->editId) {
            User::findOrFail($this->editId)->update($data);
            $this->message = 'Data pengguna berhasil diperbarui.';
        } else {
            User::create($data);
            $this->message = 'Pengguna baru berhasil ditambahkan.';
        }

        $this->messageType = 'success';
        $this->showModal   = false;
        $this->resetFormFields();
    }

    public function confirmDelete(int $id): void
    {
        if ($id === Auth::id()) {
            $this->message     = 'Tidak dapat menghapus akun yang sedang aktif.';
            $this->messageType = 'error';
            return;
        }

        $this->deleteId        = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        User::findOrFail($this->deleteId)->delete();
        $this->showDeleteModal = false;
        $this->deleteId        = null;
        $this->message         = 'Pengguna berhasil dihapus.';
        $this->messageType     = 'success';
    }

    public function closeModal(): void
    {
        $this->showModal       = false;
        $this->showDeleteModal = false;
        $this->resetFormFields();
    }

    private function resetFormFields(): void
    {
        $this->reset(['name', 'email', 'password', 'editId']);
        $this->role = 'teknisi';
    }

    public function render(): View
    {
        $data = User::query()
            ->when($this->search, fn ($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
            )
            ->orderBy('role')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.pengguna', compact('data'))
            ->layout('components.layouts.admin', ['heading' => 'Kelola Pengguna']);
    }
}
