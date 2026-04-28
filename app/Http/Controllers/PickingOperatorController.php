<?php

namespace App\Http\Controllers;

use App\Models\PickingOperator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PickingOperatorController extends Controller
{
    public function index(): View
    {
        $operators = PickingOperator::query()
            ->withCount([
                'productPickings',
                'goals',
            ])
            ->latest()
            ->paginate(10);

        return view('picking_operators.index', compact('operators'));
    }

    public function create(): View
    {
        return view('picking_operators.create', [
            'operator' => new PickingOperator(['active' => true]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        PickingOperator::create($this->validatedData($request));

        return redirect()
            ->route('picking-operators.index')
            ->with('success', 'Operador cadastrado com sucesso.');
    }

    public function show(PickingOperator $pickingOperator): View
    {
        $pickingOperator->load(['goals' => fn ($query) => $query->latest('date')->limit(8)]);

        return view('picking_operators.show', [
            'operator' => $pickingOperator,
        ]);
    }

    public function edit(PickingOperator $pickingOperator): View
    {
        return view('picking_operators.edit', [
            'operator' => $pickingOperator,
        ]);
    }

    public function update(Request $request, PickingOperator $pickingOperator): RedirectResponse
    {
        $pickingOperator->update($this->validatedData($request, $pickingOperator));

        return redirect()
            ->route('picking-operators.index')
            ->with('success', 'Operador atualizado com sucesso.');
    }

    public function destroy(PickingOperator $pickingOperator): RedirectResponse
    {
        $pickingOperator->delete();

        return redirect()
            ->route('picking-operators.index')
            ->with('success', 'Operador removido com sucesso.');
    }

    private function validatedData(Request $request, ?PickingOperator $operator = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'registration' => ['nullable', 'string', 'max:80', 'unique:picking_operators,registration,'.($operator?->id ?? 'NULL')],
            'active' => ['nullable', 'boolean'],
        ]) + ['active' => false];
    }
}
