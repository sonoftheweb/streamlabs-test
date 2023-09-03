# Streamlabs fullstack challenge
## Description
This is a simple application that allows users to authenticate with either Google or Twitch account. Once authenticated, they are allowed to see a feed of events concerning subscriptions, followers, donations and merchandise sales. The application is built using Laravel as the stateless backend and Vue.js as the frontend framework. The application is not deployed anywhere, so it has to be run locally. 



https://github.com/sonoftheweb/streamlabs-test/assets/1128096/b38c7ac7-9285-401c-9f39-e6c12b5717e4



## Installation
### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js
- Docker

### Steps
1. Clone the repository.
2. Change directory to the repository.
3. Run `composer install` in the api directory.
4. In the api directory, copy the `.env.example` file to `.env` and fill in the required values for the google and twitch authentication. The values can be obtained from the respective developer consoles.
5. Run `./vendor/bin/sail up` in the api directory. This will create and start the docker containers.
6. Run `./vendor/bin/sail artisan migrate` in the api directory. This will create the database tables. also run `./vendor/bin/sail artisan passport:install to setup Laravel passport keys.
7. Run `npm install` in the "frontend" directory.
8. In the "frontend" directory, copy the `sample.env` file to `.env`. There will be no need to change any values in this file.
9. Run `npm run dev` in the "frontend" directory to serve the frontend application in development mode. Alternatively, run `npm run prod` to build the frontend application for production and serve it over Docker. The frontend application will be available at `http://localhost:3000`.
10. After logging in on the frontend app at `http://localhost:3000`, the user will be redirected to the dashboard page. The dashboard page will show an empty feed of events from the authenticated user. If you want to have some data to play with, in the api directory, run `./vendor/bin/sail artisan db:seed`. This will seed the database with some data. The dashboard page will now show the seeded data.

### Notes
- I introduced the events table to streamline how data is fetched and stored in the DB. The events table is a polymorphic table that stores events from the different platforms. The events table has a one-to-many relationship with the users table where one user may have many events of different types. 
- I ran out of time and would love to optimize some of the code especially calls to the DB where I reduce the number of calls to the DB and or pass some of the logic to the DB.
- The donations table does contain a field for currency as well as the amount donated. I was able to implement a way to convert currency, at the time of donation (as currency fluctuates on a daily basis). The base currency of the app is USD (hard codded). Would love to implement a way to change the base currency of the app.
