@extends('layouts.app')

@section('title', 'Centre de rapports')
@section('page-title', 'Rapports et exports')
@section('breadcrumb', 'Rapports')

@section('sidebar')
@include('admin.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Rapports administratifs</h1>
            <p class="text-sm text-gray-500">Générez des rapports PDF personnalisés sur l'activité et la performance financière.</p>
        </div>
        <div class="flex gap-2 text-xs text-gray-500">
            <span class="rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-600">PDF</span>
            <span class="rounded-full bg-blue-50 px-3 py-1 font-semibold text-blue-600">Export</span>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <form method="GET" action="{{ route('admin.rapport.activite') }}" class="space-y-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Rapport d'activité</h2>
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">Consultations & RDV</span>
            </div>
            <p class="text-sm text-gray-500">Inclut les statistiques de rendez-vous, consultations réalisées, nouveaux patients et taux de présence.</p>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="text-xs font-semibold uppercase text-gray-500">Date début</label>
                    <input type="date" name="date_debut" value="{{ request('date_debut', now()->startOfMonth()->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase text-gray-500">Date fin</label>
                    <input type="date" name="date_fin" value="{{ request('date_fin', now()->endOfMonth()->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" required>
                </div>
            </div>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700">
                Générer le PDF
            </button>
        </form>

        <form method="GET" action="{{ route('admin.rapport.financier') }}" class="space-y-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Rapport financier</h2>
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">Factures & paiements</span>
            </div>
            <p class="text-sm text-gray-500">Analyse des encaissements, factures émises, paiements par méthode et montants restants.</p>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="text-xs font-semibold uppercase text-gray-500">Date début</label>
                    <input type="date" name="date_debut" value="{{ request('date_debut', now()->startOfMonth()->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase text-gray-500">Date fin</label>
                    <input type="date" name="date_fin" value="{{ request('date_fin', now()->endOfMonth()->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" required>
                </div>
            </div>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-700">
                Générer le PDF
            </button>
        </form>
    </div>

    <div class="rounded-xl border border-dashed border-gray-300 bg-white p-6 text-sm text-gray-500">
        <p class="font-medium text-gray-700">Astuce</p>
        <p class="mt-2">Les rapports sont générés en PDF et téléchargés automatiquement. Pour un suivi temps réel des indicateurs, consultez le tableau de bord principal ou exportez directement depuis les modules Facturation et Rendez-vous.</p>
    </div>
</div>
@endsection
