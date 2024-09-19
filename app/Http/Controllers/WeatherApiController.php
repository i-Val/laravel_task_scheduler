<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ApiLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherApiController extends Controller
{
    public function getCachedWeather($location)
    {
        // Define the cache key based on the location
       return $cacheKey = 'weather_' . $location;

        // Check if the data is already cached, if not cache it and log it
        $weather = Cache::remember($cacheKey, 3600, function () use ($location) {
            // Fetch data from an external API
            $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
                'q' => $location,
                'appid' => config('services.weather_api_key'), // API key from .env
                'units' => 'metric',
            ]);

            if ($response->successful()) {
                // Log the API response to the database
                ApiLog::create([
                    'location' => $location,
                    'response' => $response->json(),
                    'cached_at' => Carbon::now(),
                ]);

                return $response->json();
            }

            return null;
        });

        // Return an error if the API request or caching fails
        if (!$weather) {
            return response()->json(['error' => 'Unable to fetch weather data'], 500);
        }

        // Return the cached or newly fetched data
        return response()->json($weather);
    }

    public function getLogs($location)
{
    // Fetch the logs from the database for a given location
    $logs = ApiLog::where('location', $location)
                ->orderBy('cached_at', 'desc')
                ->get();

    if ($logs->isEmpty()) {
        return response()->json(['message' => 'No logs found for this location'], 404);
    }

    return response()->json($logs);
}

}
