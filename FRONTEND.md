# Frontend - Al-Amine - Guide de Reproduction Complet

## Vue d'ensemble

Ce document est destiné à expliquer à une autre IA (ou développeur) comment reproduire **EXACTEMENT** et **GLOBALEMENT** tout le frontend (toutes les vues et pages) du système Al-Amine.

Le frontend est intégré dans Laravel Blade avec Tailwind CSS, Alpine.js et Vite pour la compilation des assets.

---

## Architecture Frontend

### Structure des fichiers

```
resources/
├── views/               # Toutes les vues Blade
│   ├── admin/          # Vues pour les administrateurs
│   ├── auth/           # Vues d'authentification
│   ├── components/     # Composants Blade réutilisables
│   ├── dashboard.blade.php
│   ├── landing.blade.php
│   ├── welcome.blade.php
│   ├── emails/         # Templates email
│   ├── layouts/        # Layouts principaux
│   ├── patient/        # Vues pour les patients
│   ├── pdf/            # Templates PDF (DomPDF)
│   ├── praticien/      # Vues pour les praticiens
│   ├── profile/        # Pages de profil
│   ├── secretaire/     # Vues pour les secrétaires
│   └── vendor/         # Vues des packages externes
├── css/                # Styles CSS/Tailwind
├── js/                 # Scripts JavaScript/Alpine
└── app.blade.php       # Layout principal

public/
├── css/                # CSS compilé (via Vite)
├── js/                 # JavaScript compilé (via Vite)
├── build/              # Assets générés par Vite
├── photos/             # Images et photos
└── fonts/              # Polices personnalisées
```

---

## Stack Technologique Frontend

### Dependencies (package.json)
- **Laravel Vite Plugin** ^2.0.0 - Intégration Vite avec Laravel
- **Tailwind CSS** ^3.1.0 - Framework CSS utilitaire
- **Alpine.js** ^3.4.2 - Framework JavaScript léger pour l'interactivité
- **ApexCharts** ^5.3.6 - Graphiques et statistiques
- **Laravel Echo** ^1.15.4 - Broadcasting en temps réel
- **Pusher.js** ^8.4.0 - Socket WebSocket pour le chat
- **Axios** ^1.11.0 - Client HTTP
- **PostCSS** ^8.4.31 - Traitement CSS
- **Autoprefixer** ^10.4.2 - Compatibilité navigateur

### DevDependencies (package.json)
- **Vite** ^7.0.7 - Bundler et dev server
- **@tailwindcss/forms** ^0.5.2 - Composants formulaires Tailwind
- **@tailwindcss/vite** ^4.0.0 - Plugin Vite pour Tailwind
- **concurrently** ^9.0.1 - Exécuter plusieurs commandes npm en parallèle

---

## Pages et Vues (Listage Complet)

### 1. Pages Publiques (sans authentification)

#### Landing Page (`landing.blade.php`)
- Première page vue par les utilisateurs non connectés
- Contient les CTA (Appels à l'action)
- Présentation du service
- Liens de connexion/inscription

#### Page Welcome (`welcome.blade.php`)
- Page par défaut/accueil alternatif

#### Pages d'Authentification (`auth/`)
- `login.blade.php` - Formulaire de connexion
- `register.blade.php` - Formulaire d'inscription
- `forgot-password.blade.php` - Demande de réinitialisation
- `reset-password.blade.php` - Réinitialisation du mot de passe
- `verify-email.blade.php` - Vérification d'email
- `confirm-password.blade.php` - Confirmation du mot de passe

---

### 2. Dashboard (Page d'accueil après connexion)

#### Route
```
/dashboard → Redirection dynamique selon le rôle
```

Le système utilise un redirection intelligente:
```
ADMIN → admin.dashboard
PATIENT → patient.dashboard
PRATICIEN → praticien.dashboard
SECRETAIRE → secretaire.dashboard
```

---

### 3. Vues PATIENT (`patient/`)

#### 3.1 Dashboard Patient (`patient/dashboard.blade.php`)
- Vue générale des RDV à venir
- Dernières consultations
- Widgets statistiques (consultations, paiements)
- Notifications importantes
- Actions rapides (Demander RDV, Consulter dossier)

#### 3.2 Profil Patient (`patient/profile/`)
- `edit.blade.php` - Édition du profil avec onglets:
  - Informations personnelles (nom, prénom, email, tél, adresse)
  - Informations de santé (groupe sanguin, allergies, antécédents)
  - Informations d'assurance (numéro, compagnie)
  - Photo de profil
  - Changement de mot de passe

#### 3.3 Dossier Médical (`patient/dossier-medical/`)
- `index.blade.php` - Liste de toutes les consultations
- `show.blade.php` - Détails d'une consultation (diagnostic, observations)
- Affichage des ordonnances (télécharger PDF)
- Affichage des examens (télécharger PDF)
- Affichage des documents médicaux
- Section allergies et antécédents (modifiable)

#### 3.4 Demande de RDV (`patient/demander-rdv.blade.php`)
- Formulaire de demande
- Sélection du praticien/service
- Dates disponibles
- Notes additionnelles

#### 3.5 Mes Demandes (`patient/mes-demandes.blade.php`)
- Historique des demandes
- Statut (en attente, acceptée, refusée, en cours de reprogrammation)
- Actions possibles

#### 3.6 Mes RDV (`patient/mes-rdv.blade.php`)
- Liste de tous les rendez-vous
- Calendrier visuel
- Actions: Voir détails, Annuler, Reprogrammer

#### 3.7 RDV Détail (`patient/rendezvous-show.blade.php`)
- Informations complètes du RDV
- Praticien assigné
- Horaire
- Localisation
- Boutons d'action

#### 3.8 Factures (`patient/factures.blade.php`)
- Liste des factures
- Montant, date, statut paiement
- Télécharger facture PDF

#### 3.9 Paiement (`patient/paiement.blade.php`)
- Interface de paiement
- Intégration PayDunya
- Intégration Stripe
- Historique des tentatives

#### 3.10 Notifications (`patient/notifications.blade.php`)
- Centre de notifications
- Marquer comme lues
- Supprimer notifications

#### 3.11 Messagerie (`patient/messagerie/`)
- `index.blade.php` - Liste des conversations
- `show.blade.php` - Vue d'une conversation
- Chat en temps réel (WebSocket via Reverb/Pusher)
- Historique des messages

#### 3.12 Calendrier (`patient/calendrier.blade.php`)
- Vue calendrier des RDV
- Événements visuels
- Export iCal / Google Calendar

#### 3.13 Documents Médicaux (`patient/documents.blade.php`)
- Liste de tous les documents
- Ordonnances
- Examens
- Certificats (génération)
- Attestations (génération)

#### 3.14 Suivi Santé (`patient/suivi-sante.blade.php`)
- Mesures de santé enregistrées par le praticien
- Graphiques et courbes
- Historique (lecture seule)

---

### 4. Vues PRATICIEN (`praticien/`)

#### 4.1 Dashboard Praticien (`praticien/dashboard.blade.php`)
- Vue générale: RDV du jour, consultations en attente
- Widgets: Nombre de patients, consultations, factures en attente
- Calendrier de la journée
- Patients à voir

#### 4.2 Profil Praticien (`praticien/profile.blade.php`)
- Informations personnelles
- Spécialité/Services
- Horaires de travail

#### 4.3 Mes Patients (`praticien/patients.blade.php`)
- Liste de tous les patients du praticien
- Recherche, filtrage
- Actions: Voir dossier, Consulter profil

#### 4.4 Dossier Patient (`praticien/patient-dossier.blade.php`)
- Historique complet du patient
- Consultations précédentes
- Ordonnances émises
- Mesures de santé

#### 4.5 Disponibilités (`praticien/disponibilites.blade.php`)
- Ajouter/Modifier disponibilités
- Horaires de travail
- Jours de congés

#### 4.6 Consultations (`praticien/consultations.blade.php`)
- Liste des consultations planifiées
- Filtrer par date
- Actions: Commencer consultation, Voir détails

#### 4.7 Consultation Détail (`praticien/consultation-show.blade.php`)
- Interface de consultation
- Données du patient
- Champs pour:
  - Diagnostic
  - Observations
  - Prescription
  - Examen physique
  - Mesures de santé

#### 4.8 Ordonnance (`praticien/ordonnance.blade.php`)
- Formulaire de création ordonnance
- Sélection de médicaments
- Dosage et durée
- Instructions
- Génération PDF

#### 4.9 Mes Documents (`praticien/documents.blade.php`)
- Historique des ordonnances créées
- Documentsmédicaux générés

#### 4.10 Mon Agenda (`praticien/agenda.blade.php`)
- Vue calendrier
- RDV planifiés
- Disponibilités libres

#### 4.11 Messages (`praticien/messages/`)
- `index.blade.php` - Centre de messagerie
- `conversations.blade.php` - Liste conversations
- `show.blade.php` - Vue conversation
- Chat en temps réel

---

### 5. Vues SECRETAIRE (`secretaire/`)

#### 5.1 Dashboard Secrétaire (`secretaire/dashboard.blade.php`)
- Widgets: Demandes en attente, RDV du jour, Paiements en attente
- File d'attente avec patients actuels
- Calendrier partagé

#### 5.2 Profil Secrétaire (`secretaire/profile.blade.php`)
- Informations personnelles
- Modification des données
- Changement de mot de passe

#### 5.3 File d'Attente (`secretaire/file-attente.blade.php`)
- Demandes de RDV en attente
- Actions: Valider, Refuser
- Détails de chaque demande

#### 5.4 Demande Détail + Reprogrammation (`secretaire/demande-detail.blade.php`)
- Voir la demande
- Formulaire de reprogrammation
- Sélectionner date/heure disponible

#### 5.5 Agendas (`secretaire/agendas.blade.php`)
- Vue de tous les agendas des praticiens
- Calendrier par praticien
- Actions: Modifier RDV, Ajouter consultation

#### 5.6 Planning Multi-Agendas (`secretaire/agendas-multi.blade.php`)
- Vue d'ensemble de tous les praticiens
- Timeline
- Actions rapides

#### 5.7 Agenda Praticien (`secretaire/agenda-praticien.blade.php`)
- Agenda détaillé d'un praticien
- RDV et consultations
- Possibilité de replanifier

#### 5.8 Facturation (`secretaire/facturation.blade.php`)
- Génération de factures
- Sélection consultation
- Montant et détails
- Génération PDF

#### 5.9 Encaissements (`secretaire/encaissements.blade.php`)
- Historique des paiements
- Montants encaissés
- Méthodes de paiement

#### 5.10 Relances (`secretaire/relances.blade.php`)
- Templates de relance (SMS/Email)
- Modifier templates
- Envoyer relances automatiques

#### 5.11 Messages (`secretaire/messages/`)
- Centre de messagerie
- Conversations avec patients et praticiens
- Chat en temps réel

---

### 6. Vues ADMIN (`admin/`)

#### 6.1 Dashboard Admin (`admin/dashboard.blade.php`)
- Statistiques globales
- Graphiques d'activité
- Utilisateurs actifs
- Revenus

#### 6.2 Gestion des Utilisateurs (`admin/users/`)
- `index.blade.php` - Liste tous les utilisateurs (tous les rôles)
- `create.blade.php` - Créer nouvel utilisateur
- `edit.blade.php` - Modifier utilisateur
- `show.blade.php` - Voir détails utilisateur
- Actions: Créer, Éditer, Supprimer, Réinitialiser mot de passe

#### 6.3 Agendas Globaux (`admin/agendas-globaux.blade.php`)
- Vue de tous les agendas
- Gestion globale
- Résolution des conflits

#### 6.4 Services (`admin/services.blade.php`)
- Liste des services/spécialités
- Ajouter/Modifier/Supprimer services

#### 6.5 Rapports (`admin/rapports.blade.php`)
- Centre de rapports
- Sélection type de rapport

#### 6.6 Rapport d'Activité (`admin/rapport-activite.blade.php`)
- Graphiques d'activité
- Nombre de consultations
- Praticiens les plus actifs
- Patients nouveaux
- Exportable

#### 6.7 Rapport Financier (`admin/rapport-financier.blade.php`)
- Revenus totaux
- Graphiques de revenus
- Paiements par méthode
- Factures impayées

#### 6.8 Audit (`admin/audit.blade.php`)
- Journal des actions
- Qui a fait quoi et quand
- Filtrage par utilisateur/type d'action

---

## Composants Réutilisables (Components)

Tous les fichiers dans `resources/views/components/` sont des composants Blade réutilisables:

```
components/
├── auth-card.blade.php
├── button.blade.php
├── card.blade.php
├── form-input.blade.php
├── form-select.blade.php
├── form-textarea.blade.php
├── modal.blade.php
├── notification.blade.php
├── sidebar.blade.php
├── navbar.blade.php
├── table.blade.php
├── tabs.blade.php
└── ... (autres)
```

Utilisés en Blade comme: `<x-card title="Mon titre">...</x-card>`

---

## Layouts (Mises en page)

### `layouts/app.blade.php`
- Layout principal pour utilisateurs connectés
- Contient: Sidebar, Navbar, Footer
- Inclut les scripts Alpine.js et Vite

### `layouts/guest.blade.php`
- Layout pour pages publiques (auth, landing)
- Sans sidebar/navbar principale

### `layouts/pdf.blade.php`
- Layout pour templates PDF (factures, ordonnances)

---

## Styles (CSS)

### `resources/css/app.css`
- Imports Tailwind CSS
- Variables CSS personnalisées
- Styles globaux

**Tailwind Configuration** (`tailwind.config.js`):
```javascript
- Colours customisés
- Fonts personnalisées
- Breakpoints responsifs
- Plugins (@tailwindcss/forms)
```

---

## Scripts JavaScript (Alpine.js)

### `resources/js/app.js`
- Point d'entrée principal
- Initialise Alpine.js
- Enregistre les composants globaux

### Interactivité Alpine.js
- Modales (ouverture/fermeture)
- Toggles (menus, accordéons)
- Formulaires dynamiques
- Notifications
- Real-time updates avec Echo/Pusher

### WebSocket (Real-time)
- Chat en temps réel via `laravel-echo`
- Notifications push
- Updates d'agenda en temps réel

---

## Configuration Vite

### `vite.config.js`
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
```

**Assets compilés:**
- CSS: `public/build/assets/app-*.css`
- JS: `public/build/assets/app-*.js`

---

## Commandes Build & Dev

### Installation
```bash
npm install
```

### Développement (avec hot reload)
```bash
npm run dev
```

Le serveur Vite est accessible à `http://localhost:5173` (hot module reload)

### Build Production
```bash
npm run build
```

Génère les assets optimisés dans `public/build/`

### Build + Dev Server Laravel
```bash
npm run dev          # Dans un terminal
php artisan serve    # Dans un autre terminal
```

---

## Architecture Responsive

Toutes les pages sont:
- **Responsive** (Mobile, Tablet, Desktop)
- **Mobile-first** avec Tailwind
- **Accessible** (WCAG standards)

**Breakpoints Tailwind:**
- `sm`: 640px
- `md`: 768px
- `lg`: 1024px
- `xl`: 1280px
- `2xl`: 1536px

---

## Authentification & Rôles

### Middleware de rôle (`app/Http/Middleware/`)
- `role:PATIENT` - Protège routes patients
- `role:PRATICIEN` - Protège routes praticiens
- `role:SECRETAIRE` - Protège routes secrétaires
- `role:ADMIN` - Protège routes admin
- `verified` - Vérification email

### Flash Messages
- Succès, erreurs, avertissements
- Affichés automatiquement dans les vues

---

## Assets Statiques

### Images
- Location: `public/photos/`
- Format: JPG, PNG, WebP
- Utilisés pour: Photos de profil, icônes, backgrounds

### Fonts
- Location: `public/fonts/`
- Peuvent être des fonts personnalisées

### CSS compilé
- Location: `public/css/`
- Généré par Tailwind + Vite

### JS compilé
- Location: `public/js/` et `public/build/`
- Généré par Vite

---

## Intégration Tiers

### PayDunya
- Payment gateway
- Pages de paiement intégrées
- Return/Callback pages

### Stripe
- Paiements alternatifs
- Webhooks intégrés

### Pusher/Reverb
- WebSocket pour chat
- Broadcasting real-time
- Notifications push

---

## Email Templates

### `resources/views/emails/`
- Confirmation d'inscription
- Réinitialisation de mot de passe
- Notifications d'appointment
- Relances de paiement
- Confirmations de consultation

Envoyées via Laravel Mail (SMTP Gmail configuré)

---

## PDF Templates

### `resources/views/pdf/`
- `facture.blade.php` - PDF de facture
- `ordonnance.blade.php` - PDF d'ordonnance
- `examen.blade.php` - PDF de résultats d'examen
- `attestation.blade.php` - Attestation médicale

Générés par DomPDF.

---

## Résumé des Pages par Rôle

| Rôle | Pages Principales | Nombre |
|------|------------------|--------|
| **PATIENT** | Dashboard, Profil, Dossier Médical, RDV, Factures, Messagerie, Suivi Santé | ~14 |
| **PRATICIEN** | Dashboard, Profil, Patients, Consultations, Ordonnances, Messages | ~11 |
| **SECRETAIRE** | Dashboard, File d'attente, Agendas, Facturation, Relances, Messages | ~10 |
| **ADMIN** | Dashboard, Utilisateurs, Rapports, Audit, Services | ~8 |
| **PUBLIC** | Landing, Auth Pages | ~7 |
| **TOTAL** | | **~50+ pages** |

---

## Installation & Reproduction Complète

### Pré-requis
- Node.js ^16.0
- npm ^8.0
- Laravel CLI (artisan)
- PHP ^8.2

### Étapes
1. **Cloner le repo**
2. **Installer dépendances frontend:**
   ```bash
   cd backend
   npm install
   ```
3. **Configurer `.env` (voir BACKEND.md)**
4. **Compiler les assets:**
   ```bash
   npm run dev      # Développement avec hot reload
   npm run build    # Production
   ```
5. **Démarrer serveur dev Laravel + Vite:**
   ```bash
   npm run dev              # Terminal 1
   php artisan serve        # Terminal 2
   ```
6. **Accéder:** `http://localhost:8000`

---

## Points Clés à Reproduire

✅ **Toutes les vues Blade** sont dans `resources/views/`
✅ **Tous les styles** sont en Tailwind CSS
✅ **Toute l'interactivité** est via Alpine.js + WebSocket
✅ **Configuration centralisée** dans `vite.config.js` et `tailwind.config.js`
✅ **Assets compilés** automatiquement via Vite
✅ **Responsif** sur tous les appareils
✅ **Authentification** par rôles
✅ **Real-time** via Pusher/Reverb
✅ **PDFs** via DomPDF
✅ **Paiements** via PayDunya/Stripe

---

## Fichiers Clés

```
resources/
├── views/           ← TOUTES LES PAGES
├── css/app.css      ← Styles
├── js/app.js        ← Scripts
└── ...

public/
├── build/           ← Assets compilés
├── css/
├── js/
└── photos/

config/
├── app.php          ← Configuration app
├── session.php      ← Sessions
├── mail.php         ← Email
└── ...

tailwind.config.js   ← Tailwind config
vite.config.js       ← Vite config
package.json         ← Dépendances npm
```

---

## Troubleshooting

### Assets ne se compilent pas
```bash
rm -rf node_modules package-lock.json
npm install
npm run build
```

### Hot reload ne fonctionne pas
- Vérifier que `npm run dev` est lancé
- Vérifier le port 5173 n'est pas bloqué
- Vérifier `vite.config.js`

### Styles Tailwind manquants
- Vérifier que `resources/views/**/*.blade.php` sont listés dans `tailwind.config.js`
- Relancer le build

---

**Créé:** 26 novembre 2025
**Version:** 1.0
**Pour:** Reproduction complète du Frontend Al-Amine
