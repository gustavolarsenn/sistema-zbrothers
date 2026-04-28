@php
    $pickingDate = $picking->picking_date instanceof \Carbon\CarbonInterface
        ? $picking->picking_date->format('Y-m-d')
        : $picking->picking_date;
@endphp

<div class="form-grid">
    <label>
        Operador
        <select name="picking_operator_id">
            <option value="">Sem operador</option>
            @foreach ($operators as $operator)
                <option value="{{ $operator->id }}" @selected((string) old('picking_operator_id', $picking->picking_operator_id) === (string) $operator->id)>
                    {{ $operator->name }}
                </option>
            @endforeach
        </select>
    </label>

    <label>
        Data
        <input type="date" name="picking_date" value="{{ old('picking_date', $pickingDate) }}" required>
    </label>

    <label>
        Pedido
        <input name="order_number" value="{{ old('order_number', $picking->order_number) }}" maxlength="80">
    </label>

    <label>
        Codigo do produto
        <input name="product_code" value="{{ old('product_code', $picking->product_code) }}" maxlength="80">
    </label>

    <label class="span-2">
        Produto
        <input name="product_name" value="{{ old('product_name', $picking->product_name) }}" maxlength="255">
    </label>

    <label>
        Quantidade
        <input type="number" min="1" name="quantity" value="{{ old('quantity', $picking->quantity ?? 1) }}" required>
    </label>

    <label>
        Situacao
        <select name="status" required>
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $picking->status) === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </label>

    <label class="span-2">
        Observacoes
        <textarea name="notes" rows="4">{{ old('notes', $picking->notes) }}</textarea>
    </label>
</div>
