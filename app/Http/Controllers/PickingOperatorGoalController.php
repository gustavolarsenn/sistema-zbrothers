<?php

namespace App\Http\Controllers;

use App\Models\PickingOperator;
use App\Models\PickingOperatorGoal;
use App\Models\ProductPicking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PickingOperatorGoalController extends Controller
{
    public function index(Request $request): View
    {
        $date = $request->input('date', now()->toDateString());

        $goals = PickingOperatorGoal::query()
            ->with('operator')
            ->when($request->filled('operator'), fn ($query) => $query->where('picking_operator_id', $request->integer('operator')))
            ->whereDate('date', $date)
            ->orderBy('date', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('picking_operator_goals.index', [
            'goals' => $goals,
            'operators' => $this->operators(),
            'filters' => [
                'date' => $date,
                'operator' => $request->input('operator'),
            ],
            'dailyPackedTotal' => ProductPicking::query()
                ->whereDate('picking_date', $date)
                ->where('status', ProductPicking::STATUS_PACKED)
                ->count(),
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
}
