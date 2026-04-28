<x-layouts.app title="Operadores" eyebrow="Cadastro">
    <x-slot:actions>
        <x-button :href="route('picking-operators.create')">Novo operador</x-button>
    </x-slot:actions>

    <section class="panel">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Matricula</th>
                    <th>Status</th>
                    <th>Separacoes</th>
                    <th>Metas</th>
                    <th class="actions-cell">Acoes</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($operators as $operator)
                    <tr>
                        <td><strong>{{ $operator->name }}</strong></td>
                        <td>{{ $operator->registration ?: '-' }}</td>
                        <td><span class="badge {{ $operator->active ? 'ok' : 'muted' }}">{{ $operator->active ? 'Ativo' : 'Inativo' }}</span></td>
                        <td>{{ $operator->product_pickings_count }}</td>
                        <td>{{ $operator->goals_count }}</td>
                        <td class="actions-cell">
                            <a href="{{ route('picking-operators.show', $operator) }}">Ver</a>
                            <a href="{{ route('picking-operators.edit', $operator) }}">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty-state">Nenhum operador cadastrado.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $operators->links() }}
    </section>
</x-layouts.app>
