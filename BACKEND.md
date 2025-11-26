# Backend - Al-Amine - Guide de Reproduction Complet

## Vue d'ensemble

Ce document est destiné à expliquer à une autre IA (ou développeur) comment reproduire **EXACTEMENT** et **GLOBALEMENT** tout le backend du système Al-Amine.

Le backend est construit avec **Laravel 12** avec **Filament** pour l'admin panel, et utilise PostgreSQL comme base de données.

---

## Architecture Backend

### Structure des fichiers

```
app/
├── Console/                    # Commandes Artisan personnalisées
│   ├── Commands/
│   └── Kernel.php
├── Events/                     # Events Laravel
│   ├── AppointmentScheduled.php
│   ├── ConsultationCreated.php
│   └── ... (autres)
├── Filament/                   # Admin Panel Filament
│   ├── Resources/
│   │   ├── UserResource.php
│   │   ├── PatientResource.php
│   │   ├── ConsultationResource.php
│   │   └── ... (autres)
│   └── Pages/
├── Helpers/                    # Fonctions utilitaires
│   └── helpers.php
├── Http/
│   ├── Controllers/            # Tous les contrôleurs
│   │   ├── Admin/
│   │   ├── Patient/
│   │   ├── Praticien/
│   │   ├── Secretaire/
│   │   ├── Auth/
│   │   ├── ProfileController.php
│   │   └── PaydunyaWebhookController.php
│   ├── Middleware/             # Middlewares (auth, role, etc.)
│   │   ├── CheckRole.php
│   │   └── ... (autres)
│   └── Requests/               # Form Requests (validation)
├── Mail/                       # Notifications par email
│   ├── ConfirmationEmail.php
│   ├── AppointmentConfirmed.php
│   └── ... (autres)
├── Models/                     # Modèles Eloquent
│   ├── User.php
│   ├── Patient.php
│   ├── Praticien.php
│   ├── Consultation.php
│   ├── RendezVous.php
│   ├── Facture.php
│   ├── Paiement.php
│   ├── Conversation.php
│   ├── Message.php
│   ├── AuditTrail.php
│   └── ... (autres 15+ modèles)
├── Notifications/              # Notifications (DB, email, SMS)
│   ├── AppointmentReminder.php
│   └── ... (autres)
├── Providers/                  # Service Providers
│   ├── AppServiceProvider.php
│   ├── AuthServiceProvider.php
│   ├── EventServiceProvider.php
│   └── ... (autres)
├── Services/                   # Services métier
│   ├── PaymentService.php
│   ├── PaydunyaService.php
│   ├── StripeService.php
│   ├── ChatService.php
│   ├── AppointmentService.php
│   └── ... (autres)
└── View/                       # View Composers, Helpers

bootstrap/
├── app.php                     # Bootstrap du conteneur
└── providers.php               # Chargement des providers

config/
├── app.php                     # Configuration app
├── auth.php                    # Configuration auth
├── cache.php                   # Configuration cache
├── database.php                # Configuration DB
├── mail.php                    # Configuration email
├── paydunya.php                # Configuration PayDunya
├── session.php                 # Configuration sessions
├── broadcasting.php            # Configuration Pusher/Reverb
└── ... (autres configs)

database/
├── migrations/                 # Migrations (schéma DB)
│   ├── 2024_xx_xx_create_users_table.php
│   ├── 2024_xx_xx_create_patients_table.php
│   ├── 2024_xx_xx_create_consultations_table.php
│   └── ... (50+ migrations)
├── seeders/                    # Seeders (données test)
│   ├── DatabaseSeeder.php
│   ├── UserSeeder.php
│   ├── PatientSeeder.php
│   └── ... (autres)
└── factories/                  # Model Factories (test data)
    ├── UserFactory.php
    ├── PatientFactory.php
    └── ... (autres)

public/
├── index.php                   # Point d'entrée PHP
├── robots.txt
├── .htaccess
└── ... (assets statiques)

resources/
├── views/                      # Vues Blade (frontend)
│   └── ... (voir FRONTEND.md)
└── ... (CSS, JS)

routes/
├── web.php                     # Routes web (50+ routes)
├── api.php                     # Routes API (si existantes)
├── auth.php                    # Routes authentification
├── channels.php                # Broadcasting channels
└── patient-stripe.php          # Routes Stripe

storage/
├── app/                        # Stockage fichiers
│   └── public/                 # Photos, documents
├── framework/                  # Framework files
└── logs/                       # Fichiers logs

tests/
├── Feature/                    # Tests fonctionnels
├── Unit/                       # Tests unitaires
└── TestCase.php                # Base test case

vendor/                         # Dépendances Composer
```

---

## Stack Technologique Backend

### Dependencies Principales (composer.json)

| Package | Version | Rôle |
|---------|---------|------|
| **laravel/framework** | ^12.0 | Framework principal |
| **filament/filament** | ^4.2 | Admin Panel UI |
| **laravel/sanctum** | ^4.2 | API Authentication |
| **laravel/reverb** | ^1.6 | WebSocket Broadcasting |
| **stripe/stripe-php** | ^15.0 | Intégration Stripe |
| **barryvdh/laravel-dompdf** | ^3.1 | Génération PDF |
| **laravel/tinker** | ^2.10.1 | CLI REPL |

### Dev Dependencies

| Package | Version | Rôle |
|---------|---------|------|
| **phpunit/phpunit** | ^11.5.3 | Testing framework |
| **laravel/pail** | ^1.2.2 | Log viewer |
| **laravel/sail** | ^1.41 | Docker environment |
| **fakerphp/faker** | ^1.23 | Fake data generation |
| **mockery/mockery** | ^1.6 | Mocking library |

---

## Base de Données

### Configuration PostgreSQL

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=al-amine
DB_USERNAME=postgres
DB_PASSWORD=1964
```

### Migrations (Schéma)

Les migrations définissent la structure de toutes les tables:

#### 1. Utilisateurs
```
users
├── id (PRIMARY KEY)
├── name
├── email (UNIQUE)
├── password (hashed bcrypt)
├── role (ENUM: PATIENT, PRATICIEN, SECRETAIRE, ADMIN)
├── phone
├── is_active
├── email_verified_at
├── remember_token
└── timestamps
```

#### 2. Patients
```
patients
├── id
├── user_id (FK → users)
├── date_naissance
├── sexe
├── groupe_sanguin
├── telephone
├── adresse
├── ville
├── code_postal
├── entreprise
├── numero_assurance
├── compagnie_assurance
├── allergies
├── antecedents
├── photo_url
└── timestamps
```

#### 3. Praticiens
```
praticiens
├── id
├── user_id (FK → users)
├── numero_licence
├── specialite_id (FK → specialites)
├── bio
├── photo_url
├── telephone
├── adresse
├── tarif_consultation
├── is_available
└── timestamps
```

#### 4. Secrétaires
```
secretaires
├── id
├── user_id (FK → users)
├── praticien_id (FK → praticiens, nullable)
├── telephone
└── timestamps
```

#### 5. Services/Spécialités
```
specialites
├── id
├── nom
├── description
├── icone
└── timestamps
```

#### 6. Rendez-Vous
```
rendez_vous
├── id
├── patient_id (FK → patients)
├── praticien_id (FK → praticiens)
├── date_heure
├── duree_minutes
├── statut (ENUM: PLANIFIE, EN_COURS, TERMINE, ANNULE)
├── notes
├── lieu
├── type (CONSULTATION, SUIVI)
└── timestamps
```

#### 7. Demandes de RDV
```
demande_rdv
├── id
├── patient_id (FK → patients)
├── praticien_id (FK → praticiens, nullable)
├── specialite_id (FK → specialites, nullable)
├── date_souhaitee
├── motif
├── urgence (BOOLEAN)
├── statut (ENUM: EN_ATTENTE, ACCEPTEE, REFUSEE, REPROGRAMMEE)
├── notes_secretaire
└── timestamps
```

#### 8. Consultations
```
consultations
├── id
├── rendez_vous_id (FK → rendez_vous)
├── diagnostic
├── observations
├── recommandations
├── examens_demandes
├── poids
├── taille
├── tension
├── temperature
└── timestamps
```

#### 9. Ordonnances
```
ordonnances
├── id
├── consultation_id (FK → consultations)
├── medicaments (JSON: ['nom', 'dosage', 'duree', 'instructions'])
├── instructions_generales
├── statut (ENUM: ACTIVE, EXPIR, VALIDEE)
└── timestamps
```

#### 10. Examens
```
examens
├── id
├── consultation_id (FK → consultations)
├── type (ENUM: SANGUIN, RADIOLOGIE, ECHOGRAPHIE, ETC)
├── description
├── resultats (TEXT/PDF)
├── fichier_url
├── date_examen
├── date_resultats
└── timestamps
```

#### 11. Documents Médicaux
```
documents_medicaux
├── id
├── patient_id (FK → patients)
├── type (ENUM: ORDONNANCE, EXAMEN, CERTIFICAT, ATTESTATION, AUTRE)
├── titre
├── fichier_url
├── date_document
└── timestamps
```

#### 12. Disponibilités
```
disponibilites
├── id
├── praticien_id (FK → praticiens)
├── jour_semaine (0-6)
├── heure_debut
├── heure_fin
├── intervalle_minutes (30, 45, 60)
└── timestamps
```

#### 13. Factures
```
factures
├── id
├── patient_id (FK → patients)
├── consultation_id (FK → consultations, nullable)
├── numero_facture (UNIQUE)
├── date_facture
├── date_echeance
├── montant_total
├── montant_paye
├── statut (ENUM: BROUILLON, ENVOYEE, PAYEE, IMPAYEE, ANNULEE)
├── methode_paiement (ENUM: PAYDUNYA, STRIPE, ESPECES, CHEQUE)
└── timestamps
```

#### 14. Paiements
```
paiements
├── id
├── facture_id (FK → factures)
├── montant
├── date_paiement
├── methode (ENUM: PAYDUNYA, STRIPE, VIREMENT, ESPECES)
├── transaction_id
├── statut (ENUM: INITIE, SUCCES, ECHEC, REMBOURSEMENT)
├── reference_gateway
└── timestamps
```

#### 15. Mesures de Santé
```
mesures_sante
├── id
├── patient_id (FK → patients)
├── type (ENUM: POIDS, TENSION, GLUCOSE, TEMPERATURE, CHOLESTEROL, etc)
├── valeur
├── unite
├── date_mesure
├── notes
└── timestamps
```

#### 16. Conversations (Chat)
```
conversations
├── id
├── nom (nullable)
├── created_by (FK → users)
├── archived_at
└── timestamps
```

#### 17. Participants de Conversation
```
conversation_participants
├── id
├── conversation_id (FK → conversations)
├── user_id (FK → users)
├── derniere_lecture_at
└── timestamps
```

#### 18. Messages
```
messages
├── id
├── conversation_id (FK → conversations)
├── user_id (FK → users)
├── contenu (TEXT)
├── type (TEXT, IMAGE, FILE)
├── lue (BOOLEAN)
├── date_lecture (nullable)
└── timestamps
```

#### 19. Pièces Jointes de Message
```
message_attachments
├── id
├── message_id (FK → messages)
├── fichier_url
├── type (ENUM: IMAGE, PDF, DOCUMENT, AUDIO)
└── timestamps
```

#### 20. Pistes d'Audit
```
audit_trails
├── id
├── user_id (FK → users)
├── action (CREATE, UPDATE, DELETE, VIEW, DOWNLOAD)
├── model (Nom du modèle concerné)
├── model_id
├── ancienne_valeur (JSON)
├── nouvelle_valeur (JSON)
├── ip_address
├── user_agent
└── timestamps
```

#### 21. Templates de Relance
```
reminder_templates
├── id
├── nom
├── type (EMAIL, SMS)
├── contenu
├── delai_jours
└── timestamps
```

#### 22. Logs de Relance
```
reminder_logs
├── id
├── template_id (FK → reminder_templates)
├── user_id (FK → users)
├── date_envoi
├── statut (ENVOYEE, ECHEC)
└── timestamps
```

---

## Modèles Eloquent

### User Model
```php
// Attributs
- name, email, password, role, phone, is_active
- email_verified_at, remember_token

// Relations
- patient()        → One-to-One → Patient
- praticien()      → One-to-One → Praticien
- secretaire()     → One-to-One → Secretaire
- consultations()  → Many-to-Many
- conversations()  → Many-to-Many
- messages()       → One-to-Many → Message

// Scopes
- byRole('PATIENT')
- isActive()
- hasRole('ADMIN')

// Méthodes
- getInitialsAttribute()
- getFullNameAttribute()
```

### Patient Model
```php
// Attributs
- user_id, date_naissance, sexe, groupe_sanguin
- telephone, adresse, ville, code_postal
- entreprise, numero_assurance, compagnie_assurance
- allergies, antecedents, photo_url

// Relations
- user()                   → One-to-One → User
- rendezVous()            → One-to-Many → RendezVous
- consultations()         → Through RendezVous
- factures()              → One-to-Many → Facture
- paiements()             → Through Facture
- documents()             → One-to-Many → DocumentMedical
- mesuresSante()          → One-to-Many → MesureSante
- conversations()         → Many-to-Many → Conversation

// Scopes
- withUpcomingAppointments()
- withPendingInvoices()

// Méthodes
- getAgeAttribute()
- hasUnpaidInvoices()
- getNextAppointment()
```

### Praticien Model
```php
// Attributs
- user_id, numero_licence, specialite_id
- bio, photo_url, telephone, adresse
- tarif_consultation, is_available

// Relations
- user()              → One-to-One → User
- specialite()        → One-to-Many → Specialite
- rendezVous()        → One-to-Many → RendezVous
- consultations()     → Through RendezVous
- disponibilites()    → One-to-Many → Disponibilite
- secretaires()       → One-to-Many → Secretaire
- patients()          → Many-to-Many (through RendezVous)

// Méthodes
- getNextAvailableSlot()
- hasAvailability($date)
- getSchedule()
```

### Consultation Model
```php
// Attributs
- rendez_vous_id, diagnostic, observations
- recommandations, examens_demandes
- poids, taille, tension, temperature

// Relations
- rendezVous()   → One-to-One → RendezVous
- ordonnance()   → One-to-One → Ordonnance
- examens()      → One-to-Many → Examen
- factures()     → One-to-Many → Facture

// Méthodes
- createInvoice()
- createOrdonnance()
- addExamRequest()
```

### RendezVous Model
```php
// Attributs
- patient_id, praticien_id, date_heure
- duree_minutes, statut, notes, lieu, type

// Relations
- patient()      → One-to-Many → Patient
- praticien()    → One-to-Many → Praticien
- consultation() → One-to-One → Consultation (nullable)

// Scopes
- upcoming()
- past()
- byStatus($statut)

// Méthodes
- cancel()
- reschedule($newDateTime)
- confirm()
- start()
- complete()
```

### Facture Model
```php
// Attributs
- patient_id, consultation_id, numero_facture
- date_facture, date_echeance, montant_total
- montant_paye, statut, methode_paiement

// Relations
- patient()      → One-to-Many → Patient
- consultation() → One-to-One → Consultation
- paiements()    → One-to-Many → Paiement

// Scopes
- unpaid()
- overdue()
- byStatus($statut)

// Méthodes
- markAsPaid()
- calculateRemaining()
- generatePDF()
- sendEmail()
```

### Conversation & Message Models
```php
// Conversation
- nom, created_by, archived_at
- Relation: participants() → Many-to-Many → User
- Relation: messages() → One-to-Many → Message

// Message
- conversation_id, user_id, contenu, type
- lue, date_lecture
- Relation: attachments() → One-to-Many → MessageAttachment
```

### AuditTrail Model
```php
// Attributs
- user_id, action, model, model_id
- ancienne_valeur (JSON), nouvelle_valeur (JSON)
- ip_address, user_agent

// Relation
- user() → One-to-Many → User
```

---

## Contrôleurs

### Admin Controllers (`app/Http/Controllers/Admin/`)

#### AdminDashboardController
- **index()** - Dashboard admin (stats globales)
- **agendasGlobaux()** - Tous les agendas
- **services()** - Gestion des services
- **storeService()** - Créer service
- **destroyService()** - Supprimer service
- **rapports()** - Page rapports
- **audit()** - Journal d'audit
- **rapportActivite()** - Rapport d'activité (stats)
- **rapportFinancier()** - Rapport financier

#### UserController (Resource)
- **index()** - Liste utilisateurs
- **create()** - Formulaire création
- **store()** - Sauvegarder nouvel utilisateur
- **show()** - Détails utilisateur
- **edit()** - Formulaire édition
- **update()** - Sauvegarder modifications
- **destroy()** - Supprimer utilisateur

---

### Patient Controllers (`app/Http/Controllers/Patient/`)

#### PatientDashboardController
- **index()** - Dashboard patient
- **mesRdv()** - Liste RDV du patient
- **showRendezVous()** - Détails RDV
- **annulerRdv()** - Annuler RDV
- **reprogrammerRdv()** - Formulaire reprogrammation
- **updateRdv()** - Sauvegarder new RDV
- **factures()** - Liste factures
- **showFacture()** - Détails facture
- **paiement()** - Page paiement
- **traiterPaiement()** - Traiter paiement (PayDunya/Stripe)

#### PatientProfileController
- **edit()** - Formulaire profil
- **updatePersonalInfo()** - Infos personnelles
- **updateHealthInfo()** - Infos santé
- **updateInsuranceInfo()** - Infos assurance
- **updatePhoto()** - Upload photo
- **deletePhoto()** - Supprimer photo
- **updatePassword()** - Changer mot de passe

#### DemandeRdvController
- **create()** - Formulaire demande
- **store()** - Sauvegarder demande
- **index()** - Mes demandes (historique)

#### DossierMedicalController
- **index()** - Liste consultations
- **showConsultation()** - Détails consultation
- **downloadOrdonnance()** - Télécharger PDF ordonnance
- **downloadExamen()** - Télécharger PDF examen
- **downloadDocument()** - Télécharger document
- **allergiesAntecedents()** - Voir/modifier allergies
- **updateAllergiesAntecedents()** - Sauvegarder allergies

#### PatientNotificationController
- **index()** - Notifications
- **markAsRead()** - Marquer comme lue
- **markAllAsRead()** - Tout marquer comme lu
- **destroy()** - Supprimer notification
- **getUnreadCount()** - Nombre non lues

#### PatientChatController
- **index()** - Page messagerie
- **conversations()** - Liste conversations
- **show()** - Voir conversation
- **storeConversation()** - Créer conversation
- **storeMessage()** - Ajouter message
- **markAsRead()** - Marquer conversation lue
- **archive()** - Archiver conversation

#### PatientCalendrierController
- **index()** - Vue calendrier
- **getEvents()** - JSON events (AJAX)
- **exportIcal()** - Export iCal
- **exportGoogle()** - Export Google Calendar

#### PatientPaiementController
- **index()** - Historique paiements
- **show()** - Détails paiement
- **downloadRecu()** - Télécharger reçu

#### PatientDocumentController
- **index()** - Liste documents
- **downloadDocument()** - Télécharger document
- **downloadOrdonnance()** - Télécharger ordonnance
- **downloadExamen()** - Télécharger examen
- **generateCertificat()** - Générer certificat
- **generateAttestation()** - Générer attestation

#### PatientSuiviSanteController
- **index()** - Suivi santé (lectures seule)

---

### Praticien Controllers (`app/Http/Controllers/Praticien/`)

#### PraticienDashboardController
- **index()** - Dashboard praticien
- **profile()** - Profil du praticien
- **patients()** - Mes patients
- **dossierPatient()** - Dossier d'un patient
- **documents()** - Mes documents générés
- **agenda()** - Mon agenda

#### DisponibiliteController
- **index()** - Mes disponibilités
- **store()** - Ajouter disponibilités

#### ConsultationController
- **index()** - Consultations en attente
- **show()** - Détails consultation
- **store()** - Sauvegarder consultation (diagnostic, etc.)
- **ordonnance()** - Formulaire ordonnance
- **storeOrdonnance()** - Sauvegarder ordonnance

#### RendezVousController
- **reschedule()** - Replanifier RDV

#### PraticienChatController
- **index()** - Messagerie
- **conversations()** - Conversations
- **show()** - Voir conversation
- **storeConversation()** - Créer conversation
- **storeMessage()** - Ajouter message
- **markAsRead()** - Marquer lue

---

### Secretaire Controllers (`app/Http/Controllers/Secretaire/`)

#### SecretaireDashboardController
- **index()** - Dashboard secrétaire
- **agendas()** - Vue agendas
- **multiAgenda()** - Planning multi-praticiens
- **agendaEvents()** - JSON events
- **agendaReplanifier()** - Replanifier RDV
- **agendaPraticien()** - Agenda d'un praticien
- **facturation()** - Page facturation
- **genererFacture()** - Générer facture
- **storeFacture()** - Sauvegarder facture
- **encaissements()** - Historique encaissements

#### FileAttenteController
- **index()** - Demandes en attente
- **valider()** - Valider demande
- **refuser()** - Refuser demande

#### SecretaireRendezVousController
- **showReprogrammation()** - Formulaire reprogrammation
- **storeReprogrammation()** - Sauvegarder reprogrammation
- **confirm()** - Confirmer RDV
- **cancel()** - Annuler RDV

#### ReminderController
- **index()** - Templates relance
- **updateTemplate()** - Modifier template
- **send()** - Envoyer relance

#### SecretaireProfileController
- **edit()** - Formulaire profil
- **update()** - Sauvegarder profil
- **updatePassword()** - Changer mot de passe
- **destroy()** - Supprimer profil

#### SecretaireChatController
- **index()** - Messagerie
- **conversations()** - Conversations
- **show()** - Voir conversation
- **storeConversation()** - Créer conversation
- **storeMessage()** - Ajouter message
- **markAsRead()** - Marquer lue

---

### Autres Contrôleurs

#### PaydunyaWebhookController
- **handleIPN()** - Webhook IPN PayDunya
- **paymentReturn()** - Return après paiement
- **paymentCancel()** - Annulation paiement

#### ProfileController (Authentifié)
- **edit()** - Éditer profil
- **update()** - Sauvegarder profil
- **destroy()** - Supprimer profil

---

## Services

### Services Métier (`app/Services/`)

#### PaymentService
```php
// Méthodes principales
- processPayment($facture, $method) → Boolean
- verifyPayment($transaction_id) → Boolean
- refundPayment($paiement) → Boolean
- getPaymentStatus($transaction_id) → String
```

#### PaydunyaService
```php
// Méthodes principales
- createCheckout($facture) → CheckoutURL
- verifyTransaction($token) → Array
- handleWebhook($data) → Boolean
- generateSignature($data) → String
```

#### StripeService
```php
// Méthodes principales
- createPaymentIntent($facture) → PaymentIntent
- handleWebhook($event) → Boolean
- refund($payment_intent_id) → Boolean
```

#### ChatService
```php
// Méthodes principales
- createConversation($users) → Conversation
- sendMessage($conversation, $user, $content) → Message
- markAsRead($conversation, $user) → Boolean
- archiveConversation($conversation) → Boolean
```

#### AppointmentService
```php
// Méthodes principales
- requestAppointment($patient, $praticien, $date) → DemandeRdv
- scheduleAppointment($demande, $date_time) → RendezVous
- cancelAppointment($rdv) → Boolean
- rescheduleAppointment($rdv, $new_date) → RendezVous
- getAvailableSlots($praticien, $date) → Array
- sendReminders() → void (job)
```

#### NotificationService
```php
// Méthodes principales
- sendAppointmentReminder($rdv) → Boolean
- sendConsultationConfirmation($consultation) → Boolean
- sendInvoiceEmail($facture) → Boolean
- sendPaymentReceipt($paiement) → Boolean
```

#### ReportService
```php
// Méthodes principales
- getActivityReport($date_start, $date_end) → Array
- getFinancialReport($date_start, $date_end) → Array
- getConsultationStats() → Array
- getPraticienStats($praticien) → Array
```

#### PDFService
```php
// Méthodes principales
- generateInvoicePDF($facture) → PDF
- generateOrdonnancePDF($ordonnance) → PDF
- generateExamPDF($examen) → PDF
- generateAttestationPDF($consultation) → PDF
```

#### AuditService
```php
// Méthodes principales
- logAction($action, $model, $model_id, $old_data, $new_data) → void
- getAuditTrail($filters) → Collection
- exportAuditTrail($date_start, $date_end) → CSV/PDF
```

---

## Events & Listeners

### Events

```php
// app/Events/

AppointmentScheduled          // RDV planifié
ConsultationCreated           // Consultation enregistrée
InvoiceGenerated              // Facture générée
PaymentProcessed              // Paiement traité
AppointmentReminder           // Rappel RDV (job)
MessageSent                   // Message envoyé
UserCreated                   // Utilisateur créé
```

### Listeners

```php
// app/Listeners/

SendAppointmentNotification       // → AppointmentScheduled
GenerateInvoice                   // → ConsultationCreated
SendPaymentReceipt                // → PaymentProcessed
SendMessageNotification           // → MessageSent
```

---

## Middleware

### Authentification & Autorisation

```php
// app/Http/Middleware/

CheckRole                         // Vérifier rôle utilisateur
Authenticate                      // Vérifier authentification
CheckIfEmailVerified              // Email vérifié
BlockSuspendedUser                // Utilisateur actif
CheckAppointmentAccess            // Accès aux RDV
CheckPatientDocumentAccess        // Accès aux documents
```

---

## Mail (Notifications Email)

### Mailable Classes (`app/Mail/`)

```php
ConfirmationEmail               // Confirmation inscription
AppointmentConfirmed           // Confirmation RDV
AppointmentReminder            // Rappel RDV (24h avant)
ConsultationSummary            // Résumé consultation
InvoiceMailed                  // Envoi facture
PaymentReceipt                 // Reçu paiement
PasswordReset                  // Réinitialisation mot de passe
AppointmentCancelled           // Annulation RDV
AppointmentRescheduled         // Reprogrammation RDV
```

### Configuration

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=birakanembodj01@gmail.com
MAIL_PASSWORD=clok bcet gtjf rvyn
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=birakanembodj01@gmail.com
MAIL_FROM_NAME=Hôpital Al-Amine
```

---

## Broadcasting (WebSocket en temps réel)

### Configuration

```
BROADCAST_CONNECTION=reverb
BROADCAST_DRIVER=pusher
REVERB_APP_ID=alamine-chat
REVERB_APP_KEY=localkey
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
```

### Channels (`routes/channels.php`)

```php
// Private channels (authentifiés)
Channel::private('user.{id}')              // Notifications utilisateur
Channel::private('conversation.{id}')      // Messages conversation

// Presence channels
Channel::presence('agendas')               // Agendas en temps réel
```

### Broadcasting Events

```php
// app/Events/

MessageSent implements ShouldBroadcast
- broadcast on channel('conversation.{id}')

AppointmentUpdated implements ShouldBroadcast
- broadcast on channel('agendas')

NotificationSent implements ShouldBroadcast
- broadcast on channel('user.{id}')
```

---

## Jobs & Queues

### Queue Configuration

```
QUEUE_CONNECTION=sync  (dev)
QUEUE_CONNECTION=database  (production)
```

### Jobs (`app/Jobs/`)

```php
SendAppointmentReminders        // Cron job (quotidien)
SendPaymentReminders            // Cron job (quotidien)
ProcessPaymentRefund            // Refund asynchrone
GenerateMonthlyReport           // Rapport mensuel
CleanupOldLogs                  // Nettoyage logs
```

---

## Authentification

### Configuration (`config/auth.php`)

```php
Guards:
- web (SESSION)
- sanctum (API tokens)

Providers:
- users (User model)
```

### Authentication Flow

1. Login → EmailPassword verification
2. Password hashing (bcrypt, 12 rounds)
3. Session créée (DB store)
4. Role-based redirects
5. Remember me (14 jours)

### Password Reset

- Email avec token unique
- Lien valide 60 minutes
- Token hashé en base de données

---

## Permissions & Rôles

### Rôles Définis

```
PATIENT      → Accès patient dashboard, RDV, profil, dossier médical
PRATICIEN    → Accès praticien dashboard, consultations, patients
SECRETAIRE   → Accès gestion agendas, facturation, file d'attente
ADMIN        → Accès complet (users, rapports, audit, services)
```

### Vérification Rôle (Middleware)

```php
Route::middleware('role:PATIENT')->group(function () {
    // Routes protégées pour patients
});
```

---

## Configuration Globale

### `config/app.php`
```
APP_NAME=Al-Amine
APP_DEBUG=true (dev) / false (prod)
APP_LOCALE=en
BCRYPT_ROUNDS=12
```

### `config/database.php`
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=al-amine
```

### `config/cache.php`
```
CACHE_STORE=database
```

### `config/session.php`
```
SESSION_DRIVER=database
SESSION_LIFETIME=120 (minutes)
```

### `config/paydunya.php`
```php
return [
    'mode' => 'test',
    'master_key' => env('PAYDUNYA_MASTER_KEY'),
    'private_key' => env('PAYDUNYA_PRIVATE_KEY'),
    'public_key' => env('PAYDUNYA_PUBLIC_KEY'),
    'currency' => 'XOF',
    'timeout' => 15,
];
```

---

## Seeders & Factories

### DatabaseSeeder
```php
// Crée données de test
- 1 Admin user
- 5 Praticiens
- 10 Patients
- 20 RDV
- 30 Consultations
```

### Factories

```php
UserFactory              → Génère users aléatoires
PatientFactory           → Génère patients aléatoires
PraticienFactory         → Génère praticiens aléatoires
ConsultationFactory      → Génère consultations aléatoires
RendezVousFactory        → Génère RDV aléatoires
FactureFactory           → Génère factures aléatoires
```

---

## Routes

### Routes Web (`routes/web.php`)

#### Public
```
GET  /                           → landing page
GET  /login                      → login form
POST /login                      → process login
GET  /register                   → register form
POST /register                   → process register
```

#### Redirects
```
GET  /dashboard                  → redirect par rôle
```

#### Payment
```
POST /paydunya/ipn              → webhook PayDunya
GET  /paydunya/return           → return PayDunya
GET  /paydunya/cancel           → cancel PayDunya
```

#### Patient Routes (50+ routes)
```
patient.dashboard
patient.profile.edit/update/password
patient.dossier-medical
patient.demander-rdv
patient.mes-demandes
patient.mes-rdv
patient.factures
patient.paiement
patient.notifications
patient.messagerie.*
patient.calendrier
patient.documents
patient.suivi-sante
patient.paiements
```

#### Praticien Routes (30+ routes)
```
praticien.dashboard
praticien.profile
praticien.patients
praticien.patient.dossier
praticien.documents
praticien.agenda
praticien.messages.*
praticien.disponibilites
praticien.consultations
praticien.consultation.show
praticien.ordonnance
```

#### Secretaire Routes (25+ routes)
```
secretaire.dashboard
secretaire.profile
secretaire.file-attente
secretaire.demande.*
secretaire.agendas
secretaire.agendas.multi
secretaire.facturation
secretaire.encaissements
secretaire.relances
secretaire.messages.*
```

#### Admin Routes (15+ routes)
```
admin.dashboard
admin.users.*               (CRUD)
admin.agendas-globaux
admin.services
admin.rapports
admin.audit
admin.rapport.*
```

---

## Installation & Setup Complet

### Pré-requis
- PHP ^8.2
- PostgreSQL ^12
- Composer ^2.0
- Node.js ^16.0

### 1. Installation

```bash
cd backend

# Installer dépendances PHP
composer install

# Copier .env.example
cp .env.example .env

# Générer key
php artisan key:generate

# Configurer .env (BD, mail, paiements)
# DB_HOST, DB_PASSWORD, MAIL_*, PAYDUNYA_*, STRIPE_*

# Créer BD PostgreSQL
createdb al-amine

# Migrations
php artisan migrate
php artisan migrate --seed

# Installer dépendances frontend
npm install

# Build assets
npm run build
```

### 2. Configuration des Webhooks

**PayDunya:**
```
IPN URL:     https://yourdomain.com/paydunya/ipn
Return URL:  https://yourdomain.com/paydunya/return
Cancel URL:  https://yourdomain.com/paydunya/cancel
```

**Stripe:**
```
Webhook URL: https://yourdomain.com/stripe/webhook
```

### 3. Démarrage

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev

# Terminal 3: Queue listener
php artisan queue:listen

# Terminal 4: Broadcasting
php artisan reverb:start

# Terminal 5: Logs
php artisan pail
```

### 4. Premier Accès

```
Admin:
Email: admin@alamine.com
Password: password

Patient:
Email: patient@alamine.com
Password: password

Praticien:
Email: doctor@alamine.com
Password: password

Secretaire:
Email: secretary@alamine.com
Password: password
```

---

## Tests

### Exécuter les tests

```bash
# Tous les tests
php artisan test

# Tests spécifiques
php artisan test tests/Feature/Auth/LoginTest.php
php artisan test tests/Unit/Models/PatientTest.php

# Avec coverage
php artisan test --coverage
```

### Fichiers de test

```
tests/
├── Feature/
│   ├── Auth/
│   ├── Patient/
│   ├── Praticien/
│   ├── Secretaire/
│   └── Admin/
└── Unit/
    ├── Models/
    └── Services/
```

---

## Filament Admin Panel

### Installation
```bash
php artisan filament:install
php artisan filament:install --panels=admin
```

### Resources
```
app/Filament/Resources/
├── UserResource.php
├── PatientResource.php
├── PraticienResource.php
├── ConsultationResource.php
├── FactureResource.php
└── ... (autres)
```

### Accès
```
URL: /admin
```

### Fonctionnalités
- Gestion complète des utilisateurs
- CRUD pour tous les modèles
- Filtres et recherche
- Bulk actions
- Export CSV/PDF

---

## Commands Artisan

### Commandes personnalisées

```bash
# Créer utilisateurs de test
php artisan create:test-users

# Envoyer rappels RDV
php artisan appointments:send-reminders

# Nettoyer données anciennes
php artisan cleanup:old-data

# Générer rapports mensuels
php artisan reports:generate-monthly

# Rafraîchir cache
php artisan cache:clear
php artisan cache:forget
```

---

## Logs & Debugging

### Configuration Logs

```
LOG_CHANNEL=stack
LOG_LEVEL=debug (dev) / warning (prod)
```

### Fichiers Logs
```
storage/logs/laravel.log
```

### Debugging Tools
```
php artisan tinker              # CLI REPL
php artisan pail                # Log viewer
php artisan debug:routes        # Routes list
```

---

## Sécurité

### Protections
- ✅ CSRF tokens
- ✅ SQL injection prevention (Eloquent)
- ✅ XSS protection (Blade escaping)
- ✅ Rate limiting
- ✅ Password hashing (bcrypt)
- ✅ Session security (database)
- ✅ Role-based access
- ✅ Audit logging

### Bonnes pratiques
- Utiliser middleware pour vérifier rôles
- Ne jamais trucker secret keys en base visible
- Valider toutes les inputs (Form Requests)
- Utiliser prepared statements (Eloquent)
- HTTPS en production
- Keep Laravel updated

---

## Dépannage

### BD ne se connecte pas
```bash
# Vérifier BD
psql -h localhost -U postgres -d al-amine

# Recréer migrations
php artisan migrate:fresh --seed
```

### Erreurs permissions
```bash
chmod -R 775 storage/ bootstrap/cache/
chown -R www-data:www-data storage/
```

### Cache problèmes
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## Points Clés à Reproduire

✅ **22 Modèles** avec relations complexes
✅ **50+ Routes** web avec authentification par rôles
✅ **15+ Contrôleurs** pour tous les rôles
✅ **10+ Services** métier (paiements, emails, etc.)
✅ **Database complète** (22 tables avec migrations)
✅ **Authentification** (login, register, password reset)
✅ **Permissions** basées sur rôles
✅ **Broadcasting** temps réel (WebSocket)
✅ **Email** (Swift Mailer, SMTP Gmail)
✅ **Paiements** (PayDunya + Stripe)
✅ **PDFs** (DomPDF)
✅ **Audit logging** complet
✅ **Tests** unitaires et fonctionnels
✅ **Filament Admin Panel**
✅ **Jobs & Queues** asynchrones

---

## Structure des Fichiers Clés

```
app/
├── Models/                    ← 22 modèles
├── Http/Controllers/          ← 15+ contrôleurs
├── Services/                  ← 8+ services
├── Mail/                      ← 10+ mailables
├── Events/                    ← Broadcasting events
├── Jobs/                      ← Queue jobs
├── Listeners/                 ← Event listeners
├── Filament/Resources/        ← Admin panel
└── Providers/                 ← Service providers

config/
├── app.php
├── database.php
├── mail.php
├── paydunya.php               ← PayDunya config
├── broadcasting.php           ← WebSocket config
└── ... (autres)

database/
├── migrations/                ← 50+ migrations
├── seeders/                   ← Data seeders
└── factories/                 ← Model factories

routes/
├── web.php                    ← 150+ routes
├── auth.php                   ← Auth routes
├── channels.php               ← Broadcasting
└── patient-stripe.php         ← Stripe routes

storage/
├── logs/
└── app/public/                ← Fichiers uploadés
```

---

## Résumé Complet

| Aspect | Détail | Nombre |
|--------|--------|--------|
| **Modèles** | Eloquent ORM models | 22 |
| **Routes** | Web routes | 150+ |
| **Contrôleurs** | Avec actions | 15+ |
| **Services** | Business logic | 10+ |
| **Migrations** | Database tables | 50+ |
| **Tests** | Unit + Feature | 30+ |
| **Mailable** | Email templates | 10+ |
| **Events** | Brodcast events | 8+ |
| **Rôles** | User roles | 4 |
| **Tables BD** | PostgreSQL | 22 |
| **Dependencies** | Composer packages | 20+ |

---

**Créé:** 26 novembre 2025
**Version:** 1.0
**Pour:** Reproduction complète du Backend Al-Amine
