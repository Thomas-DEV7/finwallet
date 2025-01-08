<x-app-layout>
    <style>
        @media (max-width: 640px) {
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            th,
            td {
                min-width: 100px;
                text-align: left;
            }
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Usuários') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold">Lista de Usuários</h3>
                <table class="table-auto w-full mt-4 border-collapse border border-gray-200 dark:border-gray-700">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Nome</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Email</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Role</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="text-center bg-white dark:bg-gray-800">
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ $user->name }}
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ $user->email }}
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                    {{ ucfirst($user->role) }}</td>
                                <td
                                    class="border border-gray-300 dark:border-gray-600 px-4 py-2 flex justify-center gap-4">
                                    <!-- Botão de Editar -->
                                    <button onclick="openEditModal({{ $user }})"
                                        class="text-blue-500 hover:text-blue-600">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <!-- Botão de Excluir -->
                                    <button onclick="openDeleteModal({{ $user->id }})"
                                        class="text-red-500 hover:text-red-600">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de Edição -->
    <div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-96 shadow-lg">
            <h2 class="text-xl font-semibold mb-4">Editar Usuário</h2>
            <form id="edit-form" method="POST" action="{{ route('admin.users.update') }}">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-user-id" name="user_id">
                <div class="mb-4">
                    <label for="name"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nome</label>
                    <input type="text" id="edit-name" name="name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                </div>
                <div class="mb-4">
                    <label for="role"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-200">Role</label>
                    <select id="edit-role" name="role"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeModal('edit-modal')"
                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancelar</button>
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition"
                        style="background-color: #3556e9; margin-left:10px">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Exclusão -->
    <div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-96 shadow-lg">
            <h2 class="text-xl font-semibold mb-4">Excluir Usuário</h2>
            <p class="text-sm mb-4">Tem certeza de que deseja excluir este usuário? Essa ação não pode ser desfeita.</p>
            <form id="delete-form" method="POST" action="{{ route('admin.users.delete') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" id="delete-user-id" name="user_id">
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeModal('delete-modal')"
                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancelar</button>
                    <button type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:ring-2 focus:ring-red-500 transition"
                        style="background-color: rgb(161, 26, 26); margin-left:10px">

                        Excluir
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Função para abrir o modal de edição
        function openEditModal(user) {
            document.getElementById('edit-user-id').value = user.id;
            document.getElementById('edit-name').value = user.name;
            document.getElementById('edit-role').value = user.role;
            document.getElementById('edit-modal').classList.remove('hidden');
        }

        // Função para abrir o modal de exclusão
        function openDeleteModal(userId) {
            document.getElementById('delete-user-id').value = userId;
            document.getElementById('delete-modal').classList.remove('hidden');
        }

        // Função para fechar o modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
</x-app-layout>
