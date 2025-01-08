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
            {{ __('Solicitações de Reversão') }}
        </h2>
    </x-slot>

    <!-- Toast de Sucesso -->
    @if (session('success'))
        <div id="toast-success"
            class="fixed top-5 right-5 z-50 flex justify-between items-center max-w-sm w-full p-4 mb-4 text-green-500 bg-white border border-green-200 rounded-lg shadow-lg dark:bg-green-800 dark:text-green-200 animate-fade-in"
            role="alert">
            <!-- Ícone e Texto -->
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-2xl"></i>
                <span class="text-sm font-medium">
                    {{ session('success') }}
                </span>
            </div>
            <!-- Botão de Fechar -->
            <button type="button"
                class="text-green-500 hover:bg-green-100 rounded-full p-2 transition focus:outline-none"
                aria-label="Close" onclick="dismissToast()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Solicitações Pendentes</h3>

                    <table class="table-auto w-full mt-4 border-collapse border border-gray-200 dark:border-gray-700">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Usuário</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Transação</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Comentário</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Status</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Created At</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Updated At</th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reversalRequests as $request)
                                <tr
                                    class="text-center bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        {{ $request->user->name }}
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        <small>{{ $request->transaction_uuid }}</small>
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        {{ $request->comment }}
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        @if ($request->status === 'pending')
                                            <span class="text-yellow-500">Pendente</span>
                                        @elseif ($request->status === 'approved')
                                            <span class="text-green-500">Aceita</span>
                                        @elseif ($request->status === 'rejected')
                                            <span class="text-red-500">Rejeitada</span>
                                        @endif
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        <small>{{ \Illuminate\Support\Carbon::parse($request->created_at)->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        <small>{{$request->created_at == $request->updated_at ? '- - -' : \Illuminate\Support\Carbon::parse($request->updated_at)->format('d/m/Y H:i') }}</small>
                                    </td>


                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        @if ($request->status === 'pending')
                                            <form
                                                action="{{ route('admin.reversal.requests.approve', $request->uuid) }}"
                                                method="POST" class="inline-block">
                                                @csrf
                                                <button class="text-green-500 hover:text-green-600">
                                                    <i class="fas fa-check" style="color: #1cbe1c"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.reversal.requests.reject', $request->uuid) }}"
                                                method="POST" class="inline-block ml-2">
                                                @csrf
                                                <button class="text-red-500 hover:text-red-600">
                                                    <i class="fas fa-times" style="color: red"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-500">Sem ações</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-gray-500 dark:text-gray-400 py-4">
                                        Nenhuma solicitação de reversão pendente.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    // Função para ocultar o toast
    function dismissToast() {
        const toast = document.getElementById('toast-success');
        toast.style.display = 'none';
    }

    // Remove o toast automaticamente após 5 segundos
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            const toast = document.getElementById('toast-success');
            if (toast) {
                toast.style.display = 'none';
            }
        }, 5000);
    });
</script>
