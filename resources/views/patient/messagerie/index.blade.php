@extends('layouts.app')

@section('title', 'Messagerie')
@section('page-title', 'Messagerie')

@section('sidebar')
    @include('patient.partials.sidebar')
@endsection

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h3 class="text-xl font-semibold text-gray-900">Mes conversations</h3>
        <p class="text-sm text-gray-500">Communiquez avec vos praticiens</p>
    </div>
    <a href="{{ route('patient.messagerie.nouveau') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center font-medium transition-colors">
        <i class="fas fa-plus-circle mr-2"></i>
        Nouveau message
    </a>
</div>

@if($conversations->isEmpty())
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <i class="fas fa-comments text-gray-300 text-6xl mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune conversation</h3>
        <p class="text-gray-500 mb-6">Vous n'avez pas encore de conversations avec vos praticiens</p>
        <a href="{{ route('patient.messagerie.nouveau') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
            <i class="fas fa-paper-plane mr-2"></i>
            Envoyer un message
        </a>
    </div>
@else
    <div class="grid grid-cols-1 gap-4">
        @foreach($conversations as $contact)
            <a href="{{ route('patient.messagerie.show', $contact) }}" 
               class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow border-l-4 {{ $contact->unread_count > 0 ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            {{ strtoupper(substr($contact->nom ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">
                                Dr. {{ $contact->nom_complet }}
                            </h4>
                            @if($contact->praticien && $contact->praticien->specialites->isNotEmpty())
                                <p class="text-sm text-gray-500">
                                    {{ $contact->praticien->specialites->pluck('nom')->implode(', ') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if($contact->unread_count > 0)
                            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $contact->unread_count }} {{ $contact->unread_count > 1 ? 'nouveaux' : 'nouveau' }}
                            </span>
                        @endif
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endif
@endsection
