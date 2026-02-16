import './bootstrap';

// Import Adhan
import { Coordinates, CalculationMethod, PrayerTimes, Madhab } from 'adhan';

// Bikin global biar bisa dipanggil di Blade
window.adhan = {
    Coordinates,
    CalculationMethod,
    PrayerTimes,
    Madhab
};
