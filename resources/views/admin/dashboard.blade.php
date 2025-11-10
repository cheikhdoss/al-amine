@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Tableau de bord - Administrateur')
@section('breadcrumb', 'Accueil')

@section('sidebar')
@include('admin.partials.sidebar')
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Utilisateurs</p>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['total_users'] ?? 0 }}</p>
            </div>
            <div class="bg-blue-100 rounded-full p-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Praticiens</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['praticiens'] ?? 0 }}</p>
            </div>
            <div class="bg-green-100 rounded-full p-3">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Patients</p>
                <p class="text-3xl font-bold text-purple-600">{{ $stats['patients'] ?? 0 }}</p>
            </div>
            <div class="bg-purple-100 rounded-full p-3">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">RDV ce mois</p>
                <p class="text-3xl font-bold text-orange-600">{{ $stats['rdv_mois'] ?? 0 }}</p>
            </div>
            <div class="bg-orange-100 rounded-full p-3">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <a href="{{ route('admin.users.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="text-center">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            <h3 class="text-xl font-bold">Nouvel utilisateur</h3>
        </div>
    </a>

    <a href="{{ route('admin.users.index') }}" class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="text-center">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <h3 class="text-xl font-bold">Gérer utilisateurs</h3>
        </div>
    </a>

    <a href="{{ route('admin.services') }}" class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="text-center">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <h3 class="text-xl font-bold">Services</h3>
        </div>
    </a>

    <a href="{{ route('admin.rapports') }}" class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="text-center">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <h3 class="text-xl font-bold">Rapports</h3>
        </div>
    </a>
</div>

<!-- Charts -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Statistiques des RDV</h3>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
            <canvas id="rdvChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Revenus mensuels</h3>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
            <canvas id="caChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b">
        <h3 class="text-xl font-bold text-gray-800">Activité récente</h3>
    </div>
    <div class="p-6">
        <div class="space-y-4">
            <div class="flex items-center space-x-4 p-4 bg-blue-50 rounded-lg">
                <div class="bg-blue-600 text-white rounded-full w-10 h-10 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800">Nouvel utilisateur créé</p>
                    <p class="text-xs text-gray-500">Il y a 2 heures</p>
                </div>
            </div>

            <div class="flex items-center space-x-4 p-4 bg-green-50 rounded-lg">
                <div class="bg-green-600 text-white rounded-full w-10 h-10 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800">Consultation terminée</p>
                    <p class="text-xs text-gray-500">Il y a 5 heures</p>
                </div>
            </div>

            <div class="flex items-center space-x-4 p-4 bg-purple-50 rounded-lg">
                <div class="bg-purple-600 text-white rounded-full w-10 h-10 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800">Paiement reçu</p>
                    <p class="text-xs text-gray-500">Il y a 1 jour</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Graphique des rendez-vous (modernisé)
    const rdvCtx = document.getElementById('rdvChart').getContext('2d');
    const rdvGradient = rdvCtx.createLinearGradient(0, 0, 0, 250);
    rdvGradient.addColorStop(0, 'rgba(54, 162, 235, 0.4)');
    rdvGradient.addColorStop(1, 'rgba(54, 162, 235, 0.05)');
    const rdvChart = new Chart(rdvCtx, {
        type: 'line',
        data: {
            labels: @json($rdvParMois->pluck('mois')->map(fn($mois) => \Carbon\Carbon::parse($mois)->format('M Y'))),
            datasets: [{
                label: 'Rendez-vous',
                data: @json($rdvParMois->pluck('total')),
                fill: true,
                backgroundColor: rdvGradient,
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'white',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointRadius: 6,
                pointHoverRadius: 8,
                tension: 0.4
            }]
        },
        options: {
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(54, 162, 235, 0.9)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 12,
                    borderRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#a0aec0', font: { size: 14 } }
                },
                y: {
                    grid: { color: 'rgba(200,200,200,0.1)' },
                    ticks: { color: '#a0aec0', font: { size: 14 }, beginAtZero: true }
                }
            }
        }
    });

    // Graphique des revenus mensuels (modernisé)
    const caCtx = document.getElementById('caChart').getContext('2d');
    const caGradient = caCtx.createLinearGradient(0, 0, 0, 250);
    caGradient.addColorStop(0, 'rgba(75, 192, 192, 0.4)');
    caGradient.addColorStop(1, 'rgba(75, 192, 192, 0.05)');
    const caChart = new Chart(caCtx, {
        type: 'line',
        data: {
            labels: @json($caParMois->pluck('mois')->map(fn($mois) => \Carbon\Carbon::parse($mois)->format('M Y'))),
            datasets: [{
                label: 'Revenus',
                data: @json($caParMois->pluck('total')),
                fill: true,
                backgroundColor: caGradient,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'white',
                pointBorderColor: 'rgba(75, 192, 192, 1)',
                pointRadius: 6,
                pointHoverRadius: 8,
                tension: 0.4
            }]
        },
        options: {
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(75, 192, 192, 0.9)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 12,
                    borderRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#a0aec0', font: { size: 14 } }
                },
                y: {
                    grid: { color: 'rgba(200,200,200,0.1)' },
                    ticks: { color: '#a0aec0', font: { size: 14 }, beginAtZero: true }
                }
            }
        }
    });
</script>
@endsection

