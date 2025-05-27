# Notes

This project acts as the backend for the take home challenge. It is a standard Laravel 12 project and mediates between the front-end Vue.js application and the TheOneAPI api routes for retrieving character data from Lord of the Rings, while persisting and managing saved search data.

### Special Considerations

#### User Authentication and Authorization

Although Laravel can be scaffolded with authorization starter kit logic, I have by-passed authentication in this project for the sake of simplicity.

Instead, I wrote a simple middleware that retrieves the default 'Test User' and automatically logs that user into the system.

The database and models use relationships between SearchList and Search models as well as assign SearchLists to users as the owning side of a one-to-many.

### Main Points of Interest

#### Controllers
There are 2 controllers used for this project.
- TheOneController - Handles logic for interacting with TheOneApi via a service class, `/Services/TheOneApiService.php`. Provides validation.
- SearchController - Handles logic for CRUDing SearchLists and Searches. Routes introduced to `/routes/api.php` for this project follow a REST'ish style. Note that it does not use native Laravel resource controller.

#### Service
`/Services/TheOneApiService.php` encapsulates TheOneAPI specific data retrieval logic.

For larger projects, I would have implemented an Interface for the TheOneApiService and an accompanying ConcreteTheOneApiService class to better abstract the pieces and allow for different implementations.

#### Models
- SearchList - Represents a named list of saved Searches through a one-to-many relationship with the Search model.
- Search - Represents a saved search belonging to a SearchList. This is the many side of the relationship between SearchList and Search models.

##### Feature Tests
Two feature tests were written for each of the controllers.
- SearchListTest - Proofs main functionality in working with saving, deleting, retrieving search related data stored in database tables.
- TheOneApiRouteTest - Proofs logic surrounding retrieving and presenting data from TheOneAPI.
- Tests could have been fleshed out better with more time and made to cover more edge cases.

#### Migrations
Migrations written for this project are located in `/database/migrations`.

#### Cors
The `/config/cors.php` file as been modified to allow the Vue.js application to connect from a different domain/port to this Laravel application. Vue.js application runs on  http://localhost:5173.

### Custom Domain api.test
The Vue.js front-end is expecting to call into the Laravel backend using `http://api.test` locally. Be sure to set up that domain in your local hosts file. See the README.md file for this project for a link to run a command to create the hosts entry for you.
