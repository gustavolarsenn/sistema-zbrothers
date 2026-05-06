<div class="form-grid">
    <label>
        Nome
        <input name="name" value="{{ old('name', $operator->name) }}" maxlength="255">
        @error('name') <span class="field-error">{{ $message }}</span> @enderror
    </label>

    <label>
        Matricula
        <input name="registration" value="{{ old('registration', $operator->registration) }}" maxlength="80">
        @error('registration') <span class="field-error">{{ $message }}</span> @enderror
    </label>

    <label class="toggle-line">
        <input type="hidden" name="active" value="0">
        <input type="checkbox" name="active" value="1" @checked(old('active', $operator->active))>
        Operador ativo
    </label>
</div>
