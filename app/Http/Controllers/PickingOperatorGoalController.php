<?php

namespace App\Http\Controllers;

use App\Models\PickingOperator;
use App\Models\PickingOperatorGoal;
use App\Models\ProductPicking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PickingOperatorGoalController extends Controller
{
    public function index(Request $request): View
    {
        $date = $request->input('date', now()->toDateString());
        $operatorId = $request->input('operator');

        $goals = PickingOperatorGoal::query()
            ->with('operator')
            ->when($operatorId, fn ($query) => $query->where('picking_operator_id', $operatorId))
            ->whereDate('date', $date)
            ->orderBy('date', 'desc')
            ->paginate(12)
            ->withQueryString();

        $dashboard = $this->dashboardData($date, $operatorId);

        return view('picking_operator_goals.index', [
            'goals' => $goals,
            'operators' => $this->operators(),
            'filters' => [
                'date' => $date,
                'operator' => $operatorId,
            ],
            'dashboard' => $dashboard,
            'dailyPackedTotal' => $dashboard['packedTotal'],
        ]);
    }

    public function create(): View
    {
        return view('picking_operator_goals.create', [
            'goal' => new PickingOperatorGoal([
                'date' => now()->toDateString(),
                'time_goal' => '08:00',
            ]),
            'operators' => $this->operators(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        PickingOperatorGoal::create($this->validatedData($request));

        return redirect()
            ->route('picking-operator-goals.index')
            ->with('success', 'Meta cadastrada com sucesso.');
    }

    public function show(PickingOperatorGoal $pickingOperatorGoal): View
    {
        return view('picking_operator_goals.show', [
            'goal' => $pickingOperatorGoal->load('operator'),
        ]);
    }

    public function edit(PickingOperatorGoal $pickingOperatorGoal): View
    {
        return view('picking_operator_goals.edit', [
            'goal' => $pickingOperatorGoal,
            'operators' => $this->operators(),
        ]);
    }

    public function update(Request $request, PickingOperatorGoal $pickingOperatorGoal): RedirectResponse
    {
        $pickingOperatorGoal->update($this->validatedData($request, $pickingOperatorGoal));

        return redirect()
            ->route('picking-operator-goals.index')
            ->with('success', 'Meta atualizada com sucesso.');
    }

    public function destroy(PickingOperatorGoal $pickingOperatorGoal): RedirectResponse
    {
        $pickingOperatorGoal->delete();

        return redirect()
            ->route('picking-operator-goals.index')
            ->with('success', 'Meta removida com sucesso.');
    }

    private function validatedData(Request $request, ?PickingOperatorGoal $goal = null): array
    {
        return $request->validate([
            'picking_operator_id' => ['required', 'exists:picking_operators,id'],
            'date' => [
                'required',
                'date',
                Rule::unique('picking_operator_goals', 'date')
                    ->where('picking_operator_id', $request->input('picking_operator_id'))
                    ->ignore($goal?->id),
            ],
            'goal' => ['required', 'integer', 'min:1'],
            'time_goal' => ['required', 'date_format:H:i'],
        ]);
    }

    private function operators()
    {
        return PickingOperator::query()
            ->where('active', true)
            ->orderBy('name')
            ->get();
    }

    private function dashboardData(string $date, mixed $operatorId = null): array
    {
        $operatorMap = PickingOperator::query()
            ->orderBy('name')
            ->pluck('name', 'id');

        $baseQuery = ProductPicking::query()
            ->whereDate(ProductPicking::DATE_COLUMN, $date)
            ->when($operatorId, fn ($query) => $query->where('picking_operator_id', $operatorId));

        $operatorStatusRows = (clone $baseQuery)
            ->select('picking_operator_id', 'situacao', DB::raw('count(*) as total'))
            ->whereNotNull('picking_operator_id')
            ->whereNotNull('situacao')
            ->groupBy('picking_operator_id', 'situacao')
            ->get();

        $statusTotals = (clone $baseQuery)
            ->select('situacao', DB::raw('count(*) as total'))
            ->whereNotNull('situacao')
            ->groupBy('situacao')
            ->orderByDesc('total')
            ->get();

        $statuses = $statusTotals
            ->pluck('situacao')
            ->merge($operatorStatusRows->pluck('situacao'))
            ->unique()
            ->sort()
            ->values();

        if ($statuses->isEmpty()) {
            $statuses = collect(ProductPicking::statuses());
        }

        $operatorCharts = $this->operatorCharts($operatorStatusRows, $operatorMap, $statuses);
        $totalPickings = (clone $baseQuery)->count();
        $packedTotal = (clone $baseQuery)->where('situacao', ProductPicking::STATUS_PACKED)->count();

        return [
            'statuses' => $statuses,
            'operatorCharts' => $operatorCharts,
            'statusTotals' => $this->statusTotals($statusTotals, $totalPickings),
            'maxOperatorTotal' => max(1, $operatorCharts->max('total') ?? 1),
            'totalPickings' => $totalPickings,
            'packedTotal' => $packedTotal,
            'activePickers' => $operatorCharts->count(),
            'lastUpdatedAt' => now()->format('H:i'),
        ];
    }

    private function operatorCharts(Collection $rows, Collection $operatorMap, Collection $statuses): Collection
    {
        return $rows
            ->groupBy('picking_operator_id')
            ->map(function (Collection $operatorRows, int|string $operatorId) use ($operatorMap, $statuses) {
                $counts = $operatorRows->pluck('total', 'situacao');
                $total = (int) $operatorRows->sum('total');

                return [
                    'id' => $operatorId,
                    'name' => $operatorMap[$operatorId] ?? 'Operador #'.$operatorId,
                    'total' => $total,
                    'packed' => (int) ($counts[ProductPicking::STATUS_PACKED] ?? 0),
                    'segments' => $statuses->map(fn (string $status) => [
                        'status' => $status,
                        'total' => (int) ($counts[$status] ?? 0),
                        'percentage' => $total > 0 ? round(((int) ($counts[$status] ?? 0) / $total) * 100, 1) : 0,
                    ])->values(),
                ];
            })
            ->sortByDesc('packed')
            ->values();
    }

    private function statusTotals(Collection $rows, int $totalPickings): Collection
    {
        return $rows->map(fn ($row) => [
            'status' => $row->situacao,
            'total' => (int) $row->total,
            'percentage' => $totalPickings > 0 ? round(((int) $row->total / $totalPickings) * 100, 1) : 0,
        ]);
    }
}
