<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion - Hôpital Al-Amine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .slide {
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Left Side - Image Carousel -->
        <div class="hidden lg:flex lg:w-1/2 gradient-bg relative overflow-hidden">
            <div class="absolute inset-0 bg-black bg-opacity-30"></div>

            <!-- Carousel -->
            <div class="relative z-10 flex flex-col justify-center items-center w-full p-12 text-white" x-data="{
                currentSlide: 0,
                slides: [
                    {
                        image: 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=800&h=1000&fit=crop',
                        title: 'Soins de Qualité',
                        subtitle: 'Des professionnels dévoués à votre santé',
                        role: 'Équipe Médicale'
                    },
                    {
                        image: 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=800&h=1000&fit=crop',
                        title: 'Technologies Avancées',
                        subtitle: 'Équipements médicaux de pointe',
                        role: 'Innovation Médicale'
                    },
                    {
                        image: 'https://images.unsplash.com/photo-1582750433449-648ed127bb54?w=800&h=1000&fit=crop',
                        title: 'Accompagnement Personnel',
                        subtitle: 'Un suivi attentif pour chaque patient',
                        role: 'Soins Personnalisés'
                    },
                    {
                        image: 'https://images.unsplash.com/photo-1638202993928-7267aad84c31?w=800&h=1000&fit=crop',
                        title: 'Expertise Médicale',
                        subtitle: 'Des spécialistes reconnus à votre service',
                        role: 'Excellence Médicale'
                    }
                ]
            }" x-init="setInterval(() => { currentSlide = (currentSlide + 1) % slides.length }, 5000)">

                <!-- Logo -->
                <div class="absolute top-8 left-12">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                            <span class="text-2xl">🏥</span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold">Al-Amine</h1>
                            <p class="text-sm text-gray-200">Centre Hospitalier</p>
                        </div>
                    </div>
                </div>

                <!-- Slide Content -->
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="currentSlide === index" class="slide text-center max-w-2xl">
                        <div class="mb-8">
                            <img :src="slide.image" :alt="slide.title"
                                 class="w-64 h-64 rounded-full object-cover border-8 border-white shadow-2xl mx-auto">
                        </div>
                        <span class="inline-block px-4 py-2 bg-white bg-opacity-20 rounded-full text-sm mb-4" x-text="slide.role"></span>
                        <h2 class="text-4xl font-bold mb-4" x-text="slide.title"></h2>
                        <p class="text-xl text-gray-200" x-text="slide.subtitle"></p>
                    </div>
                </template>

                <!-- Dots Indicator -->
                <div class="absolute bottom-12 flex space-x-2">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click="currentSlide = index"
                                :class="currentSlide === index ? 'bg-white w-8' : 'bg-white bg-opacity-50 w-2'"
                                class="h-2 rounded-full transition-all duration-300"></button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="max-w-md w-full">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <div class="inline-flex items-center space-x-3">
                        <div class="w-12 h-12 gradient-bg rounded-full flex items-center justify-center">
                            <span class="text-2xl">🏥</span>
                        </div>
                        <div class="text-left">
                            <h1 class="text-2xl font-bold text-gray-800">Al-Amine</h1>
                            <p class="text-sm text-gray-600">Centre Hospitalier</p>
                        </div>
                    </div>
                </div>

                <!-- Welcome Text -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Bienvenue !</h2>
                    <p class="text-gray-600">Connectez-vous à votre espace personnel</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm text-green-800">{{ session('status') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-800">{{ $errors->first() }}</p>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Adresse Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                   placeholder="votre.email@exemple.com">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Mot de passe
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500">
                            <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-semibold text-purple-600 hover:text-purple-700">
                                Mot de passe oublié ?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full gradient-bg text-white font-semibold py-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        Se connecter
                    </button>
                </form>

                <!-- Test Accounts -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div x-data="{ showAccounts: false }" class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-4 border border-blue-200">
                        <button @click="showAccounts = !showAccounts" class="w-full flex items-center justify-between text-left">
                            <div class="flex items-center">
                                <span class="text-2xl mr-3">🔑</span>
                                <div>
                                    <h3 class="font-semibold text-gray-800">Comptes de Test</h3>
                                    <p class="text-xs text-gray-600">Cliquez pour voir les identifiants</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-600 transform transition-transform" :class="showAccounts ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="showAccounts" x-transition class="mt-4 space-y-3" style="display: none;">
                            <!-- Admin -->
                            <div class="bg-white rounded-lg p-3 border-l-4 border-red-500">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-bold text-red-700 uppercase">👨‍💼 Admin</span>
                                    <button onclick="fillLogin('admin@alamine.sn', 'password')" class="text-xs bg-red-100 hover:bg-red-200 text-red-700 px-2 py-1 rounded">
                                        Utiliser
                                    </button>
                                </div>
                                <p class="text-xs text-gray-600">📧 admin@alamine.sn</p>
                                <p class="text-xs text-gray-600">🔒 password</p>
                            </div>

                            <!-- Praticien -->
                            <div class="bg-white rounded-lg p-3 border-l-4 border-blue-500">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-bold text-blue-700 uppercase">👨‍⚕️ Praticien</span>
                                    <button onclick="fillLogin('praticien1@alamine.sn', 'password')" class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded">
                                        Utiliser
                                    </button>
                                </div>
                                <p class="text-xs text-gray-600">📧 praticien1@alamine.sn</p>
                                <p class="text-xs text-gray-600">🔒 password</p>
                                <p class="text-xs text-gray-500 mt-1">Dr. Cheikh NDIAYE - Médecine Générale</p>
                            </div>

                            <!-- Secrétaire -->
                            <div class="bg-white rounded-lg p-3 border-l-4 border-purple-500">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-bold text-purple-700 uppercase">💼 Secrétaire</span>
                                    <button onclick="fillLogin('secretaire1@alamine.sn', 'password')" class="text-xs bg-purple-100 hover:bg-purple-200 text-purple-700 px-2 py-1 rounded">
                                        Utiliser
                                    </button>
                                </div>
                                <p class="text-xs text-gray-600">📧 secretaire1@alamine.sn</p>
                                <p class="text-xs text-gray-600">🔒 password</p>
                                <p class="text-xs text-gray-500 mt-1">Aïssatou FALL</p>
                            </div>

                            <!-- Patient -->
                            <div class="bg-white rounded-lg p-3 border-l-4 border-green-500">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-bold text-green-700 uppercase">🏥 Patient</span>
                                    <button onclick="fillLogin('patient1@example.sn', 'password')" class="text-xs bg-green-100 hover:bg-green-200 text-green-700 px-2 py-1 rounded">
                                        Utiliser
                                    </button>
                                </div>
                                <p class="text-xs text-gray-600">📧 patient1@example.sn</p>
                                <p class="text-xs text-gray-600">🔒 password</p>
                                <p class="text-xs text-gray-500 mt-1">Patient de test (30 comptes disponibles: patient1 à patient30)</p>
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 rounded p-2 mt-3">
                                <p class="text-xs text-yellow-800">
                                    ⚠️ <strong>Note:</strong> Tous les mots de passe sont "password" pour les tests
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-500">
                        © 2025 Hôpital Al-Amine. Tous droits réservés.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fillLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            // Scroll to top to see the filled form
            window.scrollTo({ top: 0, behavior: 'smooth' });
            // Add a visual feedback
            document.getElementById('email').classList.add('ring-2', 'ring-green-500');
            document.getElementById('password').classList.add('ring-2', 'ring-green-500');
            setTimeout(() => {
                document.getElementById('email').classList.remove('ring-2', 'ring-green-500');
                document.getElementById('password').classList.remove('ring-2', 'ring-green-500');
            }, 1500);
        }
    </script>
</body>
</html>
