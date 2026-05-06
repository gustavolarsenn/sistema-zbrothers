<x-layouts.app title="Separacao #{{ $picking->id }}" eyebrow="Product picking">
    <x-slot:actions>
        <x-button :href="route('product-picking.edit', $picking)">Editar</x-button>
    </x-slot:actions>

    <section class="panel detail-list">
        <div><span>Data criacao</span><strong>{{ $picking->dataCriacao?->format('d/m/Y') ?? '-' }}</strong></div>
        <div><span>Operador</span><strong>{{ $picking->operator?->name ?? '-' }}</strong></div>
        <div><span>Numero</span><strong>{{ $picking->numero ?: '-' }}</strong></div>
        <div><span>Pedido ecommerce</span><strong>{{ $picking->numeroPedidoEcommerce ?: '-' }}</strong></div>
        <div><span>Destinatario</span><strong>{{ $picking->destinatario ?: '-' }}</strong></div>
        <div><span>Situacao</span><strong>{{ $picking->situacao }}</strong></div>
        <div><span>Volumes</span><strong>{{ $picking->qtdVolumes ?? '-' }}</strong></div>
        <div><span>Forma envio</span><strong>{{ $picking->formaEnvio ?: '-' }}</strong></div>
        <div><span>Data emissao</span><strong>{{ $picking->dataEmissao?->format('d/m/Y') ?? '-' }}</strong></div>
        <div><span>Data separacao</span><strong>{{ $picking->dataSeparacao?->format('d/m/Y') ?? '-' }}</strong></div>
        <div><span>Data checkout</span><strong>{{ $picking->dataCheckout?->format('d/m/Y') ?? '-' }}</strong></div>
        <div><span>Situacao checkout</span><strong>{{ $picking->situacaoCheckout ?: '-' }}</strong></div>
        <div><span>ID origem</span><strong>{{ $picking->idOrigem ?: '-' }}</strong></div>
        <div><span>Objeto origem</span><strong>{{ $picking->objOrigem ?: '-' }}</strong></div>
        <div><span>Situacao venda</span><strong>{{ $picking->situacaoVenda ?: '-' }}</strong></div>
        <div class="span-2"><span>Itens</span><strong><pre>{{ $picking->itens ? json_encode($picking->itens, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '-' }}</pre></strong></div>
    </section>
</x-layouts.app>
