@extends('provider.layouts.app')

@section('title', 'ZYGA | Documentos provider')
@section('page-title', 'Documentos')

@section('content')
    <section class="hero-card">
        <div>
            <p class="hero-kicker">Módulo documental</p>
            <h2 style="margin:0 0 8px;">Documentos de validación</h2>
            <p class="muted">Registra enlaces de documentos para integrarlos al expediente del proveedor.</p>
        </div>
        <div class="hero-stat summary-card">
            <span class="helper-text">Registrados</span>
            <strong>{{ count($documents) }}</strong>
        </div>
    </section>

    @if(!$hasProfile)
        <section class="locked-module">
            <h3>Módulo bloqueado temporalmente</h3>
            <p>Primero debes crear tu perfil de proveedor para poder gestionar documentos.</p>
            <a href="{{ route('provider.perfil') }}" class="btn-primary">Ir a crear perfil</a>
        </section>
    @else
        @if(!$documentsResponse['ok'] && $documentsResponse['status'] !== 200 && $documentsResponse['status'] !== 0)
            <section class="section-card"><div class="alert danger">{{ $documentsResponse['message'] ?? 'No se pudieron cargar los documentos.' }}</div></section>
        @endif

        <section class="section-card">
            <div class="section-head">
                <div>
                    <p class="dashboard-card__eyebrow">Nuevo registro</p>
                    <h3>Agregar documento</h3>
                </div>
            </div>

            <form action="{{ route('provider.documentos.store') }}" method="POST" class="form-grid">
                @csrf
                <div class="form-field">
                    <label class="label" for="document_type">Tipo de documento</label>
                    <input type="text" name="document_type" id="document_type" value="{{ old('document_type') }}" placeholder="Ej. licencia, identificacion, seguro" required>
                </div>
                <div class="form-field">
                    <label class="label" for="document_url">URL del documento</label>
                    <input type="url" name="document_url" id="document_url" value="{{ old('document_url') }}" placeholder="https://..." required>
                </div>
                <div class="form-field full">
                    <button type="submit" class="btn-primary">Guardar documento</button>
                </div>
            </form>
        </section>

        <section class="section-card">
            <div class="section-head">
                <div>
                    <p class="dashboard-card__eyebrow">Expediente actual</p>
                    <h3>Listado de documentos</h3>
                </div>
            </div>

            @if(empty($documents))
                <div class="empty-state">
                    <h4>No hay documentos registrados</h4>
                    <p>Cuando existan documentos asociados a tu cuenta se mostrarán aquí.</p>
                </div>
            @else
                <div class="stack-list">
                    @foreach($documents as $document)
                        <article class="list-card">
                            <div class="tableish-row">
                                <div>
                                    <h4>{{ $document['document_type'] ?? 'Documento' }}</h4>
                                    <p>{{ $document['document_url'] ?? 'Sin enlace disponible' }}</p>
                                </div>
                                <div>
                                    <span class="status-chip info">{{ $document['status'] ?? 'Registrado' }}</span>
                                </div>
                                <div>
                                    <small>{{ $document['created_at'] ?? 'Sin fecha' }}</small>
                                </div>
                                <div>
                                    <form action="{{ route('provider.documentos.delete', $document['id']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-secondary btn-sm">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    @endif
@endsection
