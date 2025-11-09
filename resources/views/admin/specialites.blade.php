@extends('layouts.app')

@section('title', 'Spécialités médicales')
@section('page-title', 'Gestion des spécialités')
@section('breadcrumb', 'Spécialités')

@section('sidebar')
@include('admin.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Spécialités médicales</h1>
            <p class="text-sm text-gray-500">Liste des spécialités disponibles et du nombre de praticiens associés.</p>
        </div>
        <div class="text-sm text-gray-500">Total spécialités : {{ $specialites->count() }}</div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse($specialites as $specialite)
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900">{{ $specialite->libelle }}</h2>
                <p class="mt-2 text-sm text-gray-600">{{ $specialite->description ?? 'Aucune description fournie.' }}</p>
                <div class="mt-4 flex items-center justify-between text-sm">
                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">{{ $specialite->praticiens_count }} praticien(s)</span>
                    <span class="text-gray-400">Ajoutée le {{ optional($specialite->created_at)->format('d/m/Y') }}</span>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center text-sm text-gray-500">
                Aucune spécialité enregistrée pour le moment.
            </div>
        @endforelse
    </div>
</div>
@endsection
