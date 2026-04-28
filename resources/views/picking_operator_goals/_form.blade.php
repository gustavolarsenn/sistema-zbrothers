@php
    $goalDate = $goal->date instanceof \Carbon\CarbonInterface ? $goal->date->format('Y-m-d') : $goal->date;
    $timeGoal = $goal->time_goal ? substr($goal->time_goal, 0, 5) : null;
@endphp

<div class="form-grid">
    <label>
        Operador
        <select name="picking_operator_id" required>
            <option value="">Selecione</option>
            @foreach ($operators as $operator)
                <option value="{{ $operator->id }}" @selected((string) old('picking_operator_id', $goal->picking_operator_id) === (string) $operator->id)>
                    {{ $operator->name }}
                </option>
            @endforeach
        </select>
    </label>

    <label>
        Data da meta
        <input type="date" name="date" value="{{ old('date', $goalDate) }}" required>
    </label>

    <label>
        Meta de embalagens
        <input type="number" min="1" name="goal" value="{{ old('goal', $goal->goal) }}" required>
    </label>

    <label>
        Tempo alvo
        <input type="time" name="time_goal" value="{{ old('time_goal', $timeGoal) }}" required>
    </label>
</div>
