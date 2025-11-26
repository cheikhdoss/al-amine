# CONVERSION - Full Stack React + Laravel API

## Vue d'ensemble

Ce document explique comment transformer le projet Al-Amine actuel (Laravel Blade monolithique) en une **vraie architecture Full Stack moderne**:
- **Frontend**: React (Vite + TypeScript + Tailwind)
- **Backend**: Laravel 12 (API REST pure)
- **Communication**: API JSON + REST

---

## Situation Actuelle vs Cible

### ❌ Situation Actuelle (Monolithe)
```
┌─────────────────────────────────────┐
│      Laravel Monolithe              │
├─────────────────────────────────────┤
│  • Blade Views (HTML)               │
│  • Controllers (Logic)              │
│  • Routes Web                       │
│  • Vite Build (CSS/JS)              │
│  • Sessions & Cookies               │
│  • Files in public/                 │
└─────────────────────────────────────┘
          ↓
    http://localhost:8000
```

### ✅ Situation Cible (Full Stack)
```
┌──────────────────────────┐         ┌──────────────────────────┐
│   React Frontend         │         │   Laravel Backend API    │
├──────────────────────────┤         ├──────────────────────────┤
│ • React Components       │         │ • API Routes             │
│ • State Management       │         │ • Controllers            │
│ • TypeScript             │         │ • Models & Migrations    │
│ • Tailwind CSS           │         │ • Services               │
│ • API Calls (Axios)      │         │ • Database               │
│ • Error Handling         │         │ • Authentication (JWT)   │
├──────────────────────────┤         ├──────────────────────────┤
│ Vite + React Router      │         │ Sanctum/JWT Tokens      │
│ Port: 5173               │         │ Port: 8000               │
└──────────────────────────┘         └──────────────────────────┘
        ↓ (HTTP API)
   JSON ←→ REST
```

---

## Étape 1: Préparer le Backend (API REST)

### 1.1 Restructurer les Routes

#### Avant (routes/web.php):
```php
Route::get('/patient/dashboard', [PatientDashboardController::class, 'index'])->name('patient.dashboard');
// Retourne une vue Blade
```

#### Après (routes/api.php):
```php
Route::middleware('auth:sanctum')->group(function () {
    // Patient Routes
    Route::prefix('patient')->group(function () {
        Route::get('/dashboard', [PatientDashboardController::class, 'index']);
        Route::get('/mes-rdv', [PatientDashboardController::class, 'mesRdv']);
        Route::post('/rdv/{rdv}/annuler', [PatientDashboardController::class, 'annulerRdv']);
        // ... autres routes
    });
    
    // Praticien Routes
    Route::prefix('praticien')->group(function () {
        Route::get('/dashboard', [PraticienDashboardController::class, 'index']);
        Route::get('/consultations', [ConsultationController::class, 'index']);
        // ...
    });
    
    // ... autres rôles
});
```

### 1.2 Modifier les Contrôleurs

#### Avant (retour Blade):
```php
public function index()
{
    $patient = auth()->user()->patient;
    $rdv = $patient->rendezVous()->with('praticien')->get();
    return view('patient.dashboard', compact('rdv'));
}
```

#### Après (retour JSON):
```php
public function index()
{
    $patient = auth()->user()->patient;
    $rdv = $patient->rendezVous()
        ->with('praticien')
        ->get()
        ->map(fn($r) => $r->toArray()); // ou Resource
    
    return response()->json([
        'success' => true,
        'data' => $rdv,
        'message' => 'Dashboard patient récupéré'
    ]);
}
```

### 1.3 Utiliser des API Resources (Laravel)

Créer `app/Http/Resources/`:

```php
// app/Http/Resources/RendezVousResource.php
class RendezVousResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date_heure' => $this->date_heure,
            'statut' => $this->statut,
            'duree_minutes' => $this->duree_minutes,
            'praticien' => new PraticienResource($this->praticien),
            'patient' => new PatientResource($this->patient),
        ];
    }
}

// Usage dans le contrôleur:
public function index()
{
    $rdv = RendezVous::with('praticien', 'patient')->get();
    return RendezVousResource::collection($rdv);
}
```

### 1.4 Configuration CORS

```php
// config/cors.php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_methods' => ['*'],
'allowed_origins' => [
    'http://localhost:5173',     // Dev
    'http://localhost:3000',     // Alt
    'https://alamine.com',       // Production
],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => true,
```

### 1.5 Authentification JWT (Sanctum)

```php
// config/sanctum.php
'expiration' => 60 * 24 * 365,  // 1 an pour les web apps

// app/Http/Controllers/Auth/LoginController.php
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    
    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
    
    $user = Auth::user();
    $token = $user->createToken('api-token')->plainTextToken;
    
    return response()->json([
        'token' => $token,
        'user' => new UserResource($user),
    ]);
}

// Logout
public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Logged out']);
}
```

### 1.6 Endpoints API à Créer

**Authentification:**
```
POST   /api/auth/login
POST   /api/auth/register
POST   /api/auth/logout
GET    /api/auth/me
POST   /api/auth/refresh-token
```

**Patient:**
```
GET    /api/patient/dashboard
GET    /api/patient/mes-rdv
POST   /api/patient/demander-rdv
GET    /api/patient/dossier-medical
GET    /api/patient/factures
POST   /api/patient/paiement
GET    /api/patient/profile
PATCH  /api/patient/profile
GET    /api/patient/notifications
POST   /api/patient/notifications/{id}/mark-read
```

**Praticien:**
```
GET    /api/praticien/dashboard
GET    /api/praticien/mes-patients
GET    /api/praticien/consultations
POST   /api/praticien/consultation/{id}
POST   /api/praticien/ordonnance
GET    /api/praticien/disponibilites
POST   /api/praticien/disponibilites
```

**Secrétaire:**
```
GET    /api/secretaire/dashboard
GET    /api/secretaire/demandes
POST   /api/secretaire/demande/{id}/valider
POST   /api/secretaire/demande/{id}/refuser
GET    /api/secretaire/agendas
POST   /api/secretaire/facture
```

**Admin:**
```
GET    /api/admin/dashboard
GET    /api/admin/users
POST   /api/admin/users
PATCH  /api/admin/users/{id}
DELETE /api/admin/users/{id}
GET    /api/admin/rapports
GET    /api/admin/audit
```

---

## Étape 2: Créer le Frontend React

### 2.1 Structure du Projet React

```bash
# Créer projet React
npm create vite@latest frontend -- --template react

cd frontend
npm install
```

### 2.2 Structure des Dossiers

```
frontend/
├── public/
│   ├── index.html
│   └── favicon.svg
├── src/
│   ├── components/           # Composants réutilisables
│   │   ├── auth/
│   │   │   ├── LoginForm.jsx
│   │   │   ├── RegisterForm.jsx
│   │   │   └── ProtectedRoute.jsx
│   │   ├── common/
│   │   │   ├── Navbar.jsx
│   │   │   ├── Sidebar.jsx
│   │   │   ├── Card.jsx
│   │   │   ├── Modal.jsx
│   │   │   ├── Button.jsx
│   │   │   └── Table.jsx
│   │   ├── patient/
│   │   │   ├── Dashboard.jsx
│   │   │   ├── Profile.jsx
│   │   │   ├── DossierMedical.jsx
│   │   │   ├── MesRdv.jsx
│   │   │   ├── DemanderRdv.jsx
│   │   │   ├── Factures.jsx
│   │   │   └── Paiement.jsx
│   │   ├── praticien/
│   │   │   ├── Dashboard.jsx
│   │   │   ├── MesPatientsJSX
│   │   │   ├── Consultations.jsx
│   │   │   ├── Ordonnance.jsx
│   │   │   └── Disponibilites.jsx
│   │   ├── secretaire/
│   │   │   ├── Dashboard.jsx
│   │   │   ├── FileAttente.jsx
│   │   │   ├── Agendas.jsx
│   │   │   └── Facturation.jsx
│   │   └── admin/
│   │       ├── Dashboard.jsx
│   │       ├── UserManagement.jsx
│   │       ├── Rapports.jsx
│   │       └── Audit.jsx
│   ├── pages/              # Pages principales
│   │   ├── LoginPage.jsx
│   │   ├── RegisterPage.jsx
│   │   ├── LandingPage.jsx
│   │   ├── Dashboard.jsx
│   │   └── NotFoundPage.jsx
│   ├── hooks/              # Hooks personnalisés
│   │   ├── useAuth.js
│   │   ├── useFetch.js
│   │   ├── useForm.js
│   │   └── useNotification.js
│   ├── services/           # API calls
│   │   ├── api.js          # Axios instance
│   │   ├── authService.js
│   │   ├── patientService.js
│   │   ├── praticienService.js
│   │   ├── secretaireService.js
│   │   └── adminService.js
│   ├── context/            # Context API
│   │   ├── AuthContext.jsx
│   │   ├── NotificationContext.jsx
│   │   └── AppContext.jsx
│   ├── store/              # Zustand/Redux (optionnel)
│   │   ├── authStore.js
│   │   └── uiStore.js
│   ├── utils/              # Utilitaires
│   │   ├── constants.js
│   │   ├── helpers.js
│   │   └── formatters.js
│   ├── styles/
│   │   ├── globals.css
│   │   └── variables.css
│   ├── App.jsx
│   ├── App.css
│   ├── main.jsx
│   └── index.css
├── .env.example
├── .env.local
├── package.json
├── vite.config.js
├── tailwind.config.js
└── postcss.config.js
```

### 2.3 Installation Dépendances React

```bash
npm install react-router-dom          # Routing
npm install axios                     # HTTP client
npm install zustand                   # State management (optionnel)
npm install @react-query/react-query  # Data fetching (optionnel)
npm install react-hot-toast           # Notifications
npm install tailwindcss @tailwindcss/forms
npm install -D typescript

# Optional mais recommandé
npm install react-icons              # Icons
npm install clsx                     # Conditional classNames
npm install date-fns                 # Date formatting
npm install react-select             # Advanced selects
npm install recharts                 # Charts/Graphs
```

### 2.4 Configuration Vite (React)

```javascript
// vite.config.js
import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import tailwindcss from 'tailwindcss'
import autoprefixer from 'autoprefixer'

export default defineConfig({
  plugins: [react()],
  server: {
    port: 5173,
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      }
    }
  },
  css: {
    postcss: {
      plugins: [tailwindcss, autoprefixer],
    },
  },
})
```

### 2.5 Axios Instance (API Client)

```javascript
// src/services/api.js
import axios from 'axios'

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'

const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  }
})

// Interceptor: ajouter token
api.interceptors.request.use(
  config => {
    const token = localStorage.getItem('auth_token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  error => Promise.reject(error)
)

// Interceptor: gérer erreurs
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401) {
      localStorage.removeItem('auth_token')
      window.location.href = '/login'
    }
    return Promise.reject(error)
  }
)

export default api
```

### 2.6 Auth Context

```javascript
// src/context/AuthContext.jsx
import { createContext, useState, useEffect } from 'react'
import api from '../services/api'

export const AuthContext = createContext()

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)

  // Récupérer l'utilisateur courant au démarrage
  useEffect(() => {
    const token = localStorage.getItem('auth_token')
    if (token) {
      fetchUser()
    } else {
      setLoading(false)
    }
  }, [])

  const fetchUser = async () => {
    try {
      const response = await api.get('/auth/me')
      setUser(response.data.data)
      setError(null)
    } catch (err) {
      setError(err.message)
      localStorage.removeItem('auth_token')
    } finally {
      setLoading(false)
    }
  }

  const login = async (email, password) => {
    setLoading(true)
    try {
      const response = await api.post('/auth/login', { email, password })
      const { token, user: userData } = response.data.data
      localStorage.setItem('auth_token', token)
      setUser(userData)
      setError(null)
      return userData
    } catch (err) {
      setError(err.response?.data?.message || err.message)
      throw err
    } finally {
      setLoading(false)
    }
  }

  const register = async (data) => {
    setLoading(true)
    try {
      const response = await api.post('/auth/register', data)
      const { token, user: userData } = response.data.data
      localStorage.setItem('auth_token', token)
      setUser(userData)
      setError(null)
      return userData
    } catch (err) {
      setError(err.response?.data?.message || err.message)
      throw err
    } finally {
      setLoading(false)
    }
  }

  const logout = async () => {
    try {
      await api.post('/auth/logout')
    } catch (err) {
      console.error('Logout error:', err)
    } finally {
      localStorage.removeItem('auth_token')
      setUser(null)
    }
  }

  return (
    <AuthContext.Provider value={{ user, loading, error, login, register, logout }}>
      {children}
    </AuthContext.Provider>
  )
}
```

### 2.7 Hook useAuth

```javascript
// src/hooks/useAuth.js
import { useContext } from 'react'
import { AuthContext } from '../context/AuthContext'

export const useAuth = () => {
  const context = useContext(AuthContext)
  if (!context) {
    throw new Error('useAuth doit être utilisé dans AuthProvider')
  }
  return context
}
```

### 2.8 Protected Route

```javascript
// src/components/auth/ProtectedRoute.jsx
import { Navigate } from 'react-router-dom'
import { useAuth } from '../../hooks/useAuth'

export const ProtectedRoute = ({ 
  children, 
  requiredRoles = [] 
}) => {
  const { user, loading } = useAuth()

  if (loading) {
    return <div>Chargement...</div>
  }

  if (!user) {
    return <Navigate to="/login" replace />
  }

  if (requiredRoles.length > 0 && !requiredRoles.includes(user.role)) {
    return <Navigate to="/unauthorized" replace />
  }

  return children
}
```

### 2.9 Router Setup

```javascript
// src/App.jsx
import { BrowserRouter, Routes, Route } from 'react-router-dom'
import { AuthProvider } from './context/AuthContext'
import { ProtectedRoute } from './components/auth/ProtectedRoute'

// Pages
import LandingPage from './pages/LandingPage'
import LoginPage from './pages/LoginPage'
import RegisterPage from './pages/RegisterPage'

// Patient
import PatientDashboard from './components/patient/Dashboard'
import PatientProfile from './components/patient/Profile'
import MesRdv from './components/patient/MesRdv'
import DossierMedical from './components/patient/DossierMedical'

// Praticien
import PraticienDashboard from './components/praticien/Dashboard'
import MesPatients from './components/praticien/MesPatients'
import Consultations from './components/praticien/Consultations'

// Secrétaire
import SecretaireDashboard from './components/secretaire/Dashboard'
import FileAttente from './components/secretaire/FileAttente'

// Admin
import AdminDashboard from './components/admin/Dashboard'
import UserManagement from './components/admin/UserManagement'

function App() {
  return (
    <BrowserRouter>
      <AuthProvider>
        <Routes>
          {/* Public */}
          <Route path="/" element={<LandingPage />} />
          <Route path="/login" element={<LoginPage />} />
          <Route path="/register" element={<RegisterPage />} />

          {/* Patient */}
          <Route
            path="/patient/dashboard"
            element={
              <ProtectedRoute requiredRoles={['PATIENT']}>
                <PatientDashboard />
              </ProtectedRoute>
            }
          />
          <Route
            path="/patient/profile"
            element={
              <ProtectedRoute requiredRoles={['PATIENT']}>
                <PatientProfile />
              </ProtectedRoute>
            }
          />
          <Route
            path="/patient/mes-rdv"
            element={
              <ProtectedRoute requiredRoles={['PATIENT']}>
                <MesRdv />
              </ProtectedRoute>
            }
          />
          <Route
            path="/patient/dossier-medical"
            element={
              <ProtectedRoute requiredRoles={['PATIENT']}>
                <DossierMedical />
              </ProtectedRoute>
            }
          />

          {/* Praticien */}
          <Route
            path="/praticien/dashboard"
            element={
              <ProtectedRoute requiredRoles={['PRATICIEN']}>
                <PraticienDashboard />
              </ProtectedRoute>
            }
          />
          <Route
            path="/praticien/mes-patients"
            element={
              <ProtectedRoute requiredRoles={['PRATICIEN']}>
                <MesPatients />
              </ProtectedRoute>
            }
          />
          <Route
            path="/praticien/consultations"
            element={
              <ProtectedRoute requiredRoles={['PRATICIEN']}>
                <Consultations />
              </ProtectedRoute>
            }
          />

          {/* Secrétaire */}
          <Route
            path="/secretaire/dashboard"
            element={
              <ProtectedRoute requiredRoles={['SECRETAIRE']}>
                <SecretaireDashboard />
              </ProtectedRoute>
            }
          />
          <Route
            path="/secretaire/file-attente"
            element={
              <ProtectedRoute requiredRoles={['SECRETAIRE']}>
                <FileAttente />
              </ProtectedRoute>
            }
          />

          {/* Admin */}
          <Route
            path="/admin/dashboard"
            element={
              <ProtectedRoute requiredRoles={['ADMIN']}>
                <AdminDashboard />
              </ProtectedRoute>
            }
          />
          <Route
            path="/admin/users"
            element={
              <ProtectedRoute requiredRoles={['ADMIN']}>
                <UserManagement />
              </ProtectedRoute>
            }
          />

          {/* 404 */}
          <Route path="*" element={<div>Page non trouvée</div>} />
        </Routes>
      </AuthProvider>
    </BrowserRouter>
  )
}

export default App
```

### 2.10 Exemple Component Patient Dashboard

```javascript
// src/components/patient/Dashboard.jsx
import { useState, useEffect } from 'react'
import { useAuth } from '../../hooks/useAuth'
import api from '../../services/api'

export default function PatientDashboard() {
  const { user } = useAuth()
  const [rdv, setRdv] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)

  useEffect(() => {
    fetchDashboard()
  }, [])

  const fetchDashboard = async () => {
    try {
      setLoading(true)
      const response = await api.get('/patient/dashboard')
      setRdv(response.data.data.mes_rdv)
      setError(null)
    } catch (err) {
      setError(err.message)
    } finally {
      setLoading(false)
    }
  }

  if (loading) return <div>Chargement...</div>
  if (error) return <div>Erreur: {error}</div>

  return (
    <div className="p-6">
      <h1 className="text-3xl font-bold mb-6">
        Bienvenue, {user?.name}
      </h1>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <Card title="RDV à venir" value={rdv.filter(r => r.statut === 'PLANIFIE').length} />
        <Card title="Consultations" value={rdv.length} />
        <Card title="Factures impayées" value="0" />
      </div>

      <div className="bg-white rounded-lg shadow p-6">
        <h2 className="text-xl font-bold mb-4">Mes rendez-vous</h2>
        <table className="w-full">
          <thead>
            <tr className="border-b">
              <th className="text-left py-2">Date</th>
              <th className="text-left py-2">Praticien</th>
              <th className="text-left py-2">Statut</th>
              <th className="text-left py-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            {rdv.map(r => (
              <tr key={r.id} className="border-b hover:bg-gray-50">
                <td className="py-2">{new Date(r.date_heure).toLocaleDateString()}</td>
                <td className="py-2">{r.praticien.user.name}</td>
                <td className="py-2">
                  <span className={`px-2 py-1 rounded text-sm font-medium ${
                    r.statut === 'PLANIFIE' ? 'bg-green-100 text-green-800' :
                    r.statut === 'ANNULE' ? 'bg-red-100 text-red-800' :
                    'bg-blue-100 text-blue-800'
                  }`}>
                    {r.statut}
                  </span>
                </td>
                <td className="py-2">
                  <button className="text-blue-600 hover:underline">Détails</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}

function Card({ title, value }) {
  return (
    <div className="bg-white rounded-lg shadow p-6">
      <p className="text-gray-600 text-sm">{title}</p>
      <p className="text-3xl font-bold text-gray-800">{value}</p>
    </div>
  )
}
```

### 2.11 Services API (Patient)

```javascript
// src/services/patientService.js
import api from './api'

export const patientService = {
  getDashboard: () => api.get('/patient/dashboard'),
  
  getMesRdv: () => api.get('/patient/mes-rdv'),
  
  getRdvDetail: (id) => api.get(`/patient/mes-rdv/${id}`),
  
  annulerRdv: (id) => api.post(`/patient/rdv/${id}/annuler`),
  
  reprogrammerRdv: (id, data) => api.patch(`/patient/rdv/${id}/reprogrammer`, data),
  
  demanderRdv: (data) => api.post('/patient/demander-rdv', data),
  
  getDossierMedical: () => api.get('/patient/dossier-medical'),
  
  getConsultation: (id) => api.get(`/patient/consultation/${id}`),
  
  getFactures: () => api.get('/patient/factures'),
  
  getFacture: (id) => api.get(`/patient/facture/${id}`),
  
  initiatePaiement: (factureId, method) => 
    api.post(`/patient/paiement/${factureId}`, { method }),
  
  getProfile: () => api.get('/patient/profile'),
  
  updateProfile: (data) => api.patch('/patient/profile', data),
  
  getNotifications: () => api.get('/patient/notifications'),
  
  markNotificationRead: (id) => api.post(`/patient/notifications/${id}/mark-read`),
}
```

---

## Étape 3: Configuration WebSocket (Temps Réel)

### 3.1 Frontend - Socket.io ou Reverb

```javascript
// src/services/websocket.js
import io from 'socket.io-client'

const socket = io(import.meta.env.VITE_WS_URL || 'http://localhost:8080', {
  auth: {
    token: localStorage.getItem('auth_token')
  }
})

socket.on('connect', () => {
  console.log('WebSocket connected')
})

socket.on('disconnect', () => {
  console.log('WebSocket disconnected')
})

export default socket
```

### 3.2 Écouter Messages en Temps Réel

```javascript
// src/components/patient/Messagerie.jsx
import { useEffect, useState } from 'react'
import socket from '../../services/websocket'

export default function Messagerie() {
  const [messages, setMessages] = useState([])

  useEffect(() => {
    // Rejoindre la room de conversation
    socket.emit('join_conversation', { conversation_id: conversationId })

    // Écouter les nouveaux messages
    socket.on('new_message', (message) => {
      setMessages(prev => [...prev, message])
    })

    return () => {
      socket.off('new_message')
    }
  }, [])

  const sendMessage = (content) => {
    socket.emit('send_message', {
      conversation_id: conversationId,
      content: content
    })
  }

  return (
    // JSX pour afficher messages
  )
}
```

### 3.3 Backend Broadcasting (Laravel)

```php
// app/Events/MessageSent.php
class MessageSent implements ShouldBroadcast
{
    public function broadcastOn()
    {
        return new Channel('conversation.' . $this->message->conversation_id);
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message->toArray(),
            'user' => $this->message->user->toArray(),
        ];
    }
}
```

---

## Étape 4: Fichiers Statiques & CDN

### 4.1 Stocker Images Utilisateurs

```php
// Backend: Sauvegarder upload
public function updatePhoto(Request $request)
{
    $request->validate(['photo' => 'image|max:2048']);
    
    $path = $request->file('photo')->store('photos/users', 's3');
    
    auth()->user()->update(['photo_url' => $path]);
    
    return response()->json(['url' => Storage::disk('s3')->url($path)]);
}
```

```javascript
// Frontend: Upload photo
const uploadPhoto = async (file) => {
  const formData = new FormData()
  formData.append('photo', file)
  
  const response = await api.post('/patient/profile/photo', formData, {
    headers: { 'Content-Type': 'multipart/form-data' }
  })
  
  return response.data.url
}
```

### 4.2 AWS S3 / Cloudinary

```php
// config/filesystems.php
'disks' => [
    's3' => [
        'driver' => 's3',
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
        'bucket' => env('AWS_BUCKET'),
        'url' => env('AWS_URL'),
        'endpoint' => env('AWS_ENDPOINT'),
    ],
],
```

---

## Étape 5: Variables d'Environnement

### 5.1 Backend (.env)

```properties
APP_NAME=Al-Amine
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

# Frontend URL (CORS)
FRONTEND_URL=http://localhost:5173

# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=al-amine
DB_USERNAME=postgres
DB_PASSWORD=1964

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost:5173,127.0.0.1:5173

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email@gmail.com
MAIL_PASSWORD=password
MAIL_ENCRYPTION=tls

# Payments
PAYDUNYA_MODE=test
STRIPE_PUBLIC_KEY=pk_...
STRIPE_SECRET_KEY=sk_...

# WebSocket
REVERB_APP_ID=...
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
```

### 5.2 Frontend (.env.local)

```properties
VITE_API_URL=http://localhost:8000/api
VITE_WS_URL=http://localhost:8080
VITE_APP_NAME=Al-Amine
```

---

## Étape 6: Déploiement

### 6.1 Production Build Frontend

```bash
# Frontend
npm run build

# Génère dist/ avec assets optimisés
```

### 6.2 Servir React depuis Nginx

```nginx
server {
    listen 80;
    server_name alamine.com;

    # Frontend React
    location / {
        root /var/www/frontend/dist;
        try_files $uri $uri/ /index.html;
    }

    # Backend API
    location /api {
        proxy_pass http://localhost:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }
}
```

### 6.3 Docker Compose

```yaml
version: '3.8'

services:
  backend:
    build: ./backend
    ports:
      - "8000:8000"
    environment:
      APP_ENV: production
      DB_HOST: postgres
    depends_on:
      - postgres
      - redis

  frontend:
    build: ./frontend
    ports:
      - "3000:3000"
    depends_on:
      - backend

  postgres:
    image: postgres:15
    environment:
      POSTGRES_DB: al-amine
      POSTGRES_PASSWORD: 1964

  redis:
    image: redis:7

  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
    volumes:
      - ./nginx.conf:/etc/nginx/nginx.conf
    depends_on:
      - backend
      - frontend
```

---

## Étape 7: Authentification JWT vs Sessions

### Avantages JWT pour Full Stack

| Aspect | Sessions | JWT |
|--------|----------|-----|
| **Stockage Token** | Server session | localStorage/sessionStorage |
| **Scalabilité** | Nécessite sessions distribuées | Stateless |
| **Sécurité** | Cookies HttpOnly | Bearer token |
| **Mobile/SPA** | ❌ Moins adapté | ✅ Idéal |
| **CSRF** | ✅ Protection native | Nécessite CORS |
| **Refresh** | Automatique | Explicit refresh token |

### Configuration JWT

```php
// config/jwt.php
'secret' => env('JWT_SECRET'),
'algorithm' => 'HS256',
'exp' => 60 * 60 * 24 * 365,  // 1 an
```

```javascript
// React: Gérer token
const setAuthToken = (token) => {
  if (token) {
    api.defaults.headers.Authorization = `Bearer ${token}`
    localStorage.setItem('auth_token', token)
  } else {
    delete api.defaults.headers.Authorization
    localStorage.removeItem('auth_token')
  }
}
```

---

## Étape 8: Migration des Données

### 8.1 Garder la BD PostgreSQL

```bash
# Les migrations Laravel restent identiques
php artisan migrate

# Les seeders population les données
php artisan db:seed
```

### 8.2 Exporter/Importer Données

```bash
# Backup BD
pg_dump -h localhost -U postgres al-amine > backup.sql

# Restore
psql -h localhost -U postgres < backup.sql
```

---

## Résumé de Transformation

### ❌ Avant (Monolithe Blade)
```
Routes/Web.php (50+ routes web)
    ↓
Controllers (retournent Blade views)
    ↓
Views Blade (HTML complet)
    ↓
Navigateur reçoit HTML rendu
```

### ✅ Après (Full Stack React)
```
Routes/API.php (50+ routes API)
    ↓
Controllers (retournent JSON)
    ↓
Resources (formatent les données)
    ↓
Frontend React appelle l'API
    ↓
Navigateur reçoit JSON et rend avec React
```

---

## Architecture Finale

```
┌──────────────────────────────────────────────────────────────┐
│                         FRONTEND                              │
│  (React + Vite + TypeScript + Tailwind)                       │
│  Port: 5173 ou 3000                                           │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  • React Components (Patient/Praticien/Secretaire/Admin)      │
│  • React Router (SPA routing)                                 │
│  • Axios (API calls)                                          │
│  • Context API / Zustand (State management)                   │
│  • Tailwind CSS (Styling)                                     │
│                                                               │
└──────────────────────────────────────────────────────────────┘
                            ↕ REST API / JSON
┌──────────────────────────────────────────────────────────────┐
│                         BACKEND                               │
│  (Laravel 12 + PostgreSQL)                                    │
│  Port: 8000                                                   │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  • API Routes (REST endpoints)                                │
│  • Controllers (Business logic)                               │
│  • Resources (JSON formatting)                                │
│  • Models (Eloquent ORM)                                      │
│  • Migrations (Database schema)                               │
│  • Services (Complex logic)                                   │
│  • Events (Broadcasting)                                      │
│                                                               │
├──────────────────────────────────────────────────────────────┤
│  PostgreSQL Database                                          │
│  • 22 tables                                                  │
│  • Relationships maintained                                   │
└──────────────────────────────────────────────────────────────┘
                            ↕ WebSocket (Reverb)
┌──────────────────────────────────────────────────────────────┐
│                    REAL-TIME (WebSocket)                      │
│  • Chat messages                                              │
│  • Agenda updates                                             │
│  • Notifications                                              │
│  Port: 8080                                                   │
└──────────────────────────────────────────────────────────────┘
```

---

## Checklist de Conversion

### Phase 1: Backend API (1-2 semaines)
- [ ] Convertir routes web → routes API
- [ ] Modifier contrôleurs (retourner JSON)
- [ ] Créer API Resources
- [ ] Configurer JWT/Sanctum
- [ ] Configurer CORS
- [ ] Tester endpoints API avec Postman

### Phase 2: Frontend React (2-3 semaines)
- [ ] Créer structure React
- [ ] Installer dépendances
- [ ] Créer Auth Context & ProtectedRoutes
- [ ] Créer Axios instance
- [ ] Créer components Patient
- [ ] Créer components Praticien
- [ ] Créer components Secretaire
- [ ] Créer components Admin

### Phase 3: Intégration (1 semaine)
- [ ] Connecter Frontend à Backend API
- [ ] Tester flux authentification
- [ ] Tester tous les endpoints
- [ ] Implémenter WebSocket
- [ ] Gestion des erreurs

### Phase 4: Déploiement (1 semaine)
- [ ] Build production Frontend
- [ ] Déployer Backend sur serveur
- [ ] Déployer Frontend (Vercel/Netlify/AWS)
- [ ] Tests en production
- [ ] Monitoring & logs

---

## Fichiers à Modifier/Créer

### Modifier Backend
```
routes/api.php                          ← Nouveau fichier
app/Http/Controllers/*/DashboardController.php
app/Http/Resources/                     ← Nouveaux fichiers
config/cors.php
config/sanctum.php
.env
```

### Créer Frontend
```
frontend/                               ← Nouveau dossier
├── src/
│   ├── components/                     ← Tous les components
│   ├── pages/                          ← Pages
│   ├── hooks/                          ← Hooks
│   ├── services/                       ← API services
│   ├── context/                        ← Contexts
│   └── App.jsx
├── vite.config.js
├── tailwind.config.js
└── package.json
```

---

## Temps Estimé

| Tâche | Durée |
|-------|-------|
| Backend API | 1-2 semaines |
| Frontend React | 2-3 semaines |
| Intégration | 1 semaine |
| Tests | 1 semaine |
| Déploiement | 1 semaine |
| **TOTAL** | **6-10 semaines** |

---

## Avantages de cette Architecture

✅ **Séparation des préoccupations** - Frontend et backend indépendants
✅ **Scalabilité** - API peut servir mobile, web, desktop
✅ **Maintenance** - Chaque partie peut être modifiée indépendamment
✅ **Performance** - Frontend peut être en CDN, caching amélioré
✅ **Développement** - Deux équipes peuvent travailler en parallèle
✅ **Testing** - API testable indépendamment du frontend
✅ **Déploiement** - Frontend et backend sur des serveurs différents
✅ **Mobile** - API peut servir une app React Native

---

**Créé:** 26 novembre 2025
**Version:** 1.0
**Pour:** Guide de conversion Laravel Monolithe → Full Stack React + API
