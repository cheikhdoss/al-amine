@extends('layouts.app')

@section('title', 'Journal d\'audit')
@section('page-title', 'Journal d\'audit')
@section('breadcrumb', 'Audit')

@section('sidebar')
@include('admin.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Historique des actions</h1>
            <p class="text-sm text-gray-500">Suivez les opérations sensibles réalisées par les utilisateurs.</p>
        </div>
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher une action ou un utilisateur" class="w-64 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
            <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">Rechercher</button>
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Utilisateur</th>
                        <th class="px-4 py-3 text-left">Action</th>
                        <th class="px-4 py-3 text-left">Description</th>
                        <th class="px-4 py-3 text-left">Adresse IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($audits as $audit)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-600">{{ $audit->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <div class="text-gray-900">{{ optional($audit->user)->nom_complet ?? 'Utilisateur inconnu' }}</div>
                                <div class="text-xs text-gray-500">{{ optional($audit->user)->email }}</div>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $audit->action }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $audit->description ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $audit->ip_address ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-500">Aucune activité enregistrée pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between border-t border-gray-100 px-4 py-4 text-sm text-gray-500">
            <span>Résultats : {{ $audits->total() }}</span>
            <div>{{ $audits->links() }}</div>
        </div>
    </div>
</div>
@endsection
