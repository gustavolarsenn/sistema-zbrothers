<x-layouts.app title="Separacao #{{ $picking->id }}" eyebrow="Product picking">
    <x-slot:actions>
        <x-button :href="route('product-picking.edit', $picking)">Editar</x-button>
    </x-slot:actions>

    <section class="panel detail-list">
        <div><span>Data</span><strong>{{ $picking->picking_date->format('d/m/Y') }}</strong></div>
        <div><span>Operador</span><strong>{{ $picking->operator?->name ?? '-' }}</strong></div>
        <div><span>Pedido</span><strong>{{ $picking->order_number ?: '-' }}</strong></div>
        <div><span>Produto</span><strong>{{ $picking->product_name ?: '-' }}</strong></div>
        <div><span>Codigo</span><strong>{{ $picking->product_code ?: '-' }}</strong></div>
        <div><span>Quantidade</span><strong>{{ $picking->quantity }}</strong></div>
        <div><span>Situacao</span><strong>{{ $picking->status }}</strong></div>
        <div class="span-2"><span>Observacoes</span><strong>{{ $picking->notes ?: '-' }}</strong></div>
    </section>
</x-layouts.app>
