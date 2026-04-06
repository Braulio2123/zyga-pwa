@extends('provider.layouts.app')

@section('title', 'ZYGA | Documentos provider')
@section('page-title', 'Documentos')

@section('content')
@php
    $items = $documentosResult['data']['documents'] ?? [];
@endphp

<section class="hero-card">
    <div>
        <p class="hero-kicker">Documentación</p>
        <h2>Documentos del proveedor</h2>
        <p class="muted">Registra documentos por tipo y referencia URL, tal como lo permite la API real actual.</p>
    </div>
    <div class="hero-badge">{{ count($items) }} registrados</div>
</section>

<section class="section-block">
    <div class="section-head">
        <h3>Agregar documento</h3>
        <span class="pill">POST /provider/documents</span>
    </div>

    <form action="{{ route('provider.documentos.store') }}" method="POST" class="panel-card form-grid">
        @csrf

        <div class="form-field">
            <label for="document_type">Tipo de documento</label>
            <input type="text" id="document_type" name="document_type" value="{{ old('document_type') }}" placeholder="Ej. INE, licencia, póliza" required>
        </div>

        <div class="form-field">
            <label for="document_url">URL del documento</label>
            <input type="url" id="document_url" name="document_url" value="{{ old('document_url') }}" placeholder="https://..." required>
        </div>

        <div class="form-actions form-field-full">
            <button type="submit" class="btn-primary">Guardar documento</button>
        </div>
    </form>
</section>

<section class="section-block">
    <div class="section-head">
        <h3>Documentos registrados</h3>
        <span class="pill">{{ count($items) }} elementos</span>
    </div>

    @if(empty($items))
        <div class="panel-card">
            <h4>Sin documentos registrados</h4>
            <p class="muted">Aún no hay documentos asociados al proveedor autenticado.</p>
        </div>
    @else
        <div class="stack-list">
            @foreach($items as $documento)
                <article class="list-card">
                    <div class="inline-between gap-12">
                        <div>
                            <h4>{{ $documento['document_type'] ?? 'Documento' }}</h4>
                            <p class="break-anywhere">{{ $documento['document_url'] ?? 'Sin URL' }}</p>
                            <span class="meta-text">ID: {{ $documento['id'] ?? '-' }}</span>
                        </div>

                        <div class="actions-inline">
                            @if(!empty($documento['document_url']))
                                <a href="{{ $documento['document_url'] }}" target="_blank" rel="noopener" class="btn-secondary">Abrir</a>
                            @endif
                            <form action="{{ route('provider.documentos.delete', $documento['id']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-secondary">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>
@endsection
