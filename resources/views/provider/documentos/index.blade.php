@extends('provider.layouts.app')

@section('title', 'ZYGA | Documentos provider')
@section('page-title', 'Documentos provider')

@section('content')
    @php($r = $context['readiness'])
    <section class="hero">
        <p class="eyebrow">Expediente</p>
        <h2 style="margin:0 0 12px; font-size:2rem;">Documentos del provider</h2>
        <p class="muted" style="margin:0; line-height:1.6;">Administra tu expediente documental y mantén tu información disponible para revisión.</p>
    </section>

    @if(!$context['hasProfile'])
        <section class="lockbox">
            <h3>Primero crea tu perfil</h3>
            <a href="{{ route('provider.perfil') }}" class="btn">Ir a perfil</a>
        </section>
    @else
        <section class="two-col">
            <section class="card">
                <div class="section-head">
                    <div>
                        <p class="eyebrow">Nuevo documento</p>
                        <h3>Registrar documento</h3>
                    </div>
                </div>
                <form method="POST" action="{{ route('provider.documentos.store') }}" class="form-grid">
                    @csrf
                    <div class="field">
                        <label class="label" for="document_type">Tipo</label>
                        <input type="text" id="document_type" name="document_type" value="{{ old('document_type') }}" placeholder="Ej. licencia, identificacion" required>
                    </div>
                    <div class="field">
                        <label class="label" for="document_url">URL</label>
                        <input type="text" id="document_url" name="document_url" value="{{ old('document_url') }}" placeholder="https://..." required>
                    </div>
                    <div class="field full">
                        <button class="btn full" type="submit">Guardar documento</button>
                    </div>
                </form>
            </section>

            <section class="card">
                <div class="section-head">
                    <div>
                        <p class="eyebrow">Expediente actual</p>
                        <h3>Documentos registrados</h3>
                    </div>
                </div>

                @if(empty($context['documents']))
                    <div class="empty">
                        <h4>Sin documentos todavía</h4>
                        <p>{{ $r['documents_note'] }}</p>
                    </div>
                @else
                    <div class="list">
                        @foreach($context['documents'] as $document)
                            <article class="item">
                                <div class="item-head">
                                    <div>
                                        <h4>{{ $document['document_type'] ?? 'Documento' }}</h4>
                                        <p>{{ $document['document_url'] ?? 'Sin URL' }}</p>
                                    </div>
                                    <span class="chip info">Registrado</span>
                                </div>
                                <form method="POST" action="{{ route('provider.documentos.delete', $document['id']) }}" style="margin-top:12px;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-ghost" type="submit">Eliminar</button>
                                </form>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        </section>
    @endif
@endsection
