<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nom -->
        <div>
            <x-input-label for="name" :value="__('Nom de famille')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="family-name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Prénom -->
        <div class="mt-4">
            <x-input-label for="prenom" :value="__('Prénom')" />
            <x-text-input id="prenom" class="block mt-1 w-full" type="text" name="prenom" :value="old('prenom')" required autocomplete="given-name" />
            <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <!-- Date de naissance -->
            <div>
                <x-input-label for="date_naissance" :value="__('Date de naissance')" />
                <x-text-input id="date_naissance" class="block mt-1 w-full" type="date" name="date_naissance" :value="old('date_naissance')" required />
                <x-input-error :messages="$errors->get('date_naissance')" class="mt-2" />
            </div>

            <!-- Sexe -->
            <div>
                <x-input-label for="sexe" :value="__('Sexe')" />
                <select id="sexe" name="sexe" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">-- {{ __('Choisir') }} --</option>
                    <option value="M" @selected(old('sexe') === 'M')>{{ __('Homme') }}</option>
                    <option value="F" @selected(old('sexe') === 'F')>{{ __('Femme') }}</option>
                </select>
                <x-input-error :messages="$errors->get('sexe')" class="mt-2" />
            </div>
        </div>

        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <!-- Numéro CNI -->
            <div>
                <x-input-label for="numero_cni" :value="__('Numéro CNI')" />
                <x-text-input id="numero_cni" class="block mt-1 w-full" type="text" name="numero_cni" :value="old('numero_cni')" required inputmode="numeric" pattern="[0-9]{13}" />
                <x-input-error :messages="$errors->get('numero_cni')" class="mt-2" />
            </div>

            <!-- Téléphone -->
            <div>
                <x-input-label for="telephone" :value="__('Téléphone')" />
                <x-text-input id="telephone" class="block mt-1 w-full" type="tel" name="telephone" :value="old('telephone')" required autocomplete="tel" />
                <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
            </div>
        </div>

        <!-- Adresse -->
        <div class="mt-4">
            <x-input-label for="adresse" :value="__('Adresse complète')" />
            <x-text-input id="adresse" class="block mt-1 w-full" type="text" name="adresse" :value="old('adresse')" autocomplete="street-address" />
            <x-input-error :messages="$errors->get('adresse')" class="mt-2" />
        </div>

        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <!-- Quartier -->
            <div>
                <x-input-label for="quartier" :value="__('Quartier')" />
                <x-text-input id="quartier" class="block mt-1 w-full" type="text" name="quartier" :value="old('quartier')" />
                <x-input-error :messages="$errors->get('quartier')" class="mt-2" />
            </div>

            <!-- Ville -->
            <div>
                <x-input-label for="ville" :value="__('Ville')" />
                <x-text-input id="ville" class="block mt-1 w-full" type="text" name="ville" :value="old('ville', 'Dakar')" autocomplete="address-level2" />
                <x-input-error :messages="$errors->get('ville')" class="mt-2" />
            </div>
        </div>

        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <!-- Numéro sécurité sociale -->
            <div>
                <x-input-label for="numero_securite_sociale" :value="__('Numéro de sécurité sociale')" />
                <x-text-input id="numero_securite_sociale" class="block mt-1 w-full" type="text" name="numero_securite_sociale" :value="old('numero_securite_sociale')" />
                <x-input-error :messages="$errors->get('numero_securite_sociale')" class="mt-2" />
            </div>

            <!-- Mutuelle -->
            <div>
                <x-input-label for="mutuelle" :value="__('Mutuelle')" />
                <x-text-input id="mutuelle" class="block mt-1 w-full" type="text" name="mutuelle" :value="old('mutuelle')" />
                <x-input-error :messages="$errors->get('mutuelle')" class="mt-2" />
            </div>
        </div>

        <!-- Numéro mutuelle -->
        <div class="mt-4">
            <x-input-label for="numero_mutuelle" :value="__('Numéro de mutuelle')" />
            <x-text-input id="numero_mutuelle" class="block mt-1 w-full" type="text" name="numero_mutuelle" :value="old('numero_mutuelle')" />
            <x-input-error :messages="$errors->get('numero_mutuelle')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
