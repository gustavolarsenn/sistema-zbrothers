<x-layouts.app title="Novo operador" eyebrow="Cadastro">
    <form class="panel form-panel" method="POST" action="{{ route('picking-operators.store') }}">
        @csrf
        @include('picking_operators._form')
        <div class="form-actions">
            <x-button :href="route('picking-operators.index')" variant="secondary">Cancelar</x-button>
            <x-button type="submit">Salvar operador</x-button>
        </div>
    </form>
</x-layouts.app>
