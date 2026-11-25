import Alpine from 'alpinejs';
import './order-cards';
import reservationForm from './reservation-form';

window.Alpine = Alpine;
window.reservationForm = reservationForm;
Alpine.data('reservationForm', reservationForm);

Alpine.start();