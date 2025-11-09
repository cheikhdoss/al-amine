@extends('layouts.app')

@section('title', 'Services hospitaliers')
@section('page-title', 'Gestion des services')
@section('breadcrumb', 'Services')

@section('sidebar')
@include('admin.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Services médicaux</h1>
            <p class="text-sm text-gray-500">Aperçu des services et du nombre de praticiens associés.</p>
        </div>
        <div class="text-sm text-gray-500">Total services : {{ $services->count() }}</div>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Libellé</th>
                        <th class="px-4 py-3 text-left">Description</th>
                        <th class="px-4 py-3 text-left">Praticiens</th>
                        <th class="px-4 py-3 text-left">Créé le</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($services as $service)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $service->libelle }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $service->description ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-700">
                                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">{{ $service->praticiens->count() }} praticien(s)</span>
                                <div class="mt-2 flex flex-wrap gap-1 text-xs text-gray-500">
                                    @foreach($service->praticiens as $praticien)
                                        <span class="rounded bg-gray-100 px-2 py-1">{{ optional($praticien->user)->nom_complet }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ optional($service->created_at)->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-sm text-gray-500">Aucun service enregistré.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
