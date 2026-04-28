@php
    use Illuminate\Support\Facades\URL;

    $files = $files ?? [];
    if (!is_array($files) && !empty($files)) {
        $files = array_filter(preg_split('/\s*,\s*/', (string) $files));
    }

    // helper to build absolute URL
    $makeUrl = function (string $path): string {
        $path = ltrim($path, '/');

        // already an absolute URL? keep it
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        // if caller already passed something like "storage/app/public/xyz.jpg"
        if (str_starts_with($path, 'storage/app/public/')) {
            return URL::to('/' . $path);
        }

        // if caller passed "storage/xyz.jpg" (less common), also make absolute
        if (str_starts_with($path, 'storage/')) {
            return URL::to('/' . $path);
        }

        // default: it's a path inside the public disk (e.g. "docs_files/xyz.jpg")
        // map to the working pattern: /storage/app/public/{path}
        return URL::to('/storage/app/public/docs_files/' . $path);
    };
@endphp

@if(!empty($files))
    <div class="mb-3">
        @isset($title)
            <label class="form-label">{{ $title }}</label>
        @endisset

        <div class="d-flex flex-wrap gap-2">
            @foreach($files as $rawPath)
                @php
                    $url = $makeUrl((string)$rawPath);
                @endphp
                <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-paperclip me-1"></i>{{ basename((string)$rawPath) }}
                </a>
            @endforeach
        </div>
    </div>
@endif
