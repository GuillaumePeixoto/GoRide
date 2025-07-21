import axios from "axios";
import AuthService from "@/services/AuthService";

const API_URL = "http://localhost:8000/api";

export default {
    async getVehicules() {
        try {
            const response = await axios.get(API_URL+"/vehicules");
            return response.data;
        } catch (error) {
            console.error("Erreur lors de la récupération des véhicules:", error);
            throw error;
        }
    },

    async addVehicule(vehiculeData: any) {
        try {
            const response = await axios.post(API_URL+"/vehicules", vehiculeData, {
                headers: {
                    Authorization: `Bearer ${AuthService.getToken()}`,
                },
            });
            return response.data;
        } catch (error) {
            console.error("Erreur lors de l'ajout du véhicule:", error);
            throw error;
        }
    },

    async getVehicule(id: number) {
        const response = await axios.get(API_URL+"/vehicule/"+id)
        return response.data
    },

    async updateVehicule(id: number, formData: FormData) {
        return axios.post(API_URL+'/vehicule/'+id+'/edit', formData, {
            headers: { 
                'Content-Type': 'multipart/form-data',
                Authorization: `Bearer ${AuthService.getToken()}`,
            },
        });
    },

    async deleteVehicule(vehiculeId: number) {
        try {
            const response = await axios.delete(API_URL+"/vehicule/"+vehiculeId, {
                headers: {
                    Authorization: `Bearer ${AuthService.getToken()}`,
                },
            });
            return response.data;
        } catch (error) {
            console.error("Erreur lors de l'ajout du véhicule:", error);
            throw error;
        }
    },

    async searchVehicules(filters: { startDate: string; endDate: string; ville: string; type: number; }){
        try {
            const response = await axios.get(API_URL+"/vehicules/search", {
                params: filters,
            });
            return response.data;
        } catch (error) {
            console.error("Erreur lors de la recherche des véhicules:", error);
            throw error;
        }

    },

    getTarifJournalier(jours: number, tarifMax: number) {
        const tarifMin = tarifMax / 2
        const maxJourPalier = 8 // ou un autre seuil de stabilité

        // On diminue progressivement vers le minimum jusqu’à maxJourPalier jours
        const coeff = Math.min(jours, maxJourPalier) / maxJourPalier

        const tarif = tarifMax - (tarifMax - tarifMin) * coeff

        return Math.round(tarif)
    },

    calculateDaysDifference(startInput: string, endInput: string): number {

        const date1 = new Date(startInput);
        const date2 = new Date(endInput);

        if (isNaN(date1.getTime()) || isNaN(date2.getTime())) {
            return 0;
        }

        const diffTime = Math.abs(date2.getTime() - date1.getTime());
        return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    },

    calculateAssuranceTousRisquesPrice(prixJour: number, nbJours: number): number {

        let taux = 0.25;
        if (nbJours >= 7) taux = 0.15;
        else if (nbJours >= 3) taux = 0.20;

        let prix = prixJour * nbJours * taux;

        return parseFloat(prix.toFixed(2));
    },

    calculateTotalPrice(nbDays: number, pricePerDay: number, selectedOptions: string[], optionsList: any) {
        let total = nbDays * pricePerDay;

        selectedOptions.forEach(option => {
            if (optionsList[option]) {
                total += parseFloat(optionsList[option].price.toFixed(2));
            }
        });

        return total;
    },

    getPriceDetails(nbDays: number, pricePerDay: number, selectedOptions: string[], optionsList: any) {
        let priceDetails = [];

        priceDetails.push({
            key: 'vehicule',
            displayName: 'Véhicule',
            description: `${nbDays} jour(s) × ${pricePerDay}€`,
            amount: nbDays * pricePerDay
        });

        selectedOptions.forEach(option => {
            if (optionsList[option]) {
                priceDetails.push({ key: option, displayName: optionsList[option].label, amount: optionsList[option].price });
            }
        });

        return priceDetails;

    },

    getOptionsList(pricePerDay: number) {
        return {
            siegeBebe: { label: 'Siège enfant', description: "7 €/jour, réduit à 5 €/jour pour toute réservation de plus de 7 jours.", price: 7 },
            kilometrageIllimite: { label: 'Kilométrage illimité', description: "Si l’option n’est pas sélectionnée, vous bénéficiez de 200 km par jour inclus. Des frais s’appliquent en cas de dépassement", price: parseFloat(((pricePerDay ?? 0) * 0.05).toFixed(2)) },
            depotAutreAgence: { label: 'Dépôt dans une autre agence', description:"15 € maintenant et 0,40 €/km entre les agences à payer à la restitution du véhicule", price: 15 },
            assuranceTousRisque: { label: 'Assurance tous risques', description:"15 % à 25 % du prix de la location hors option selon la durée.", price: parseFloat(((pricePerDay ?? 0) * 0.25).toFixed(2))  }
        };
    },

    confirmationReservation() {
        const data = localStorage.getItem('reservation');
        let reservationInformation: any = null;
        if (data) {
            reservationInformation = JSON.parse(data)
        } else {
            return "";
        }

        console.log("Confirmation de la réservation :", reservationInformation);
    }
};
