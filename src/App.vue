<template>
  <div class="weather-app" :style="{ backgroundImage: `url(${backgroundImage})` }">
    <div class="overlay">
      <h1>{{ city }}</h1>
      <h2>{{ weather.description }}</h2>
      <p>{{ temperature }}°C</p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      city: 'Berdiansk', // Stadtname, kann dynamisch gesetzt werden
      weather: {
        description: '',
      },
      temperature: null,
      backgroundImage: '', // Bild der Stadt
      apiKey: '6c1db3aabafab352c05cb6e3674f8ab4', // OpenWeatherMap API Schlüssel
    };
  },
  methods: {
    async fetchWeather() {
      try {
        const response = await fetch(
            `https://api.openweathermap.org/data/2.5/weather?q=${this.city}&appid=${this.apiKey}&units=metric`
        );
        const data = await response.json();
        this.weather.description = data.weather[0].description;
        this.temperature = Math.round(data.main.temp);
        this.fetchCityImage();
      } catch (error) {
        console.error('Fehler beim Abrufen der Wetterdaten:', error);
      }
    },
    async fetchCityImage() {
      // Du kannst einen kostenlosen Dienst wie Unsplash verwenden, um Bilder basierend auf dem Stadtnamen zu laden
      try {
        const response = await fetch(
            `https://api.unsplash.com/search/photos?query=${this.city}&client_id=NunEEgZRZSR3cb4LXJI4vO4_u73MrapIi9C99D784Hg`
        );
        const data = await response.json();
        if (data.results.length > 0) {
          this.backgroundImage = data.results[0].urls.regular;
        }
      } catch (error) {
        console.error('Fehler beim Abrufen des Stadtbildes:', error);
      }
    },
  },
  mounted() {
    this.fetchWeather();
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
}

.overlay {
  background-color: rgba(0, 0, 0, 0.5);
  padding: 20px;
  border-radius: 10px;
  text-align: center;
}
</style>
