<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Visão Geral -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total de Usuários -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold">Total de Usuários</h3>
                        <p class="text-2xl font-bold mt-2">{{ \App\Models\User::count() }}</p>
                    </div>
                </div>

                <!-- Solicitações Pendentes -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold">Solicitações Pendentes</h3>
                        <p class="text-2xl font-bold mt-2">{{ \DB::table('reversal_requests')->where('status', 'pending')->count() }}</p>
                    </div>
                </div>

                <!-- Transações Recentes -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold">Transações Recentes</h3>
                        <p class="text-2xl font-bold mt-2">10 Últimas</p>
                    </div>
                </div>
            </div>

            <!-- Solicitações de Reversão -->
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">Solicitações de Reversão Pendentes</h3>

                    @if ($reversalRequests->isEmpty())
                        <p class="mt-2">Nenhuma solicitação pendente no momento.</p>
                    @else
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
                                @foreach ($reversalRequests as $request)
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
                    @endif
                </div>
            </div>

            <!-- Transações Recentes -->
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">Transações Recentes</h3>
                    <table class="table-auto w-full mt-4 border-collapse border border-gray-200 dark:border-gray-700">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700">
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Tipo</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Valor</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Usuário</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentTransactions as $transaction)
                                <tr class="text-center bg-white dark:bg-gray-800">
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        {{ ucfirst($transaction->type) }}
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        R$ {{ number_format(abs($transaction->amount), 2, ',', '.') }}
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        {{ $transaction->user_id }}
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        {{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i') }}
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
