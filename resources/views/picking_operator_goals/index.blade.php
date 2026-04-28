<x-layouts.app title="Metas diarias" eyebrow="Acompanhamento">
    <x-slot:actions>
        <x-button :href="route('picking-operator-goals.create')">Nova meta</x-button>
    </x-slot:actions>

    <section class="summary-strip">
        <div class="panel metric-card">
            <span>Data filtrada</span>
            <strong>{{ \Carbon\Carbon::parse($filters['date'])->format('d/m/Y') }}</strong>
        </div>
        <div class="panel metric-card">
            <span>Total embalado</span>
            <strong>{{ $dailyPackedTotal }}</strong>
        </div>
        <div class="panel metric-card">
            <span>Metas cadastradas</span>
            <strong>{{ $goals->total() }}</strong>
        </div>
    </section>

    <form class="panel filters" method="GET">
        <label>
            Data
            <input type="date" name="date" value="{{ $filters['date'] }}">
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
        <x-button type="submit">Filtrar</x-button>
    </form>

    <section class="panel">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Operador</th>
                    <th>Data</th>
                    <th>Meta</th>
                    <th>Tempo alvo</th>
                    <th>Embaladas</th>
                    <th>Progresso</th>
                    <th class="actions-cell">Acoes</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($goals as $goal)
                    <tr>
                        <td><strong>{{ $goal->operator?->name ?? '-' }}</strong></td>
                        <td>{{ $goal->date->format('d/m/Y') }}</td>
                        <td>{{ $goal->goal }}</td>
                        <td>{{ substr($goal->time_goal, 0, 5) }}</td>
                        <td>{{ $goal->packed_count }}</td>
                        <td>
                            <div class="progress-line">
                                <span style="width: {{ $goal->progress_percentage }}%"></span>
                            </div>
                            <small>{{ $goal->progress_percentage }}%</small>
                        </td>
                        <td class="actions-cell">
                            <a href="{{ route('picking-operator-goals.show', $goal) }}">Ver</a>
                            <a href="{{ route('picking-operator-goals.edit', $goal) }}">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty-state">Nenhuma meta encontrada para o filtro.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $goals->links() }}
    </section>
</x-layouts.app>
