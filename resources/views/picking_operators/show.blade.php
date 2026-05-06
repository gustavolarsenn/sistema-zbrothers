<x-layouts.app :title="$operator->name ?: 'Operador #'.$operator->id" eyebrow="Operador">
    <x-slot:actions>
        <x-button :href="route('picking-operators.edit', $operator)">Editar</x-button>
    </x-slot:actions>

    <section class="detail-grid">
        <div class="panel metric-card">
            <span>Status</span>
            <strong>{{ $operator->active ? 'Ativo' : 'Inativo' }}</strong>
        </div>
        <div class="panel metric-card">
            <span>Matricula</span>
            <strong>{{ $operator->registration ?: '-' }}</strong>
        </div>
    </section>

    <section class="panel">
        <h2>Metas recentes</h2>
        <table class="data-table">
            <thead><tr><th>Data</th><th>Meta</th><th>Embaladas</th><th>Progresso</th></tr></thead>
            <tbody>
                @forelse ($operator->goals as $goal)
                    <tr>
                        <td>{{ $goal->date->format('d/m/Y') }}</td>
                        <td>{{ $goal->goal }}</td>
                        <td>{{ $goal->packed_count }}</td>
                        <td>{{ $goal->progress_percentage }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty-state">Nenhuma meta recente.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
</x-layouts.app>
