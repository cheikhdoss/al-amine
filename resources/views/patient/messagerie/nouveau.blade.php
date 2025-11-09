@extends('layouts.app')

@section('title', 'Nouveau message')
@section('page-title', 'Nouveau message')

@section('sidebar')
    @include('patient.partials.sidebar')
@endsection

@section('content')
<div class="mb-4">
    <a href="{{ route('patient.messagerie.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
        <i class="fas fa-arrow-left mr-2"></i>
        Retour
    </a>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-xl font-semibold text-gray-900 mb-6">
        <i class="fas fa-envelope text-blue-600 mr-2"></i>
        Choisir un praticien
    </h3>

    @if($praticiens->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-user-md text-gray-300 text-6xl mb-4"></i>
            <h4 class="text-lg font-semibold text-gray-700 mb-2">Aucun praticien disponible</h4>
            <p class="text-gray-500 mb-6">Vous devez avoir au moins un rendez-vous avec un praticien pour pouvoir lui envoyer un message</p>
            <a href="{{ route('patient.demander-rdv') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                <i class="fas fa-calendar-plus mr-2"></i>
                Prendre un rendez-vous
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($praticiens as $praticien)
                <a href="{{ route('patient.messagerie.show', $praticien->user) }}" 
                   class="bg-gray-50 hover:bg-blue-50 border-2 border-gray-200 hover:border-blue-500 rounded-lg p-6 transition-all">
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            {{ strtoupper(substr($praticien->user->nom ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 text-lg">
                                Dr. {{ $praticien->user->nom_complet }}
                            </h4>
                            <p class="text-sm text-gray-600">
                                {{ $praticien->specialites->pluck('nom')->implode(', ') }}
                            </p>
                            @if($praticien->service)
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-building mr-1"></i>
                                    {{ $praticien->service->nom }}
                                </p>
                            @endif
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
