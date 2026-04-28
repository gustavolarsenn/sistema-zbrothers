<x-layouts.app title="Separacoes" eyebrow="Product picking">
    <x-slot:actions>
        <x-button :href="route('product-picking.create')">Nova separacao</x-button>
    </x-slot:actions>

    <form class="panel filters" method="GET">
        <label>
            Data
            <input type="date" name="date" value="{{ $filters['date'] ?? '' }}">
        </label>
        <label>
            Operador
            <select name="operator">
                <option value="">Todos</option>
                @foreach ($operators as $operator)
                    <option value="{{ $operator->id }}" @selected((string) ($filters['operator'] ?? '') === (string) $operator->id)>{{ $operator->name }}</option>
                @endforeach
            </select>
        </label>
        <label>
            Situacao
            <select name="status">
                <option value="">Todas</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </label>
        <x-button type="submit">Filtrar</x-button>
    </form>

    <section class="panel">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Operador</th>
                    <th>Pedido</th>
                    <th>Produto</th>
                    <th>Qtd.</th>
                    <th>Situacao</th>
                    <th class="actions-cell">Acoes</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pickings as $picking)
                    <tr>
                        <td>{{ $picking->picking_date->format('d/m/Y') }}</td>
                        <td>{{ $picking->operator?->name ?? '-' }}</td>
                        <td>{{ $picking->order_number ?: '-' }}</td>
                        <td>{{ $picking->product_name ?: $picking->product_code ?: '-' }}</td>
                        <td>{{ $picking->quantity }}</td>
                        <td><span class="badge {{ $picking->status === 'Embalada' ? 'ok' : 'info' }}">{{ $picking->status }}</span></td>
                        <td class="actions-cell">
                            <a href="{{ route('product-picking.show', $picking) }}">Ver</a>
                            <a href="{{ route('product-picking.edit', $picking) }}">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty-state">Nenhuma separacao encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $pickings->links() }}
    </section>
</x-layouts.app>
