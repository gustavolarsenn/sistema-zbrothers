<x-layouts.app title="Dashboard de picking" eyebrow="Atualiza a cada 10 minutos">
    <x-slot:actions>
        <span class="refresh-pill">Ultima atualizacao {{ $dashboard['lastUpdatedAt'] }}</span>
        <x-button :href="route('picking-operator-goals.create')" variant="secondary">Nova meta</x-button>
    </x-slot:actions>

    <section class="summary-strip tv-summary">
        <div class="panel metric-card">
            <span>Data analisada</span>
            <strong>{{ \Carbon\Carbon::parse($filters['date'])->format('d/m/Y') }}</strong>
        </div>
        <div class="panel metric-card">
            <span>Total de separacoes</span>
            <strong>{{ $dashboard['totalPickings'] }}</strong>
        </div>
        <div class="panel metric-card">
            <span>Embaladas</span>
            <strong>{{ $dashboard['packedTotal'] }}</strong>
        </div>
        <div class="panel metric-card">
            <span>Pickers com resultado</span>
            <strong>{{ $dashboard['activePickers'] }}</strong>
        </div>
    </section>

    <form class="panel filters tv-filters" method="GET">
        <label>
            Data
            <input type="date" name="date" value="{{ $filters['date'] }}">
        </label>
        <label>
            Operador
            <select name="operator">
                <option value="">Todos</option>
                @foreach ($operators as $operator)
                    <option value="{{ $operator->id }}" @selected((string) ($filters['operator'] ?? '') === (string) $operator->id)>
                        {{ $operator->name ?: 'Operador #'.$operator->id }}
                    </option>
                @endforeach
            </select>
        </label>
        <x-button type="submit">Filtrar</x-button>
    </form>

    <section class="tv-grid">
        <div class="panel chart-panel picker-chart">
            <div class="panel-title-row">
                <div>
                    <h2>Comparacao entre pickers</h2>
                    <p>Resultado agrupado por situacao em {{ \Carbon\Carbon::parse($filters['date'])->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="chart-legend">
                @foreach ($dashboard['statuses'] as $status)
                    <span><i class="status-color-{{ $loop->index % 8 }}"></i>{{ $status }}</span>
                @endforeach
            </div>

            <div class="operator-bars">
                @forelse ($dashboard['operatorCharts'] as $operatorChart)
                    <article class="operator-row">
                        <div class="operator-meta">
                            <strong>{{ $operatorChart['name'] }}</strong>
                            <span>{{ $operatorChart['total'] }} separacoes</span>
                        </div>
                        <div class="stacked-bar" aria-label="Resultado de {{ $operatorChart['name'] }}">
                            @foreach ($operatorChart['segments'] as $segment)
                                @if ($segment['total'] > 0)
                                    <span
                                        class="status-bg-{{ $loop->index % 8 }}"
                                        style="width: {{ max(4, $segment['percentage']) }}%"
                                        title="{{ $segment['status'] }}: {{ $segment['total'] }}"
                                    >
                                        {{ $segment['total'] }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                        <strong class="operator-packed">{{ $operatorChart['packed'] }} embaladas</strong>
                    </article>
                @empty
                    <div class="empty-state">Nenhuma separacao encontrada para esta data.</div>
                @endforelse
            </div>
        </div>

        <div class="panel chart-panel">
            <h2>Situacoes do dia</h2>
            <div class="status-chart">
                @forelse ($dashboard['statusTotals'] as $statusTotal)
                    <article>
                        <div>
                            <strong>{{ $statusTotal['status'] }}</strong>
                            <span>{{ $statusTotal['total'] }} registros</span>
                        </div>
                        <div class="horizontal-meter">
                            <span style="width: {{ $statusTotal['percentage'] }}%"></span>
                        </div>
                        <b>{{ $statusTotal['percentage'] }}%</b>
                    </article>
                @empty
                    <div class="empty-state">Sem situacoes para exibir.</div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="tv-grid lower">
        <div class="panel chart-panel">
            <h2>Ranking de embaladas</h2>
            <div class="ranking-list">
                @forelse ($dashboard['operatorCharts']->sortByDesc('packed')->take(8)->values() as $operatorChart)
                    <article>
                        <span>{{ $loop->iteration }}</span>
                        <strong>{{ $operatorChart['name'] }}</strong>
                        <b>{{ $operatorChart['packed'] }}</b>
                    </article>
                @empty
                    <div class="empty-state">Sem ranking para esta data.</div>
                @endforelse
            </div>
        </div>

        <div class="panel">
            <div class="panel-title-row">
                <div>
                    <h2>Metas do dia</h2>
                    <p>A meta considera product_picking com situacao Embalada na dataCriacao.</p>
                </div>
            </div>
            <table class="data-table compact-table">
                <thead>
                    <tr>
                        <th>Operador</th>
                        <th>Meta</th>
                        <th>Embaladas</th>
                        <th>Progresso</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($goals as $goal)
                        <tr>
                            <td><strong>{{ $goal->operator?->name ?? '-' }}</strong></td>
                            <td>{{ $goal->goal }}</td>
                            <td>{{ $goal->packed_count }}</td>
                            <td>
                                <div class="progress-line">
                                    <span style="width: {{ $goal->progress_percentage }}%"></span>
                                </div>
                                <small>{{ $goal->progress_percentage }}%</small>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="empty-state">Nenhuma meta cadastrada para o dia.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $goals->links() }}
        </div>
    </section>

    <script>
        window.setTimeout(function () {
            window.location.reload();
        }, 600000);
    </script>
</x-layouts.app>
