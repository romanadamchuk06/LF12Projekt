<template>
  <div class="weather-app" :style="{ backgroundImage: `url(${backgroundImage})` }">
    <div class="overlay">
      <div class="current-weather">
        <h1 class="city">{{ city }}</h1>
        <div class="weather-info">
          <i :class="currentWeatherIcon" class="weather-icon"></i>
          <div>
            <h2 class="description">{{ weather.description }}</h2>
            <p class="temperature">{{ temperature }}°C</p>
          </div>
        </div>
        <button @click="promptCity" class="change-city-button">Stadt ändern</button>
      </div>
      <div class="forecast">
        <h3>5-Tage-Vorhersage:</h3>
        <div class="forecast-container">
          <div v-for="(day, index) in forecast" :key="index" class="forecast-day">
            <p class="forecast-date">{{ day.date }}</p>
            <i :class="day.icon" class="forecast-icon"></i>
            <p class="forecast-description">{{ day.description }}</p>
            <p class="forecast-temperature">{{ day.temp }}°C</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  data() {
    return {
      city: '',
      weather: {
        description: '',
        icon: '', // Icon-Klasse für das aktuelle Wetter
      },
      temperature: null,
      backgroundImage: '',
      forecast: [],
      apiKey: '6c1db3aabafab352c05cb6e3674f8ab4',
      unsplashKey: 'NunEEgZRZSR3cb4LXJI4vO4_u73MrapIi9C99D784Hg',
    };
  },
  computed: {
    currentWeatherIcon() {
      // Mappe die Wetterbeschreibung zu Font Awesome Icons
      const iconMap = {
        'clear sky': 'fas fa-sun',
        'few clouds': 'fas fa-cloud-sun',
        'scattered clouds': 'fas fa-cloud',
        'broken clouds': 'fas fa-cloud-meatball',
        'shower rain': 'fas fa-cloud-showers-heavy',
        'rain': 'fas fa-cloud-rain',
        'thunderstorm': 'fas fa-bolt',
        'snow': 'fas fa-snowflake',
        'mist': 'fas fa-smog',
      };
      return iconMap[this.weather.description] || 'fas fa-cloud';
    },
    iconMapping() {
      // Mapping von OpenWeatherMap Icons zu Font Awesome Icons
      return {
        '01d': 'fas fa-sun',
        '01n': 'fas fa-moon',
        '02d': 'fas fa-cloud-sun',
        '02n': 'fas fa-cloud-moon',
        '03d': 'fas fa-cloud',
        '03n': 'fas fa-cloud',
        '04d': 'fas fa-cloud-meatball',
        '04n': 'fas fa-cloud-meatball',
        '09d': 'fas fa-cloud-showers-heavy',
        '09n': 'fas fa-cloud-showers-heavy',
        '10d': 'fas fa-cloud-rain',
        '10n': 'fas fa-cloud-rain',
        '11d': 'fas fa-bolt',
        '11n': 'fas fa-bolt',
        '13d': 'fas fa-snowflake',
        '13n': 'fas fa-snowflake',
        '50d': 'fas fa-smog',
        '50n': 'fas fa-smog',
      };
    }
  },
  methods: {
    async fetchWeather() {
      if (!this.city) return;
      try {
        const response = await fetch(
            `https://api.openweathermap.org/data/2.5/weather?q=${this.city}&appid=${this.apiKey}&units=metric`
        );
        if (!response.ok) {
          throw new Error('Stadt nicht gefunden.');
        }
        const data = await response.json();
        this.weather.description = data.weather[0].description;
        this.weather.icon = data.weather[0].icon; // Icon-Klasse für das aktuelle Wetter
        this.temperature = Math.round(data.main.temp);
        this.fetchCityImage();
        this.fetchForecast(); // Holen Sie sich die Vorhersage nach dem aktuellen Wetter
      } catch (error) {
        alert(error.message);
        console.error('Fehler beim Abrufen der Wetterdaten:', error);
        this.promptCity(); // Wiederhole die Eingabeaufforderung bei Fehler
      }
    },
    async fetchForecast() {
      if (!this.city) return;
      try {
        const response = await fetch(
            `https://api.openweathermap.org/data/2.5/forecast?q=${this.city}&appid=${this.apiKey}&units=metric`
        );
        if (!response.ok) {
          throw new Error('Fehler beim Abrufen der Wettervorhersage.');
        }
        const data = await response.json();
        // Extrahiere die Vorhersage für die nächsten 5 Tage
        this.forecast = data.list
            .filter((_, index) => index % 8 === 0) // Wähle tägliche Vorhersagen aus
            .map(day => ({
              date: new Date(day.dt * 1000).toLocaleDateString(),
              description: day.weather[0].description,
              temp: Math.round(day.main.temp),
              icon: `fas fa-${this.iconMapping[day.weather[0].icon]}`, // Mapping des Icons für die Vorhersage
            }));
      } catch (error) {
        console.error('Fehler beim Abrufen der Wettervorhersage:', error);
      }
    },
    async fetchCityImage() {
      try {
        const response = await fetch(
            `https://api.unsplash.com/search/photos?query=${this.city}&client_id=${this.unsplashKey}`
        );
        if (!response.ok) {
          throw new Error('Fehler beim Abrufen des Stadtbildes.');
        }
        const data = await response.json();
        if (data.results.length > 0) {
          this.backgroundImage = data.results[0].urls.regular;
        } else {
          console.warn('Kein Bild gefunden.');
          // Verwende ein Standardbild oder informiere den Benutzer
        }
      } catch (error) {
        console.error('Fehler beim Abrufen des Stadtbildes:', error);
      }
    },
    promptCity() {
      this.city = prompt('Bitte geben Sie die Stadt ein:');
      if (this.city) {
        this.fetchWeather();
      }
    },
  },
  mounted() {
    this.promptCity();
  },
};
</script>

<style scoped>
.weather-app {
  width: 100vw;
  height: 100vh;
  background: linear-gradient(135deg, #6e45e2, #88d3ce); /* Fallback-Hintergrund */
  background-size: cover;
  background-position: center;
  display: flex;
  justify-content: center;
  align-items: center;
  color: white;
  position: relative;
  font-family: 'Poppins', sans-serif; /* Modernere Schrift */
}

.overlay {
  background-color: rgba(0, 0, 0, 0.7);
  padding: 30px;
  border-radius: 20px;
  text-align: center;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
  max-width: 600px;
  width: 100%;
}

.current-weather {
  margin-bottom: 30px;
}

.weather-info {
  display: flex;
  align-items: center;
  justify-content: center;
}

.weather-icon {
  font-size: 5rem;
  margin-right: 20px;
}

.city {
  font-size: 3.5rem;
  font-weight: 700;
  margin: 0;
}

.description {
  font-size: 1.8rem;
  margin: 10px 0;
  font-weight: 300;
}

.temperature {
  font-size: 3rem;
  font-weight: 300;
}

.change-city-button {
  margin-top: 30px;
  padding: 12px 24px;
  font-size: 1rem;
  background-color: #ff8a00;
  color: white;
  border: none;
  border-radius: 25px;
  cursor: pointer;
  transition: background-color 0.3s, transform 0.3s;
}

.change-city-button:hover {
  background-color: #e07b00;
  transform: scale(1.05);
}

.forecast {
  margin-top: 30px;
}

.forecast h3 {
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 15px;
}

.forecast-container {
  display: flex;
  justify-content: space-between;
}

.forecast-day {
  background-color: rgba(255, 255, 255, 0.1);
  padding: 15px;
  border-radius: 15px;
  width: 100px;
  text-align: center;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
  transition: transform 0.3s;
}

.forecast-day:hover {
  transform: translateY(-5px);
}

.forecast-icon {
  font-size: 2.5rem;
  margin-bottom: 10px;
}

.forecast-date {
  font-size: 1rem;
  font-weight: 600;
}

.forecast-description {
  font-size: 0.9rem;
  margin: 5px 0;
}

.forecast-temperature {
  font-size: 1.2rem;
  font-weight: 500;
}
</style>
