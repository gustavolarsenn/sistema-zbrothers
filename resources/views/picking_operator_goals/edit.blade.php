<x-layouts.app title="Editar meta" eyebrow="Acompanhamento">
    <form class="panel form-panel" method="POST" action="{{ route('picking-operator-goals.update', $goal) }}">
        @csrf
        @method('PUT')
        @include('picking_operator_goals._form')
        <div class="form-actions">
            <x-button :href="route('picking-operator-goals.index')" variant="secondary">Cancelar</x-button>
            <x-button type="submit">Atualizar meta</x-button>
        </div>
    </form>
</x-layouts.app>
