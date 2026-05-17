@extends('tasks::pdf.layouts.pdf')

@section('header')
<div class="header">
    <h1 class="main-title">{{ $applicant['name'] ?? 'Ansøger' }}</h1>
    <div class="subtitle">Ansøgningsdetaljer</div>
</div>
@endsection

@section('content')
<!-- Stats Overview -->
<div class="stats-grid">
    <div class="stat-item">
        <div class="stat-number">{{ $applicant['price'] ?? '0' }} DKK</div>
        <div class="stat-label">Timepris</div>
    </div>
    <div class="stat-item">
        <div class="stat-number">{{ $applicant['hires_count'] ?? 0 }}</div>
        <div class="stat-label">Ansatte</div>
    </div>
    <div class="stat-item">
        <div class="stat-number">{{ $applicant['amount'] ?? 0 }}</div>
        <div class="stat-label">Mål antal</div>
    </div>
    <div class="stat-item">
        <div class="stat-number">
            @if(isset($applicant['categories'][0]['name']))
                {{ $applicant['categories'][0]['name'] }}
            @else
                Ingen kategori
            @endif
        </div>
        <div class="stat-label">Kategori</div>
    </div>
</div>

<!-- Description Section -->
<div class="section">
    <h2 class="section-title">Beskrivelse</h2>
    <div class="description-box">
        <p>{{ $applicant['description'] ?? 'Ingen beskrivelse tilgængelig' }}</p>
    </div>
</div>

<!-- Requirements Section -->
@if(isset($applicant['tags']['requirements']) && is_array($applicant['tags']['requirements']) && count($applicant['tags']['requirements']) > 0)
<div class="section">
    <h2 class="section-title">Specifikationer & Krav</h2>
    <div class="tags-container">
        @foreach($applicant['tags']['requirements'] as $tag)
        <span class="tag">{{ $tag }}</span>
        @endforeach
    </div>
</div>
@endif

<!-- Grid Layout for Details -->
<div class="grid">
    <!-- Address -->
    <div class="info-card">
        <h3>📍 Adresse</h3>
        <p>
            {{ $applicant['address']['street'] ?? 'Ingen gade' }}<br>
            {{ $applicant['address']['city'] ?? 'Ingen by' }}, 
            {{ $applicant['address']['post_code'] ?? 'Ingen postkode' }}
        </p>
    </div>

    <!-- Period -->
    <div class="info-card">
        <h3>📅 Periode</h3>
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-icon">F</div>
                <div class="timeline-content">
                    <h4>Fra</h4>
                    <p>{{ $applicant['available']['from'] ?? 'Ukendt' }}</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-icon">T</div>
                <div class="timeline-content">
                    <h4>Til</h4>
                    <p>{{ $applicant['available']['to'] ?? 'Ukendt' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Information -->
<div class="section">
    <h2 class="section-title">Kontaktinformation</h2>
    <div class="contact-grid">
        <div class="contact-item">
            <div class="contact-icon">👤</div>
            <div>
                <h3>Oprettet af</h3>
                <p>{{ $applicant['user']['first_name'] ?? 'Ukendt' }} {{ $applicant['user']['last_name'] ?? '' }}</p>
            </div>
        </div>
        <div class="contact-item">
            <div class="contact-icon">✉️</div>
            <div>
                <h3>Email</h3>
                <p>{{ $applicant['user']['email'] ?? 'Ingen email' }}</p>
            </div>
        </div>
        <div class="contact-item">
            <div class="contact-icon">📱</div>
            <div>
                <h3>Telefon</h3>
                <p>{{ $applicant['user']['contact']['phone'] ?? 'Ingen telefon' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<div class="footer-logo">WorkBizz</div>
<p>Genereret: {{ now()->format('d/m/Y H:i') }}</p>
@endsection