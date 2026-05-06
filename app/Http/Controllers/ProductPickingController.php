<?php

namespace App\Http\Controllers;

use App\Models\PickingOperator;
use App\Models\ProductPicking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductPickingController extends Controller
{
    public function index(Request $request): View
    {
        $pickings = ProductPicking::query()
            ->with('operator')
            ->when($request->filled('situacao'), fn ($query) => $query->where('situacao', $request->string('situacao')))
            ->when($request->filled('date'), fn ($query) => $query->whereDate(ProductPicking::DATE_COLUMN, $request->input('date')))
            ->when($request->filled('operator'), fn ($query) => $query->where('picking_operator_id', $request->integer('operator')))
            ->latest(ProductPicking::DATE_COLUMN)
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('product_picking.index', [
            'pickings' => $pickings,
            'operators' => $this->operators(),
            'statuses' => $this->statuses(),
            'filters' => $request->only(['situacao', 'date', 'operator']),
        ]);
    }

    public function create(): View
    {
        return view('product_picking.create', [
            'picking' => new ProductPicking([
                'qtdVolumes' => 1,
                'situacao' => ProductPicking::STATUS_PENDING,
                'dataCriacao' => now()->toDateString(),
            ]),
            'operators' => $this->operators(),
            'statuses' => $this->statuses(),
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
            'statuses' => $this->statuses(),
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
        $data = $request->validate([
            'picking_operator_id' => ['nullable', 'exists:picking_operators,id'],
            'idOrigem' => ['nullable', 'string', 'max:80'],
            'objOrigem' => ['nullable', 'string', 'max:255'],
            'situacao' => ['required', 'string', 'max:255'],
            'situacaoCheckout' => ['nullable', 'string', 'max:255'],
            'dataCriacao' => ['nullable', 'date'],
            'itens' => ['nullable', 'json'],
            'qtdVolumes' => ['nullable', 'integer', 'min:0'],
            'numero' => ['nullable', 'string', 'max:80'],
            'dataEmissao' => ['nullable', 'date'],
            'numeroPedidoEcommerce' => ['nullable', 'string', 'max:80'],
            'idFormaEnvio' => ['nullable', 'string', 'max:80'],
            'formaEnvio' => ['nullable', 'string', 'max:255'],
            'idContato' => ['nullable', 'string', 'max:80'],
            'destinatario' => ['nullable', 'string', 'max:255'],
            'situacaoOrigem' => ['nullable', 'string', 'max:80'],
            'dataSeparacao' => ['nullable', 'date'],
            'dataCheckout' => ['nullable', 'date'],
            'idOrigemVinc' => ['nullable', 'string', 'max:80'],
            'objOrigemVinc' => ['nullable', 'string', 'max:255'],
            'situacaoVenda' => ['nullable', 'string', 'max:80'],
        ]);

        if (isset($data['itens'])) {
            $data['itens'] = json_decode($data['itens'], true);
        }

        return $data;
    }

    private function operators()
    {
        return PickingOperator::query()
            ->where('active', true)
            ->orderBy('name')
            ->get();
    }

    private function statuses()
    {
        return ProductPicking::query()
            ->whereNotNull('situacao')
            ->distinct()
            ->orderBy('situacao')
            ->pluck('situacao')
            ->whenEmpty(fn ($statuses) => $statuses->merge(ProductPicking::statuses()));
    }
}
