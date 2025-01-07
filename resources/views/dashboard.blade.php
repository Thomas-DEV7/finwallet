<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Botão de Transferir -->

            <!-- Saldo Atual -->
            <div
                class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex justify-between items-center">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">Seu saldo atual</h3>
                    <p class="text-2xl font-bold mt-2">R$ {{ number_format(auth()->user()->balance, 2, ',', '.') }}</p>
                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-end mb-4">
                        <button onclick="openTransferModal()"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
                            style="background-color: #272727; ">

                            Transferir
                        </button>
                        <button onclick="openTransferModal()"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 ml-3"
                            style="background-color: #3556e9; ">

                            Depositar
                        </button>
                    </div>
                </div>
            </div>
            <br>



            <!-- Últimas Transações -->
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">Últimas Transações</h3>
                    <table class="table-auto w-full mt-4 border-collapse border border-gray-200 dark:border-gray-700">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700">
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Tipo</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Valor</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Origem/Destino</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Data</th>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- Modal de Transferência -->
            <div id="transfer-modal"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-96 shadow-lg">
                    <h2 class="text-xl font-semibold mb-4">Transferir Saldo</h2>
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
                                Valor (máximo permitido: R$
                                {{ number_format(auth()->user()->balance, 2, ',', '.') }}):
                            </label>
                            <input type="number" id="amount" name="amount" step="0.01"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2"
                                required>
                        </div>
                        <div class="text-center">
                            <button type="button" onclick="closeModal('transfer-modal')"
                                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancelar</button>
                            <button type="submit"
                                class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 ml-3"
                                style="background-color: #3556e9; ">Transferir</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openTransferModal() {
            document.getElementById('transfer-modal').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
</x-app-layout>
