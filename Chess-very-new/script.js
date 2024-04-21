function getWeather() {
    const city = document.getElementById('cityInput').value;
    fetch(`weather.php?city=${city}`)
        .then(response => response.json())
        .then(data => {
            if (data.cod === 200) {
                const weatherDesc = data.weather[0].description;
                const temp = data.main.temp;
                const humidity = data.main.humidity;
                const windSpeed = data.wind.speed;
                const weatherInfo = `Weather in ${city}:<br>
                    Description: ${weatherDesc}<br>
                    Temperature: ${temp}°C<br>
                    Humidity: ${humidity}%<br>
                    Wind Speed: ${windSpeed} m/s`;
                document.getElementById('weatherInfo').innerHTML = weatherInfo;
            } else {
                document.getElementById('weatherInfo').innerHTML = 'City not found or error in retrieving data.';
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}
