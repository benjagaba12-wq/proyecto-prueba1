<div class="inline-flex items-center gap-2 bg-purple-50 border border-purple-200 rounded-lg px-4 py-2">
    <span class="text-sm font-medium text-purple-800">UF del día:</span>
    @if ($valor)
        <span class="text-sm font-bold text-purple-900">
            ${{ number_format($valor, 2, ',', '.') }}
        </span>
        <span class="text-xs text-purple-600">({{ $fecha }})</span>
    @else
        <span class="text-sm text-red-600">No disponible</span>
    @endif
</div>