@extends('layouts.app')

@section('title', 'Conversation')
@section('page-title', 'Conversation avec Dr. ' . $praticien->nom_complet)

@section('sidebar')
    @include('patient.partials.sidebar')
@endsection

@section('content')
<div class="mb-4">
    <a href="{{ route('patient.messagerie.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
        <i class="fas fa-arrow-left mr-2"></i>
        Retour aux conversations
    </a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden" x-data="chatApp()">
    <!-- En-tête de conversation -->
    <div class="bg-blue-600 text-white p-6">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center text-blue-600 font-bold text-2xl">
                {{ strtoupper(substr($praticien->nom ?? 'U', 0, 1)) }}
            </div>
            <div>
                <h3 class="text-xl font-bold">Dr. {{ $praticien->nom_complet }}</h3>
                @if($praticien->praticien && $praticien->praticien->specialites->isNotEmpty())
                    <p class="text-blue-100 text-sm">
                        {{ $praticien->praticien->specialites->pluck('nom')->implode(', ') }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Zone de messages -->
    <div class="h-96 overflow-y-auto p-6 bg-gray-50 space-y-4" id="messages-container">
        @forelse($messages as $message)
            <div class="flex {{ $message->expediteur_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs lg:max-w-md">
                    <div class="rounded-lg p-4 {{ $message->expediteur_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-white text-gray-900 shadow' }}">
                        <p class="text-sm mb-2">{{ $message->contenu }}</p>
                        
                        @if($message->fichier)
                            <a href="{{ Storage::url($message->fichier) }}" 
                               target="_blank"
                               class="inline-flex items-center text-sm {{ $message->expediteur_id === auth()->id() ? 'text-blue-100 hover:text-white' : 'text-blue-600 hover:text-blue-700' }}">
                                <i class="fas fa-paperclip mr-2"></i>
                                Pièce jointe
                            </a>
                        @endif
                        
                        <p class="text-xs mt-2 {{ $message->expediteur_id === auth()->id() ? 'text-blue-100' : 'text-gray-500' }}">
                            {{ $message->created_at->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <i class="fas fa-comments text-gray-300 text-5xl mb-4"></i>
                <p class="text-gray-500">Aucun message dans cette conversation</p>
                <p class="text-sm text-gray-400">Commencez la conversation en envoyant un message</p>
            </div>
        @endforelse
    </div>

    <!-- Formulaire d'envoi -->
    <div class="border-t p-4 bg-white">
        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('patient.messagerie.store', $praticien) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <div class="flex items-end space-x-4">
                <div class="flex-1">
                    <textarea 
                        name="contenu" 
                        rows="3" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                        placeholder="Écrivez votre message..."
                        required
                    ></textarea>
                    @error('contenu')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <label class="cursor-pointer inline-flex items-center text-gray-600 hover:text-gray-800">
                        <i class="fas fa-paperclip mr-2"></i>
                        <span class="text-sm">Joindre un fichier</span>
                        <input type="file" name="fichier" class="hidden" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    </label>
                    <span class="text-xs text-gray-400" x-show="$refs.fileInput?.files[0]" x-text="$refs.fileInput?.files[0]?.name"></span>
                </div>

                <button 
                    type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center transition-colors">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Envoyer
                </button>
            </div>

            @error('fichier')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function chatApp() {
        return {
            init() {
                // Scroll to bottom on load
                const container = document.getElementById('messages-container');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }
        }
    }

    // Auto-scroll to bottom after form submission
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('messages-container');
        if (container) {
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, 100);
        }
    });
</script>
@endpush
