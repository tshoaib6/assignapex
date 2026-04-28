@php($editing = isset($pixel))
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Grid ID</label>
        <input name="grid_id" class="form-control" required maxlength="100"
               value="{{ old('grid_id', $pixel->grid_id ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Region</label>
        <input name="region" class="form-control" required maxlength="150"
               value="{{ old('region', $pixel->region ?? '') }}">
    </div>
</div>
