<template>
  <div v-if="user" class="profile-container">
    <div class="profile-header">
      <h1>Mon Profil</h1>
      <p>Bienvenue, {{ user.prenom }} {{ user.nom }}</p>
    </div>

    <div class="section-card">
      <h2>Informations Personnelles</h2>
      <div class="user-info">
        <p><strong>Nom :</strong> {{ user.nom }}</p>
        <p><strong>Prénom :</strong> {{ user.prenom }}</p>
        <p><strong>Email :</strong> {{ user.email }}</p>
        <p><strong>Téléphone :</strong> {{ user.telephone }}</p>
      </div>
      <button class="btn-edit">Modifier les informations</button>
    </div>

    <div class="section-card">
      <h2>Mes Réservations</h2>

      <h3 class="subsection-title">Réservations à venir</h3>
      <div v-if="upcomingReservations.length" class="reservation-list">
        <div v-for="reservation in upcomingReservations" :key="reservation.id" class="reservation-item">
          <p><strong>Période :</strong> {{ formatDate(reservation.startDate) }} au {{ formatDate(reservation.endDate) }}</p>
          <p><strong>Véhicule :</strong> {{ reservation.vehicle.brand }} {{ reservation.vehicle.model }}</p>
          <p><strong>Prix total :</strong> {{ reservation.totalPrice }}€</p>
          <div class="actions">
            <button class="btn-action">Modifier</button>
            <button class="btn-action btn-cancel">Annuler</button>
          </div>
        </div>
      </div>
      <p v-else class="no-reservations">Vous n'avez aucune réservation à venir.</p>

      <h3 class="subsection-title">Historique des réservations</h3>
      <div v-if="pastReservations.length" class="reservation-list">
        <div v-for="reservation in pastReservations" :key="reservation.id" class="reservation-item past-item">
          <p><strong>Période :</strong> {{ formatDate(reservation.startDate) }} au {{ formatDate(reservation.endDate) }}</p>
          <p><strong>Véhicule :</strong> {{ reservation.vehicle.brand }} {{ reservation.vehicle.model }}</p>
          <p><strong>Prix total :</strong> {{ reservation.totalPrice }}€</p>
          <div class="actions">
            <button class="btn-action">Voir les détails</button>
          </div>
        </div>
      </div>
      <p v-else class="no-reservations">Aucune réservation passée.</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import UserServices from '@/services/UserServices';

// Interfaces pour la typographie
interface User {
    prenom: string;
    nom: string;
    email: string;
    telephone: string;
    adresse: string;
    codePostal: string;
    ville: string;
    dateNaissance: string;
}

interface Vehicle {
    brand: string;
    model: string;
}

interface Reservation {
    id: number;
    startDate: string;
    endDate: string;
    totalPrice: number;
    vehicle: Vehicle;
    // Autres champs comme 'status', 'agency', etc.
}

// Données fictives
const user = ref<User>({
    prenom: '',
    nom: '',
    email: '',
    telephone: '',
    adresse: '',
    codePostal: '',
    ville: '',
    dateNaissance: '',
});

const upcomingReservations = ref<Reservation[]>([
    {
        id: 1,
        startDate: '2025-10-20',
        endDate: '2025-10-25',
        totalPrice: 450,
        vehicle: { brand: 'Renault', model: 'Clio' },
    },
    {
        id: 2,
        startDate: '2025-11-15',
        endDate: '2025-11-18',
        totalPrice: 280,
        vehicle: { brand: 'Peugeot', model: '308' },
    },
]);

const pastReservations = ref<Reservation[]>([
    {
        id: 3,
        startDate: '2025-08-01',
        endDate: '2025-08-05',
        totalPrice: 350,
        vehicle: { brand: 'Citroën', model: 'C3' },
    },
]);

// Fonctions utilitaires
const formatDate = (dateString: string): string => {
    const date = new Date(dateString);
    const options: Intl.DateTimeFormatOptions = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('fr-FR', options);
};


onMounted(async () =>{
    // Ici, vous ferez des appels à votre API Symfony pour récupérer :
    // 1. Les informations de l'utilisateur
    user.value = await UserServices.getCurrentUser(); 

    // user.value = UserServices.getCurrentUser();

    // 2. La liste de ses réservations
    // Exemple :
    // const userData = await ReservationService.getUserProfile();
    // user.value = userData;
});
</script>

<style scoped>
/* Styles de base pour la mise en page */
.profile-container {
  max-width: 900px;
  margin: 2rem auto;
  padding: 2rem;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.profile-header {
  text-align: center;
  margin-bottom: 2rem;
}

.section-card {
  background-color: #fff;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  margin-bottom: 1.5rem;
}

h1 {
  font-size: 2.5rem;
  color: #333;
}

h2 {
  font-size: 1.8rem;
  margin-bottom: 1rem;
  color: #555;
  border-bottom: 2px solid #eee;
  padding-bottom: 0.5rem;
}

.user-info p {
  font-size: 1rem;
  line-height: 1.6;
}

.btn-edit {
  background-color: #007bff;
  color: white;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-top: 1rem;
}

.subsection-title {
  font-size: 1.4rem;
  margin-top: 1.5rem;
  margin-bottom: 1rem;
}

.reservation-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.reservation-item {
  border: 1px solid #ddd;
  padding: 1rem;
  border-radius: 6px;
  background-color: #fafafa;
}

.reservation-item p {
  margin: 0.2rem 0;
}

.actions {
  margin-top: 1rem;
}

.btn-action {
  background-color: #6c757d;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-right: 0.5rem;
}

.btn-cancel {
  background-color: #dc3545;
}

.no-reservations {
  color: #888;
  font-style: italic;
}

</style>