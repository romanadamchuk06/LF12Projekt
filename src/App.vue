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
        icon: '',
      },
      temperature: null,
      backgroundImage: '',
      forecast: [],
      apiKey: '6c1db3aabafab352c05cb6e3674f8ab4',
      unsplashKey: 'NunEEgZRZSR3cb4LXJI4vO4_u73MrapIi9C99D784Hg',
      weatherTranslations: {
        'clear sky': 'klarer Himmel',
        'few clouds': 'leicht bewölkt',
        'scattered clouds': 'vereinzelt Wolken',
        'broken clouds': 'aufgelockerte Bewölkung',
        'overcast clouds': 'bedeckt',
        'light rain': 'leichter Regen',
        'moderate rain': 'mäßiger Regen',
        'heavy intensity rain': 'starker Regen',
        'very heavy rain': 'sehr starker Regen',
        'extreme rain': 'extremer Regen',
        'freezing rain': 'gefrierender Regen',
        'light snow': 'leichter Schneefall',
        'snow': 'Schnee',
        'heavy snow': 'starker Schneefall',
        'sleet': 'Schneeregen',
        'light shower snow': 'leichter Schneeschauer',
        'shower snow': 'Schneeschauer',
        'heavy shower snow': 'starker Schneeschauer',
        'mist': 'Nebel',
        'smoke': 'Rauch',
        'haze': 'Dunst',
        'sand, dust whirls': 'Sand-/Staubwirbel',
        'fog': 'dichter Nebel',
        'sand': 'Sand',
        'dust': 'Staub',
        'volcanic ash': 'Vulkanasche',
        'squalls': 'Böen',
        'tornado': 'Tornado',
        'thunderstorm with light rain': 'Gewitter mit leichtem Regen',
        'thunderstorm with rain': 'Gewitter mit Regen',
        'thunderstorm with heavy rain': 'Gewitter mit starkem Regen',
        'light thunderstorm': 'leichtes Gewitter',
        'thunderstorm': 'Gewitter',
        'heavy thunderstorm': 'starkes Gewitter',
        'ragged thunderstorm': 'unbeständiges Gewitter',
        'drizzle': 'Nieselregen',
        'light drizzle': 'leichter Nieselregen',
        'heavy intensity drizzle': 'starker Nieselregen',
        'drizzle rain': 'Nieselregen',
        'heavy intensity drizzle rain': 'starker Nieselregen',
      },
    };
  },
  computed: {
    currentWeatherIcon() {
      const iconMap = {
      'klarer Himmel': 'fas fa-sun',
      'leicht bewölkt': 'fas fa-cloud-sun',
      'vereinzelt Wolken': 'fas fa-cloud',
      'aufgelockerte Bewölkung': 'fas fa-cloud-meatball',
      'bedeckt': 'fas fa-cloud',
      'leichter Regen': 'fas fa-cloud-showers-heavy',
      'mäßiger Regen': 'fas fa-cloud-rain',
      'starker Regen': 'fas fa-cloud-rain',
      'sehr starker Regen': 'fas fa-cloud-rain',
      'extremer Regen': 'fas fa-cloud-rain',
      'gefrierender Regen': 'fas fa-cloud-showers-heavy',
      'leichter Schneefall': 'fas fa-snowflake',
      'Schnee': 'fas fa-snowflake',
      'starker Schneefall': 'fas fa-snowflake',
      'Schneeregen': 'fas fa-cloud-showers-heavy',
      'leichter Schneeschauer': 'fas fa-cloud-snow',
      'Schneeschauer': 'fas fa-cloud-snow',
      'starker Schneeschauer': 'fas fa-cloud-snow',
      'Nebel': 'fas fa-smog',
      'Rauch': 'fas fa-smog',
      'Dunst': 'fas fa-smog',
      'Sand-/Staubwirbel': 'fas fa-wind',
      'dichter Nebel': 'fas fa-smog',
      'Sand': 'fas fa-wind',
      'Staub': 'fas fa-wind',
      'Vulkanasche': 'fas fa-cloud-meatball',
      'Böen': 'fas fa-wind',
      'Tornado': 'fas fa-wind',
      'Gewitter mit leichtem Regen': 'fas fa-bolt',
      'Gewitter mit Regen': 'fas fa-bolt',
      'Gewitter mit starkem Regen': 'fas fa-bolt',
      'leichtes Gewitter': 'fas fa-bolt',
      'Gewitter': 'fas fa-bolt',
      'starkes Gewitter': 'fas fa-bolt',
      'unbeständiges Gewitter': 'fas fa-bolt',
      'Nieselregen': 'fas fa-cloud-showers-heavy',
      'leichter Nieselregen': 'fas fa-cloud-showers-heavy',
      'starker Nieselregen': 'fas fa-cloud-showers-heavy',
    };
      return iconMap[this.weather.description];
    },
    iconMapping() {
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
    translateWeather(description) {
      return this.weatherTranslations[description] || description;
    },
    formatCityName(city) {
      return city
        .toLowerCase()
        .split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
    },
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
        this.weather.description = this.translateWeather(data.weather[0].description);
        this.weather.icon = data.weather[0].icon;
        this.temperature = Math.round(data.main.temp);
        this.fetchCityImage();
        this.fetchForecast();
      } catch (error) {
        alert(error.message);
        console.error('Fehler beim Abrufen der Wetterdaten:', error);
        this.promptCity();
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
        this.forecast = data.list
          .filter((_, index) => index % 8 === 0)
          .map(day => ({
            date: new Date(day.dt * 1000).toLocaleDateString(),
            description: this.translateWeather(day.weather[0].description),
            temp: Math.round(day.main.temp),
            icon: `fas fa-${this.iconMapping[day.weather[0].icon]}`,
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
        }
      } catch (error) {
        console.error('Fehler beim Abrufen des Stadtbildes:', error);
      }
    },
    promptCity() {
      let inputCity = '';
      // Schleife bis eine gültige Eingabe erfolgt
      while (!inputCity || inputCity.trim() === '') {
        inputCity = prompt('Bitte geben Sie eine Stadt ein:');
        if (inputCity === null) {
          alert('Die Eingabe ist erforderlich!');
        }
      }
      this.city = this.formatCityName(inputCity);
      this.fetchWeather();
    },
  },
  mounted() {
    this.promptCity();
  },
};
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html, body {
  width: 100vw;
  height: 100vh;
  overflow: hidden;
}

.weather-app {
  width: 100vw;
  height: 100vh;
  background: linear-gradient(135deg, #6e45e2, #88d3ce);
  background-size: cover;
  background-position: center;
  display: flex;
  justify-content: center;
  align-items: center;
  color: white;
  position: relative;
  font-family: 'Poppins', sans-serif;
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
  justify-content: space-around;
}

.forecast-day {
  width: 100px;
  text-overflow: ellipsis;
  overflow: hidden; 
  text-align: center;
}

.forecast-date {
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: 10px;
}

.forecast-icon {
  font-size: 2rem;
  margin-bottom: 10px;
}

.forecast-description {
  font-size: 1rem;
  margin-bottom: 5px;
}

.forecast-temperature {
  font-size: 1.2rem;
  font-weight: 300;
}
</style>
