@extends('secretaire.layouts.app')

@section('title', 'Dashboard Secrétaire')

@section('content')
<!-- Header -->
<div class="mb-8 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-2xl p-6 text-white shadow-xl">
    <h2 class="text-2xl font-bold mb-2 flex items-center gap-2">
        <span class="text-3xl">👋</span>
        Bonjour, {{ auth()->user()->prenom }}!
    </h2>
    <p class="text-indigo-100">Bienvenue sur votre tableau de bord</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-lg border border-yellow-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <span class="text-3xl font-bold text-yellow-600">{{ $stats['demandes_attente'] ?? 0 }}</span>
        </div>
        <p class="text-sm font-semibold text-gray-600">Demandes en attente</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-lg border border-blue-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <span class="text-3xl font-bold text-blue-600">{{ $stats['rdv_aujourdhui'] ?? 0 }}</span>
        </div>
        <p class="text-sm font-semibold text-gray-600">RDV Aujourd'hui</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-lg border border-purple-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <span class="text-3xl font-bold text-purple-600">{{ $stats['factures_impayees'] ?? 0 }}</span>
        </div>
        <p class="text-sm font-semibold text-gray-600">Factures Impayées</p>
    </div>

</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <a href="{{ route('secretaire.file-attente', ['statut' => 'EN_ATTENTE']) }}" class="bg-gradient-to-br from-yellow-500 via-yellow-600 to-orange-600 rounded-2xl shadow-xl p-6 text-white hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold mb-2">File d'attente</h3>
                <p class="text-yellow-100 text-sm">Gérer les demandes</p>
            </div>
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
        </div>
    </a>

    <a href="{{ route('secretaire.agendas') }}" class="bg-gradient-to-br from-blue-500 via-blue-600 to-cyan-600 rounded-2xl shadow-xl p-6 text-white hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold mb-2">Agendas</h3>
                <p class="text-blue-100 text-sm">Planning praticiens</p>
            </div>
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </a>

    <a href="{{ route('secretaire.facturation') }}" class="bg-gradient-to-br from-purple-500 via-purple-600 to-pink-600 rounded-2xl shadow-xl p-6 text-white hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold mb-2">Facturation</h3>
                <p class="text-purple-100 text-sm">Générer factures</p>
            </div>
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </a>
</div>

<!-- Recent Requests -->
<div class="bg-white rounded-2xl shadow-lg">
    <div class="p-6 border-b border-gray-100">
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                <span class="text-2xl">📋</span>
                Demandes récentes
            </h3>
            <a href="{{ route('secretaire.file-attente') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-semibold flex items-center gap-1">
                Voir tout
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
    <div class="p-6">
        @if(isset($demandesRecentes) && $demandesRecentes->isNotEmpty())
            <div class="space-y-4">
                @foreach($demandesRecentes as $demande)
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-500 rounded-xl p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 text-white rounded-xl flex items-center justify-center font-bold shadow-lg">
                                {{ substr($demande->patient->user->prenom, 0, 1) }}{{ substr($demande->patient->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $demande->patient->user->nom_complet }}</h4>
                                <p class="text-sm text-gray-600">Dr. {{ $demande->praticien->user->nom_complet }} - {{ $demande->specialite->nom }}</p>
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $demande->date_heure_souhaitee->format('d/m/Y à H:i') }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('secretaire.file-attente') }}" class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg text-sm font-semibold shadow-md hover:shadow-lg transition-all">
                            Traiter
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p class="text-gray-500">Aucune demande récente</p>
            </div>
        @endif
    </div>
</div>
@endsection

