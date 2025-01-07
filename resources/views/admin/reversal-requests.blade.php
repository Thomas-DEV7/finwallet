<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Solicitações de Reversão') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">Solicitações Pendentes</h3>

                    @if (session('success'))
                        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table-auto w-full mt-4 border-collapse border border-gray-200 dark:border-gray-700">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700">
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Usuário</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Transação</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Comentário</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $request)
                                <tr class="text-center bg-white dark:bg-gray-800">
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        {{ $request->user_uuid }}
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        {{ $request->transaction_uuid }}
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        {{ $request->comment }}
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        <form action="{{ route('admin.reversal.requests.approve', $request->uuid) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button class="text-green-500 hover:text-green-600">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.reversal.requests.reject', $request->uuid) }}" method="POST" class="inline-block ml-2">
                                            @csrf
                                            <button class="text-red-500 hover:text-red-600">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
