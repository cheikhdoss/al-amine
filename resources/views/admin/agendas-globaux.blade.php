@extends('layouts.app')

@section('title', 'Agendas globaux')
@section('page-title', 'Agendas globaux')
@section('breadcrumb', 'Agendas globaux')

@section('sidebar')
@include('admin.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Calendrier des rendez-vous</h1>
            <p class="text-sm text-gray-500">Vue consolidée des rendez-vous sur la période sélectionnée.</p>
        </div>
        <div class="rounded-lg bg-blue-50 px-4 py-2 text-sm font-medium text-blue-600">{{ $rdv->count() }} rendez-vous chargés</div>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Date & heure</th>
                        <th class="px-4 py-3 text-left">Patient</th>
                        <th class="px-4 py-3 text-left">Praticien</th>
                        <th class="px-4 py-3 text-left">Motif</th>
                        <th class="px-4 py-3 text-left">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($rdv as $element)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-700">{{ optional($element->date_heure_rdv)->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <div class="text-gray-900">{{ optional(optional($element->patient)->user)->nom_complet }}</div>
                                <div class="text-xs text-gray-500">{{ optional(optional($element->patient)->user)->telephone }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-gray-900">{{ optional(optional($element->praticien)->user)->nom_complet }}</div>
                                <div class="text-xs text-gray-500">{{ optional(optional($element->praticien)->service)->libelle }}</div>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $element->motif ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = [
                                        'CONFIRME' => 'bg-emerald-100 text-emerald-700',
                                        'EN_ATTENTE' => 'bg-amber-100 text-amber-700',
                                        'ANNULE' => 'bg-rose-100 text-rose-700',
                                        'TERMINE' => 'bg-blue-100 text-blue-700',
                                    ];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusColors[$element->statut] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst(strtolower($element->statut)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-500">Aucun rendez-vous programmé sur cette période.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
