# Invoice CRUD

##  Requirements

    Docker
    Make


##  Installation

### 1. Configuration
* Prepare .env file
  > cp .env .env.local

* Prepare .docker-compose file
  > cp docker-compose.override.dist.yml docker-compose.override.yml

### 2. Build via makefile
  > make build

### 3. Run composer install
  > make composer-install
  
### 4. Load data and migration via makefile
  > make load
  
### 5. Bash
  > make bash

### Composer scripts
  - composer fix 
  - composer phpstan
  - composer test
  
## Links
  * [adminer](http://localhost:8020/)
  * [app](http://localhost:8080)