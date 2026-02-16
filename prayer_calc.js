const adhan = require('adhan');
const args = process.argv.slice(2);

const lat = parseFloat(args[0]);
const lng = parseFloat(args[1]);
const date = new Date(args[2]);

const coordinates = new adhan.Coordinates(lat, lng);
const params = adhan.CalculationMethod.Singapore();
params.madhab = adhan.Madhab.Shafi;

// Ikhtiyat 2 menit agar pas dengan jadwal lokal
params.adjustments.fajr = 2;
params.adjustments.dhuhr = 2;
params.adjustments.asr = 2;
params.adjustments.maghrib = 2;
params.adjustments.isha = 2;

const prayerTimes = new adhan.PrayerTimes(coordinates, date, params);

const result = {
    fajr: prayerTimes.fajr.toISOString(),
    sunrise: prayerTimes.sunrise.toISOString(),
    dhuhr: prayerTimes.dhuhr.toISOString(),
    asr: prayerTimes.asr.toISOString(),
    maghrib: prayerTimes.maghrib.toISOString(),
    isha: prayerTimes.isha.toISOString()
};

console.log(JSON.stringify(result));
