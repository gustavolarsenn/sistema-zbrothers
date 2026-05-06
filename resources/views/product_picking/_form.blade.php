@php
    $dateValue = fn ($value) => $value instanceof \Carbon\CarbonInterface ? $value->format('Y-m-d') : $value;
    $itemsValue = old('itens', is_array($picking->itens) ? json_encode($picking->itens, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $picking->itens);
@endphp

<div class="form-grid">
    <label>
        Operador
        <select name="picking_operator_id">
            <option value="">Sem operador</option>
            @foreach ($operators as $operator)
                <option value="{{ $operator->id }}" @selected((string) old('picking_operator_id', $picking->picking_operator_id) === (string) $operator->id)>
                    {{ $operator->name ?: 'Operador #'.$operator->id }}
                </option>
            @endforeach
        </select>
    </label>

    <label>
        Situacao
        <input list="situacoes" name="situacao" value="{{ old('situacao', $picking->situacao) }}" required maxlength="255">
        <datalist id="situacoes">
            @foreach ($statuses as $status)
                <option value="{{ $status }}"></option>
            @endforeach
        </datalist>
    </label>

    <label>
        Numero
        <input name="numero" value="{{ old('numero', $picking->numero) }}" maxlength="80">
    </label>

    <label>
        Pedido ecommerce
        <input name="numeroPedidoEcommerce" value="{{ old('numeroPedidoEcommerce', $picking->numeroPedidoEcommerce) }}" maxlength="80">
    </label>

    <label>
        Data criacao
        <input type="date" name="dataCriacao" value="{{ old('dataCriacao', $dateValue($picking->dataCriacao)) }}">
    </label>

    <label>
        Data emissao
        <input type="date" name="dataEmissao" value="{{ old('dataEmissao', $dateValue($picking->dataEmissao)) }}">
    </label>

    <label>
        Data separacao
        <input type="date" name="dataSeparacao" value="{{ old('dataSeparacao', $dateValue($picking->dataSeparacao)) }}">
    </label>

    <label>
        Data checkout
        <input type="date" name="dataCheckout" value="{{ old('dataCheckout', $dateValue($picking->dataCheckout)) }}">
    </label>

    <label>
        Qtd. volumes
        <input type="number" min="0" name="qtdVolumes" value="{{ old('qtdVolumes', $picking->qtdVolumes) }}">
    </label>

    <label>
        Situacao checkout
        <input name="situacaoCheckout" value="{{ old('situacaoCheckout', $picking->situacaoCheckout) }}" maxlength="255">
    </label>

    <label>
        ID origem
        <input name="idOrigem" value="{{ old('idOrigem', $picking->idOrigem) }}" maxlength="80">
    </label>

    <label>
        Objeto origem
        <input name="objOrigem" value="{{ old('objOrigem', $picking->objOrigem) }}" maxlength="255">
    </label>

    <label>
        Forma envio
        <input name="formaEnvio" value="{{ old('formaEnvio', $picking->formaEnvio) }}" maxlength="255">
    </label>

    <label>
        ID forma envio
        <input name="idFormaEnvio" value="{{ old('idFormaEnvio', $picking->idFormaEnvio) }}" maxlength="80">
    </label>

    <label>
        Destinatario
        <input name="destinatario" value="{{ old('destinatario', $picking->destinatario) }}" maxlength="255">
    </label>

    <label>
        ID contato
        <input name="idContato" value="{{ old('idContato', $picking->idContato) }}" maxlength="80">
    </label>

    <label>
        Situacao origem
        <input name="situacaoOrigem" value="{{ old('situacaoOrigem', $picking->situacaoOrigem) }}" maxlength="80">
    </label>

    <label>
        Situacao venda
        <input name="situacaoVenda" value="{{ old('situacaoVenda', $picking->situacaoVenda) }}" maxlength="80">
    </label>

    <label>
        ID origem vinc.
        <input name="idOrigemVinc" value="{{ old('idOrigemVinc', $picking->idOrigemVinc) }}" maxlength="80">
    </label>

    <label>
        Objeto origem vinc.
        <input name="objOrigemVinc" value="{{ old('objOrigemVinc', $picking->objOrigemVinc) }}" maxlength="255">
    </label>

    <label class="span-2">
        Itens JSON
        <textarea name="itens" rows="6">{{ $itemsValue }}</textarea>
        @error('itens') <span class="field-error">{{ $message }}</span> @enderror
    </label>
</div>
