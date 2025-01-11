<x-app-layout>
    <style>
        .c-box {
            margin: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            /* Adiciona sombra */
            border-radius: 0.75rem;
            /* Bordas arredondadas */
            overflow: hidden;
            /* Para garantir que o conteúdo não ultrapasse os limites */
        }

        /* Responsividade para dispositivos móveis */
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
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Toast de Sucesso -->
            @if (session('success'))
                <div id="toast-success"
                    class="  z-50 p-4 mb-4 text-green-500 bg-white border border-green-200 rounded-lg shadow-lg dark:bg-green-800 dark:text-green-200">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Saldo Atual -->
            <div
                class="c-box mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex justify-between items-center">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">Saldo</h3>
                    <p class="text-2xl font-bold mt-2">R$ {{ number_format(auth()->user()->balance, 2, ',', '.') }}</p>
                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col items-center space-y-4 mb-4">
                        <!-- Alterado para flex-col e adicionado espaço entre os botões -->
                        <!-- Botão de Transferir -->
                        <button onclick="openModal('transfer-modal')"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 m-3 dark:bg-green-800"
                            style="">
                            Transferir
                        </button>
                        <!-- Botão de Depositar -->
                        <button onclick="openModal('deposit-modal')"
                            class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 mt-1"
                            style="background-color: #3556e9;">
                            Depositar
                        </button>
                    </div>
                </div>

            </div>
            <br>

            <!-- Últimas Transações -->
            <div class="c-box mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">Últimas Transações</h3>
                    <div class="overflow-x-auto">
                        <table
                            class="table-auto w-full mt-4 border-collapse border border-gray-200 dark:border-gray-700">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Tipo</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Valor</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Origem/Destino
                                    </th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Data</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (auth()->user()->transactions->sortByDesc('created_at')->take(5) as $transaction)
                                    {{-- <pre>{{ json_encode($transaction, JSON_PRETTY_PRINT) }}</pre> --}}

                                    <tr
                                        class="text-center {{ $transaction->amount < 0 ? '' : 'text-green-600 dark:text-green-400' }}">
                                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                            {{ ucfirst($transaction->type) }}
                                        </td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                            <span
                                                class="{{ $transaction->amount < 0 ? '' : 'text-green-600 dark:text-green-400' }}">
                                                R$ {{ number_format(abs($transaction->amount), 2, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                            @if ($transaction->type === 'transfer')
                                                @if ($transaction->amount < 0)
                                                    Para:
                                                    {{ $transaction->recipient ? $transaction->recipient->name : 'Usuário não encontrado' }}
                                                @else
                                                    De:
                                                    {{ $transaction->sender ? $transaction->sender->name : 'Usuário não encontrado' }}
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
                                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                            @if ($transaction->type === 'transfer')
                                                <button onclick="openModal('reversal-modal', {{ $transaction->id }})"
                                                    class="text-red-500 hover:text-red-600 ml-2">
                                                    <i class="fas fa-warning" style="color: #d3d303"></i>
                                                </button>
                                            @else
                                                <button disabled
                                                    onclick="openModal('reversal-modal', {{ $transaction->id }})"
                                                    class="text-gray-500 hover:text-gray-600 ml-2">
                                                    <i class="fas fa-info-circle" style="color: #585858"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal de Transferência -->
            <div id="transfer-modal"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-96 shadow-lg">
                    <h2 class="text-xl font-semibold mb-4 dark:text-white">Transferir Saldo</h2>
                    <form id="transfer-form" method="POST" action="{{ route('wallet.transfer') }}">
                        @csrf
                        <input type="hidden" name="from_user_id" value="{{ auth()->user()->id }}">
                        <div class="mb-4">
                            <label for="to_user_email"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Transferir para (E-mail):
                            </label>
                            <input type="text" id="to-user-email" name="to_user_email" list="user-emails"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2"
                                required>
                            <datalist id="user-emails">
                                @foreach ($allUsers as $user)
                                    <option value="{{ $user->email }}">{{ $user->name }}</option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Valor:
                            </label>
                            <input type="number" id="amount" name="amount" step="0.01"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2"
                                required>
                        </div>
                        <div class="text-center">
                            <button type="button" onclick="closeModal('transfer-modal')"
                                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancelar</button>
                            <button type="submit"
                                class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 ml-3"
                                style="background-color: #3556e9; ">
                                Transferir
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal de Depósito -->
            <div id="deposit-modal"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-96 shadow-lg">
                    <h2 class="text-xl font-semibold mb-4 dark:text-white ">Depositar Saldo</h2>
                    <form id="deposit-form" method="POST" action="{{ route('wallet.deposit') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Valor:
                            </label>
                            <input type="number" id="deposit-amount" name="amount" step="0.01"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2"
                                required>
                        </div>
                        <div class="text-center">
                            <button type="button" onclick="closeModal('deposit-modal')"
                                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancelar</button>
                            <button type="submit"
                                class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 ml-3"
                                style="background-color: #3556e9; ">
                                Depositar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal de Reversão -->
            <div id="reversal-modal"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-96 shadow-lg">
                    <h2 class="text-xl font-semibold mb-4 dark:text-white">Solicitar Reversão</h2>
                    <form id="reversal-form" method="POST" action="{{ route('transactions.reversal.request') }}">
                        @csrf
                        <input type="hidden" id="transaction_id" name="transaction_id">
                        <div class="mb-4">
                            <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Comentário:
                            </label>
                            <textarea id="comment" name="comment" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2"
                                required></textarea>
                        </div>
                        <div class="text-center">
                            <button type="button" onclick="closeModal('reversal-modal')"
                                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancelar</button>
                            <button type="submit"
                                class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 ml-3"
                                style="background-color: #3556e9; ">
                                Solicitar Reversão
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(modalId, transactionId = null) {
            if (transactionId) {
                document.getElementById('transaction_id').value = transactionId;
            }
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        setTimeout(() => {
            const toast = document.getElementById('toast-success');
            if (toast) {
                toast.remove();
            }
        }, 3000);
    </script>
</x-app-layout>
