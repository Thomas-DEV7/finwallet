<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Visão Geral -->
            <div class="flex flex-wrap justify-between gap-4">
                <!-- Total de Usuários -->
                <div class="flex-1 bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-300">Total de Usuários</h3>
                    <p class="text-4xl font-bold text-gray-800 dark:text-gray-100 mt-2">{{ $totalUsers }}</p>
                </div>

                <!-- Total de Transações -->
                <div class="flex-1 bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-300">Total de Transações</h3>
                    <p class="text-4xl font-bold text-gray-800 dark:text-gray-100 mt-2">{{ $totalTransactions }}</p>
                </div>

                <!-- Solicitações Pendentes -->
                <div class="flex-1 bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-300">Solicitações Pendentes</h3>
                    <p class="text-4xl font-bold text-gray-800 dark:text-gray-100 mt-2">{{ $pendingReversalRequests }}
                    </p>
                </div>
            </div>


            <br>
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
                                        {{ $transaction->user_name }}
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
