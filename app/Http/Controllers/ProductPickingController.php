<?php

namespace App\Http\Controllers;

use App\Models\PickingOperator;
use App\Models\ProductPicking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductPickingController extends Controller
{
    public function index(Request $request): View
    {
        $pickings = ProductPicking::query()
            ->with('operator')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('date'), fn ($query) => $query->whereDate('picking_date', $request->date('date')))
            ->when($request->filled('operator'), fn ($query) => $query->where('picking_operator_id', $request->integer('operator')))
            ->latest('picking_date')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('product_picking.index', [
            'pickings' => $pickings,
            'operators' => $this->operators(),
            'statuses' => ProductPicking::statuses(),
            'filters' => $request->only(['status', 'date', 'operator']),
        ]);
    }

    public function create(): View
    {
        return view('product_picking.create', [
            'picking' => new ProductPicking([
                'quantity' => 1,
                'status' => ProductPicking::STATUS_PENDING,
                'picking_date' => now()->toDateString(),
            ]),
            'operators' => $this->operators(),
            'statuses' => ProductPicking::statuses(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        ProductPicking::create($this->validatedData($request));

        return redirect()
            ->route('product-picking.index')
            ->with('success', 'Separação cadastrada com sucesso.');
    }

    public function show(ProductPicking $productPicking): View
    {
        return view('product_picking.show', [
            'picking' => $productPicking->load('operator'),
        ]);
    }

    public function edit(ProductPicking $productPicking): View
    {
        return view('product_picking.edit', [
            'picking' => $productPicking,
            'operators' => $this->operators(),
            'statuses' => ProductPicking::statuses(),
        ]);
    }

    public function update(Request $request, ProductPicking $productPicking): RedirectResponse
    {
        $productPicking->update($this->validatedData($request));

        return redirect()
            ->route('product-picking.index')
            ->with('success', 'Separação atualizada com sucesso.');
    }

    public function destroy(ProductPicking $productPicking): RedirectResponse
    {
        $productPicking->delete();

        return redirect()
            ->route('product-picking.index')
            ->with('success', 'Separação removida com sucesso.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'picking_operator_id' => ['nullable', 'exists:picking_operators,id'],
            'order_number' => ['nullable', 'string', 'max:80'],
            'product_code' => ['nullable', 'string', 'max:80'],
            'product_name' => ['nullable', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in(ProductPicking::statuses())],
            'picking_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
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
