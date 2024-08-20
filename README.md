# Transport Price Calculation Service

## Overview

This project is a PHP-based service for calculating transport prices based on distances between cities. It uses various repositories and services to fetch city data, vehicle types, and calculate distances.

## Features

- Calculate transport prices based on distances between cities.
- Fetch city data and vehicle types.
- Calculate distances using Google Maps API.
- Unit and feature tests for the application.
- API KEY based authentication for API endpoints.

## Prerequisites

- PHP 8.3 or higher
- Composer
- Laravel Framework
- MongoDB
- Docker (for Docker-based setup)

## Installation

### Using Docker

The easiest way to run the application is to use Docker. The following steps will guide you through the process:
1. **Clone the repository:**
   ```sh
   git clone https://github.com/romanalisoy/laravel-transport-api.git
   cd transport-price-service
   ```
   
2. **Set up environment variables:**

   Copy the .env.example file to .env and update the necessary environment variables, especially the database and Google API key configurations.
    ```sh
    cp .env.example .env
    ```
   
3. **Build and start the Docker containers:**
   ```sh
    docker compose up --build
   ```
4. **Generate application key:**
    ```sh
    docker-compose exec app php artisan key:generate
    ```

### Using Local Development Environment

The following steps will guide you through the process of setting up the application in a local development environment:

1. **Clone the repository:**
   ```sh
    git clone https://github.com/romanalisoy/laravel-transport-api.git
    cd transport-price-service
   ```

2. **Install dependencies:**
   ```sh
    composer install
   ``` 
3. **Set up environment variables:**

   Copy the .env.example file to .env and update the necessary environment variables, especially the database and Google API key configurations.
    ```sh
    cp .env.example .env
    ```
4. **Generate application key:**
    ```sh
    php artisan key:generate
    ```
5. **Running the Application**

    To start the application, use the following command:
    ```sh
    php artisan serve
    ```

## Usage
The application will be available at http://localhost:8000.  

### Running Tests
To run the unit tests, use the following command:
```sh
php artisan test
```

## API Endpoints
Calculate Transport Price

Endpoint: 
```http request
POST /api/calculate-price
```

Description: Calculates the transport price based on the provided addresses and vehicle types. 

__**Example Request Body:**__
```json
{
    "addresses": [
        {
            "country": "DE",
            "zip": "20095",
            "city": "Hamburg"
        },
        {
            "country": "DE",
            "zip": "80331",
            "city": "Munich"
        }
    ]
}
```

__**Authorization:**__

You need to provide a valid API Key in the X-TOKEN header to access this endpoint. You need to change API_KEY environment variable in .env file to your own API key.
```env
X-TOKEN: {YOUR_API_KEY}
```

__**Example Response Body:**__
```json
[
    {
        "vehicle_type": 3,
        "price": 206.22
    },
    {
        "vehicle_type": 10,
        "price": 399
    },
    {
        "vehicle_type": 11,
        "price": 192.73
    },
    "..."
]
```


## Project Structure
- app/DTOs: Data Transfer Objects used in the application.
- app/Repositories/Contracts: Interfaces for repositories.
- app/Services: Service classes containing business logic.
- tests/Unit: Unit tests for the application.

### **Key Classes and Methods**

- TransportPriceService
    - calculatePrice(CalculateTransportPriceDTO $dto): array Calculates the transport price based on the provided addresses and vehicle types.
    - calculateTotalDistance(array $addresses): float Calculates the total distance between a series of addresses.
- DirectionsService
    - getDistanceBetweenPoints(string $origin, string $destination): float Fetches the distance between two points using the Google Maps API.

- Environment Variables
    - DB_CONNECTION: Database connection type (e.g., mongodb).
    - DB_HOST: Database host.
    - DB_PORT: Database port.
    - DB_DATABASE: Database name.
    - DB_USERNAME: Database username.
    - DB_PASSWORD: Database password.
    - GOOGLE_API_KEY: API key for Google Maps.


### **UML Diagram**

![UML Diagram](https://www.plantuml.com/plantuml/svg/ZLHDZzCm4BttLrYh7hg2QkyveEss73XjnKPS448ccIsZTUp8CmO5n7_7DKbsJAsekIL-7szU9hadrfv3PrQzRbxq2THDxRMbtdbMHuyDgBqsJJ2QqLdr1Fv7V7QZVgzKl1gZYTHCIFWHCYBErvjcL804s4o2a1CFzQDQj-nkAwMzrqE1NplMve34saQTX59htROUY80YyTaupOduPoKI7j13Snw02kbEm9Fa0_1F07jqQ9asn1xjIQsTnnFQjsgwRu4ORIeajnIEkt-XuanP4IvOzcAoNzYBK-JXGcYXB24yTr09vyzVBflOHu-Fq3j2TdwG4izesuTX7leSvdNac3FUYAFRAabT6do2xzWOg8SUSXzHzywOJHGGq79GIREwWsgCaFMtTwqyo9kU3vPoPZQX-mQ-KcZvzM675hlGJUzQc11_VUaAQrV-9BRRTuLLh5GWe6NQqXfSny_V-rKSKL6PcelEFX7YWOEt2LMM-TyWvooDcvXYowT0iVdv5xOxVb3lRTkdxpZ_MUspVz44aYjmV7ogrPtkyQwPiDi-WhCbBSkaREL2vvJtODlGcRy0)
