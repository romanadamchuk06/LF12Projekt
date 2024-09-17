<template>
  <div class="weather-app" :style="{ backgroundImage: `url(${backgroundImage})` }">
    <div class="overlay">
      <h1 class="city">{{ city }}</h1>
      <h2 class="description">{{ weather.description }}</h2>
      <p class="temperature">{{ temperature }}°C</p>
      <button @click="promptCity" class="change-city-button">Stadt ändern</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      city: 'Frankfurt',
      weather: {
        description: '',
      },
      temperature: null,
      backgroundImage: '',
      apiKey: '6c1db3aabafab352c05cb6e3674f8ab4',
      unsplashKey: 'NunEEgZRZSR3cb4LXJI4vO4_u73MrapIi9C99D784Hg',
    };
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
        this.temperature = Math.round(data.main.temp);
        this.fetchCityImage();
      } catch (error) {
        alert(error.message);
        console.error('Fehler beim Abrufen der Wetterdaten:', error);
        this.promptCity(); // Wiederhole die Eingabeaufforderung bei Fehler
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
  background-size: cover;
  background-position: center;
  display: flex;
  justify-content: center;
  align-items: center;
  color: white;
  position: relative;
  font-family: 'Arial', sans-serif;
}

.overlay {
  background-color: rgba(0, 0, 0, 0.7);
  padding: 30px;
  border-radius: 15px;
  text-align: center;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

.city {
  font-size: 3rem;
  font-weight: bold;
  margin: 0;
}

.description {
  font-size: 1.5rem;
  margin: 10px 0;
}

.temperature {
  font-size: 2rem;
  font-weight: lighter;
}

.change-city-button {
  margin-top: 20px;
  padding: 10px 20px;
  font-size: 1rem;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.change-city-button:hover {
  background-color: #0056b3;
}
</style>
