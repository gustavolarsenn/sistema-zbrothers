<x-layouts.app title="Editar operador" eyebrow="Cadastro">
    <form class="panel form-panel" method="POST" action="{{ route('picking-operators.update', $operator) }}">
        @csrf
        @method('PUT')
        @include('picking_operators._form')
        <div class="form-actions">
            <x-button :href="route('picking-operators.index')" variant="secondary">Cancelar</x-button>
            <x-button type="submit">Atualizar operador</x-button>
        </div>
    </form>
</x-layouts.app>
