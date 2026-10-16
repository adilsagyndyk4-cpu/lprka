{{-- Общий компонент формы для create и edit --}}
<div class="row g-3">
    <div class="col-md-6">
        <label for="code" class="form-label text-secondary small text-uppercase fw-semibold">
            <i class="bi bi-upc-scan me-1"></i>Код (формат: AAA-999)
        </label>
        <input type="text" id="code" name="code"
            class="form-control @error('code') is-invalid @enderror"
            value="{{ old('code', $computer->code ?? '') }}"
            placeholder="VIP-101" maxlength="7" required>
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="inventory_number" class="form-label text-secondary small text-uppercase fw-semibold">
            <i class="bi bi-tag me-1"></i>Инвентарный номер
        </label>
        <input type="text" id="inventory_number" name="inventory_number"
            class="form-control @error('inventory_number') is-invalid @enderror"
            value="{{ old('inventory_number', $computer->inventory_number ?? '') }}"
            placeholder="INV-00101" minlength="4" maxlength="20" required>
        @error('inventory_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="zone_id" class="form-label text-secondary small text-uppercase fw-semibold">
            <i class="bi bi-geo-alt me-1"></i>Зона клуба
        </label>
        <select id="zone_id" name="zone_id"
            class="form-select @error('zone_id') is-invalid @enderror" required>
            <option value="">— Выберите зону —</option>
            @foreach ($zones as $zone)
                <option value="{{ $zone->id }}" @selected(old('zone_id', $computer->zone_id ?? '') == $zone->id)>
                    {{ $zone->name }} ({{ $zone->floor }} эт.)
                </option>
            @endforeach
        </select>
        @error('zone_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="cpu" class="form-label text-secondary small text-uppercase fw-semibold">
            <i class="bi bi-cpu me-1"></i>Процессор (CPU)
        </label>
        <input type="text" id="cpu" name="cpu"
            class="form-control @error('cpu') is-invalid @enderror"
            value="{{ old('cpu', $computer->cpu ?? '') }}"
            placeholder="Intel Core i9-14900KF" minlength="4" maxlength="187" required>
        @error('cpu')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="ram_gb" class="form-label text-secondary small text-uppercase fw-semibold">
            <i class="bi bi-memory me-1"></i>ОЗУ (ГБ)
        </label>
        <input type="number" id="ram_gb" name="ram_gb"
            class="form-control @error('ram_gb') is-invalid @enderror"
            value="{{ old('ram_gb', $computer->ram_gb ?? '') }}"
            min="1" max="512" required>
        @error('ram_gb')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="hour_price" class="form-label text-secondary small text-uppercase fw-semibold">
            <i class="bi bi-cash-stack me-1"></i>Цена за час (₽)
        </label>
        <input type="number" id="hour_price" name="hour_price" step="0.01"
            class="form-control @error('hour_price') is-invalid @enderror"
            value="{{ old('hour_price', $computer->hour_price ?? '') }}"
            min="0" max="999999.99" required>
        @error('hour_price')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check form-switch mb-3">
            <input type="hidden" name="is_vip" value="0">
            <input type="checkbox" id="is_vip" name="is_vip" value="1"
                class="form-check-input" role="switch"
                @checked(old('is_vip', $computer->is_vip ?? false))>
            <label class="form-check-label text-light" for="is_vip">
                <i class="bi bi-star-fill text-warning me-1"></i>VIP место
            </label>
        </div>
    </div>
</div>
