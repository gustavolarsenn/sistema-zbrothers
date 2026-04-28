<x-layouts.app title="Nova separacao" eyebrow="Product picking">
    <form class="panel form-panel" method="POST" action="{{ route('product-picking.store') }}">
        @csrf
        @include('product_picking._form')
        <div class="form-actions">
            <x-button :href="route('product-picking.index')" variant="secondary">Cancelar</x-button>
            <x-button type="submit">Salvar separacao</x-button>
        </div>
    </form>
</x-layouts.app>
