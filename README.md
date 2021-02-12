# Backend tech homework

- Fralik Timur
- Aimprosoft

---

#Way to up the project
1. You have to have php and composer on your local machine  
    - execute:
        - composer install  
    or
2. You can use docker
    - install Docker (https://www.docker.com/)
    - open folder with project in terminal
    - execute:
        - cd docker
        - docker-compose up
        - docker exec -it web bash
        - composer install
    
---


## Receiving result in console

Execute in a project folder
- **php artisan forecast**


## Receiving result in browser (all)

### Step 1: *Serve application*
To serve project locally via built-in PHP development server:
- **php -S localhost:8000 -t public**

### Step 2: *Visit web-page*
Paste url address below in your browser
- **http://localhost:8000/**

-----

# API design (no code required)

- endpoint/s to set the forecast for a specific city  
**POST /api/v3/forecast/{city_id}**
  - body: 
    - 'today' => 'required|string',
    - 'tomorrow' => 'required|string',
  - response:
    - same as from *GET /api/v3/forecast/{city_id}*

  
- endpoint/s to read the forecast for a specific city  
**GET /api/v3/forecast/{city_id}**  
```
"responses": {  
    "200": {
        "description": "Forecast for city",
        "content": {
            "application/json": {            
                "name" : "London",
                "city_id" : 1,
                "today" : "Heavy rain",
                "tomorrow" : "Partly cloudy",
                "time" : "2021-01-01 12:00:00"
            }
        }
    },
    "422": {
        "description": "The city ID is not valid"
    }
}
```
    
**GET /api/v3/forecast/{city_id}/{day}**  
```
"responses": {  
    "200": {
        "description": "Forecast for city by day",
        "content": {
            "application/json": {            
                "name" : "London",
                "city_id" : 1,
                "day" : "2021-01-01",
                "forecast" : "Heavy rain",
                "time" : "2021-01-01 12:00:00"
            }
        }
    },
    "422": {
        "description": "The city ID is not valid"
    }
}
```
    
**GET /api/v3/forecast/today/{city_id}**  
```
"responses": {  
    "200": {
        "description": "Today forecast for city",
        "content": {
            "application/json": {            
                "name" : "London",
                "city_id" : 1,
                "day" : "2021-01-01",
                "forecast" : "Heavy rain",
                "time" : "2021-01-01 12:00:00"
            }
        }
    },
    "422": {
        "description": "The city ID is not valid"
    }
}
```

**GET /api/v3/forecast/tomorrow/{city_id}**
```
"responses": {  
    "200": {
        "description": "Tomorrow forecast for the city",
        "content": {
            "application/json": {            
                "name" : "London",
                "city_id" : 1,
                "day" : "2021-01-01",
                "forecast" : "Partly cloudy",
                "time" : "2021-01-01 12:00:00"
            }
        }
    },
    "422": {
        "description": "The city ID is not valid"
    }
}
```
