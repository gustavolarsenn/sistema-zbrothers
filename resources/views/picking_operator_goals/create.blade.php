<x-layouts.app title="Nova meta" eyebrow="Acompanhamento">
    <form class="panel form-panel" method="POST" action="{{ route('picking-operator-goals.store') }}">
        @csrf
        @include('picking_operator_goals._form')
        <div class="form-actions">
            <x-button :href="route('picking-operator-goals.index')" variant="secondary">Cancelar</x-button>
            <x-button type="submit">Salvar meta</x-button>
        </div>
    </form>
</x-layouts.app>
