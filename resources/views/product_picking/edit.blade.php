<x-layouts.app title="Editar separacao" eyebrow="Product picking">
    <form class="panel form-panel" method="POST" action="{{ route('product-picking.update', $picking) }}">
        @csrf
        @method('PUT')
        @include('product_picking._form')
        <div class="form-actions">
            <x-button :href="route('product-picking.index')" variant="secondary">Cancelar</x-button>
            <x-button type="submit">Atualizar separacao</x-button>
        </div>
    </form>
</x-layouts.app>
