@extends('layouts.app')

@section('title', 'Services hospitaliers')
@section('page-title', 'Gestion des services')
@section('breadcrumb', 'Services')

@section('sidebar')
@include('admin.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <!-- Messages de succès/erreur -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif
    
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Services médicaux</h1>
            <p class="text-sm text-gray-500">Aperçu des services et du nombre de praticiens associés.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-sm text-gray-500">Total services : {{ $services->count() }}</div>
            <button onclick="openCreateModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nouveau service
            </button>
        </div>
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
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($services as $service)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $service->nom }}</td>
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
                            <td class="px-4 py-3">
                                <button onclick="confirmDelete({{ $service->id }}, '{{ $service->nom }}')" 
                                        class="text-red-600 hover:text-red-800 transition"
                                        @if($service->praticiens->count() > 0) disabled title="Impossible de supprimer un service avec des praticiens" @endif>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-500">Aucun service enregistré.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de création de service -->
<div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-900">Nouveau service</h3>
            <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form action="{{ route('admin.services.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom du service *</label>
                <input type="text" name="nom"  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" placeholder="Ex: Service de cardiologie" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Localisation</label>
                <input type="text" name="localisation" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" placeholder="Ex: Bâtiment A, 2ème étage" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" placeholder="Description du service..." required></textarea>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeCreateModal()" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                    Annuler
                </button>
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Créer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Form de suppression (caché) -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }
    
    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }
    
    function confirmDelete(serviceId, serviceName) {
        if (confirm(`Êtes-vous sûr de vouloir supprimer le service "${serviceName}" ?\n\nCette action est irréversible.`)) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/services/${serviceId}`;
            form.submit();
        }
    }
    
    // Fermer le modal en cliquant en dehors
    document.getElementById('createModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeCreateModal();
        }
    });
</script>
@endsection
