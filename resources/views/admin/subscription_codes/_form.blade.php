@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="code" class="form-label">{{ __('Code') }}</label>
        <input type="text" id="code" name="code" value="{{ old('code', $subscriptionCode->code ?? '') }}" class="form-control @error('code') is-invalid @enderror" required>
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3 mb-3">
        <label for="duration_days" class="form-label">{{ __('Duration (days)') }}</label>
        <input type="number" min="1" id="duration_days" name="duration_days" value="{{ old('duration_days', $subscriptionCode->duration_days ?? 30) }}" class="form-control @error('duration_days') is-invalid @enderror" required>
        @error('duration_days')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3 mb-3">
        <label for="max_uses" class="form-label">{{ __('Max Uses') }}</label>
        <input type="number" min="1" id="max_uses" name="max_uses" value="{{ old('max_uses', $subscriptionCode->max_uses ?? 1) }}" class="form-control @error('max_uses') is-invalid @enderror" required>
        @error('max_uses')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="starts_at" class="form-label">{{ __('Starts At (optional)') }}</label>
        <input type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at', optional($subscriptionCode->starts_at ?? null)->format('Y-m-d\TH:i')) }}" class="form-control @error('starts_at') is-invalid @enderror">
        @error('starts_at')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="ends_at" class="form-label">{{ __('Ends At (optional)') }}</label>
        <input type="datetime-local" id="ends_at" name="ends_at" value="{{ old('ends_at', optional($subscriptionCode->ends_at ?? null)->format('Y-m-d\TH:i')) }}" class="form-control @error('ends_at') is-invalid @enderror">
        @error('ends_at')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="form-check mb-4">
    <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" {{ old('is_active', ($subscriptionCode->is_active ?? true)) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">
        {{ __('Active') }}
    </label>
</div>
<div class="d-flex justify-content-end">
    <a href="{{ route('admin.subscription-codes.index') }}" class="btn btn-light mr-2">{{ __('Cancel') }}</a>
    <button type="submit" class="btn btn-primary">{{ $submitLabel ?? __('Save') }}</button>
</div>
