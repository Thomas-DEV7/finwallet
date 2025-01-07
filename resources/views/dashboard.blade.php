<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Saldo Atual -->
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">Seu saldo atual</h3>
                    <p class="text-2xl font-bold mt-2">R$ {{ number_format(auth()->user()->balance, 2, ',', '.') }}</p>
                </div>
            </div>
            <br>

            <!-- Últimas Transações -->
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg ">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">Últimas Transações</h3>
                    <table class="table-auto w-full mt-4 border-collapse border border-gray-200 dark:border-gray-700">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700">
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Tipo</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Valor</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Origem/Destino</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Data</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (auth()->user()->transactions->sortByDesc('created_at')->take(5) as $transaction)
                                <tr class="text-center {{ $transaction->amount < 0 ? 'bg-red-100' : 'bg-green-100' }}">
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        {{ ucfirst($transaction->type) }}
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        <span
                                            class="{{ $transaction->amount < 0 ? 'text-black' : 'text-green-600 dark:text-green-400' }}">
                                            R$ {{ number_format(abs($transaction->amount), 2, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        @if ($transaction->type === 'transfer')
                                            @if ($transaction->amount < 0)
                                                Para: {{ $transaction->recipient->name }}
                                            @else
                                                De: {{ $transaction->sender->name }}
                                            @endif
                                        @elseif ($transaction->type === 'deposit')
                                            Depósito
                                        @else
                                            ---
                                        @endif
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-center">
                                        <!-- Ícone para abrir o modal de detalhes -->
                                        <button onclick="openModal('{{ $transaction->id }}')"
                                            class="text-blue-500 hover:text-blue-600">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Ícone para solicitar reversão -->
                                        <button onclick="openReversalModal('{{ $transaction->id }}')"
                                            class="text-red-500 hover:text-red-600 ml-2">
                                            <i class="fas fa-undo-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal de Solicitação de Reversão -->
            <div id="reversal-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-96">
                    <h2 class="text-xl font-semibold mb-4">Solicitar Reversão</h2>
                    <form id="reversal-form" method="POST" action="{{ route('transactions.reversal.request') }}">
                        @csrf
                        <input type="hidden" id="transaction_id" name="transaction_id">
                        <input type="hidden" id="user_id" name="user_id" value="{{ auth()->user()->uuid }}">

                        <div class="mb-4">
                            <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Comentário
                            </label>
                            <textarea id="comment" name="comment" rows="3"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                                required></textarea>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <button type="button" onclick="closeModal()"
                                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="bg-green-400 px-4 py-2 rounded-md hover:bg-green-600">
                                Solicitar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function openModal(transactionId) {
        document.getElementById('transaction_id').value = transactionId;
        document.getElementById('reversal-modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('reversal-modal').classList.add('hidden');
    }
</script>
