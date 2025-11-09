@extends('layouts.app')

@section('title', 'Gestion des utilisateurs')
@section('page-title', 'Gestion des utilisateurs')
@section('breadcrumb', 'Utilisateurs')

@section('sidebar')
@include('admin.partials.sidebar')
@endsection

@section('content')
<div class="space-y-8">
    <!-- Header & Actions -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Utilisateurs</h1>
            <p class="text-sm text-gray-500">Visualisez, filtrez et gérez l'ensemble des utilisateurs de la plateforme.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700">
            <span class="text-lg">＋</span>
            <span>Nouvel utilisateur</span>
        </a>
    </div>

    <!-- Metrics -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Total</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $metrics['total'] ?? 0 }}</p>
            <p class="mt-1 text-xs text-gray-400">Tous les comptes enregistrés</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Actifs</p>
            <p class="mt-2 text-3xl font-semibold text-emerald-600">{{ $metrics['actifs'] ?? 0 }}</p>
            <p class="mt-1 text-xs text-gray-400">Comptes en activité</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Administrateurs</p>
            <p class="mt-2 text-3xl font-semibold text-indigo-600">{{ $metrics['admins'] ?? 0 }}</p>
            <p class="mt-1 text-xs text-gray-400">Gestion des accès</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Nouveaux (30 j)</p>
            <p class="mt-2 text-3xl font-semibold text-orange-600">{{ $metrics['recents'] ?? 0 }}</p>
            <p class="mt-1 text-xs text-gray-400">Inscrits ce mois-ci</p>
        </div>
    </div>

    <!-- Filtres -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-5">
            <div class="md:col-span-2">
                <label class="text-xs font-medium uppercase text-gray-500">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email, téléphone..." class="mt-1 w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:border-blue-500 focus:bg-white focus:outline-none" />
            </div>
            <div>
                <label class="text-xs font-medium uppercase text-gray-500">Rôle</label>
                <select name="role" class="mt-1 w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:border-blue-500 focus:bg-white focus:outline-none">
                    <option value="">Tous</option>
                    <option value="PATIENT" @selected(request('role') === 'PATIENT')>Patient</option>
                    <option value="PRATICIEN" @selected(request('role') === 'PRATICIEN')>Praticien</option>
                    <option value="SECRETAIRE" @selected(request('role') === 'SECRETAIRE')>Secrétaire</option>
                    <option value="ADMIN" @selected(request('role') === 'ADMIN')>Administrateur</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-medium uppercase text-gray-500">Statut</label>
                <select name="statut" class="mt-1 w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:border-blue-500 focus:bg-white focus:outline-none">
                    <option value="">Tous</option>
                    <option value="ACTIF" @selected(request('statut') === 'ACTIF')>Actif</option>
                    <option value="SUSPENDU" @selected(request('statut') === 'SUSPENDU')>Suspendu</option>
                    <option value="DESACTIVE" @selected(request('statut') === 'DESACTIVE')>Désactivé</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white shadow hover:bg-gray-700">Filtrer</button>
                <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">Réinitialiser</a>
            </div>
        </form>
    </div>

    <!-- Tableau -->
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Utilisateur</th>
                        <th class="px-4 py-3 text-left">Contact</th>
                        <th class="px-4 py-3 text-left">Rôle</th>
                        <th class="px-4 py-3 text-left">Statut</th>
                        <th class="px-4 py-3 text-left">Créé le</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($user->photo)
                                        <img src="{{ asset($user->photo) }}" alt="Avatar" class="h-10 w-10 rounded-full object-cover" />
                                    @else
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-600">
                                            {{ strtoupper(substr($user->prenom ?? $user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $user->prenom }} {{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">N° CNI : {{ $user->numero_cni }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-gray-700">{{ $user->email }}</p>
                                <p class="text-xs text-gray-500">{{ $user->telephone }}</p>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $roleColors = [
                                        'ADMIN' => 'bg-red-100 text-red-700',
                                        'PRATICIEN' => 'bg-indigo-100 text-indigo-700',
                                        'SECRETAIRE' => 'bg-sky-100 text-sky-700',
                                        'PATIENT' => 'bg-emerald-100 text-emerald-700',
                                    ];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst(strtolower($user->role)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = [
                                        'ACTIF' => 'bg-emerald-100 text-emerald-700',
                                        'SUSPENDU' => 'bg-amber-100 text-amber-700',
                                        'DESACTIVE' => 'bg-rose-100 text-rose-700',
                                    ];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusColors[$user->statut_compte] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst(strtolower($user->statut_compte)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-medium text-gray-600 hover:bg-gray-100">Voir</a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="rounded-lg border border-blue-500 px-3 py-2 text-xs font-medium text-blue-600 hover:bg-blue-50">Modifier</a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-500 px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-gray-500">
                                Aucun utilisateur ne correspond à votre recherche.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col items-center justify-between gap-3 border-t border-gray-100 px-4 py-4 text-sm text-gray-500 md:flex-row">
            <div>
                Affichage de {{ $users->firstItem() ?? 0 }} à {{ $users->lastItem() ?? 0 }} sur {{ $users->total() }} utilisateurs
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
