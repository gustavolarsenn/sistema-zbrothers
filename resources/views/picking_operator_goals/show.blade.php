<x-layouts.app title="Meta de {{ $goal->operator?->name }}" eyebrow="Acompanhamento">
    <x-slot:actions>
        <x-button :href="route('picking-operator-goals.edit', $goal)">Editar</x-button>
    </x-slot:actions>

    <section class="detail-grid">
        <div class="panel metric-card">
            <span>Data</span>
            <strong>{{ $goal->date->format('d/m/Y') }}</strong>
        </div>
        <div class="panel metric-card">
            <span>Meta</span>
            <strong>{{ $goal->goal }}</strong>
        </div>
        <div class="panel metric-card">
            <span>Embaladas</span>
            <strong>{{ $goal->packed_count }}</strong>
        </div>
        <div class="panel metric-card">
            <span>Progresso</span>
            <strong>{{ $goal->progress_percentage }}%</strong>
        </div>
    </section>

    <section class="panel">
        <div class="progress-line large">
            <span style="width: {{ $goal->progress_percentage }}%"></span>
        </div>
        <p class="muted-text">
            A contagem considera product_picking do operador em {{ $goal->date->format('d/m/Y') }} com dataCriacao no dia e situacao Embalada.
        </p>
    </section>
</x-layouts.app>
