@extends('praticien.layouts.app')

@section('title', 'Messages')

@section('content')
<div class="grid grid-cols-12 gap-6">
    <!-- Liste des conversations -->
    <div class="col-span-4">
        <div class="bg-white rounded-2xl shadow-elegant h-full">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Messages</h2>
                <p class="text-sm text-gray-600 mt-1">Communiquez avec votre équipe</p>
            </div>

            <div class="overflow-y-auto" style="max-height: calc(100vh - 250px);">
                <!-- Conversation avec la secrétaire -->
                <div class="p-4 hover:bg-gray-50 cursor-pointer border-l-4 border-purple-600 bg-purple-50">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <div class="w-12 h-12 bg-gradient-to-br from-pink-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                S
                            </div>
                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="font-semibold text-gray-900">Secrétaire</p>
                                <span class="text-xs text-gray-500">Il y a 5 min</span>
                            </div>
                            <p class="text-sm text-gray-600 truncate">Le patient est arrivé pour sa consultation</p>
                        </div>
                    </div>
                </div>

                <!-- Autres contacts -->
                <div class="p-4 hover:bg-gray-50 cursor-pointer">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold">
                                A
                            </div>
                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-gray-400 border-2 border-white rounded-full"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="font-semibold text-gray-900">Administration</p>
                                <span class="text-xs text-gray-500">Hier</span>
                            </div>
                            <p class="text-sm text-gray-600 truncate">Rappel : Réunion demain à 14h</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Zone de chat -->
    <div class="col-span-8">
        <div class="bg-white rounded-2xl shadow-elegant h-full flex flex-col">
            <!-- En-tête du chat -->
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                        S
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Secrétaire</h3>
                        <p class="text-xs text-green-600 flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                            En ligne
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-y-auto p-6 space-y-4" style="max-height: calc(100vh - 350px);">
                <!-- Message reçu -->
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-pink-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                        S
                    </div>
                    <div class="flex-1">
                        <div class="bg-gray-100 rounded-2xl rounded-tl-none p-4 max-w-md">
                            <p class="text-gray-800">Bonjour Docteur, le patient M. Diallo est arrivé pour sa consultation de 14h.</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 ml-2">Il y a 5 minutes</p>
                    </div>
                </div>

                <!-- Message envoyé -->
                <div class="flex items-start space-x-3 justify-end">
                    <div class="flex-1">
                        <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-2xl rounded-tr-none p-4 max-w-md ml-auto">
                            <p class="text-white">Merci, je le reçois dans 5 minutes. Pouvez-vous préparer son dossier svp ?</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 mr-2 text-right">Il y a 2 minutes</p>
                    </div>
                </div>

                <!-- Message reçu -->
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-pink-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                        S
                    </div>
                    <div class="flex-1">
                        <div class="bg-gray-100 rounded-2xl rounded-tl-none p-4 max-w-md">
                            <p class="text-gray-800">C'est fait ! Le dossier est prêt. Bon courage 👍</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 ml-2">À l'instant</p>
                    </div>
                </div>
            </div>

            <!-- Zone de saisie -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center space-x-3">
                    <button class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                        </svg>
                    </button>
                    <input type="text" placeholder="Écrivez votre message..." class="flex-1 bg-gray-100 rounded-full px-6 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <button class="w-12 h-12 bg-gradient-to-r from-purple-600 to-blue-600 rounded-full flex items-center justify-center hover:opacity-90 transition">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

