## Local Setup Instructions

**1. Clone the repository:**
```sh
git clone git@github.com:edgecase123/api-challenge.git
cd api-challenge
```

**2. Install PHP dependencies:**
```sh
composer install
```

**3. Install JavaScript dependencies:**
```sh
npm install
```

**4. Copy and configure environment file:**
```sh
cp .env.example .env
# Edit `.env` and set APP_URL to http://api.test
```

**5. Generate application key:**
```sh
php artisan key:generate
```

**6. Set up your hosts file for the custom domain:**
```sh
sudo sh -c 'echo "127.0.0.1 api.test" >> /etc/hosts'
```

**7. Run database migrations and seed the database:**
```sh
php artisan migrate --seed
```

**8. Start the local development server:**
```sh
php artisan serve --host=api.test
```

**Note** there is a file `/doc/api-back.conf` that configures nginx if that is more convenient.

Now access the API at [http://api.test](http://api.test). This is the Laravel backend so you should only see a confirmation page.

**9. Update `.env` with API keys:**

if necessary, copy `.env.example` to `.env` and update the `.env` file.

```
API_LOR_BASE_URL=https://the-one-api.dev/v2
API_LOR_KEY=YOUR_API_KEY_HERE
```
---

### Optional: Use the local SQLite file database for testing

The sqlite memory database is used by default, but you can switch to using the sqlite file database included in the `/database` folder.

**Note** The tests
Edit `phpunit.xml` and set the `DB_DATABASE` environment variable to the path of the included SQLite file (e.g., `database/database.sqlite`):

```xml
<env name="DB_DATABASE" value="database/database.sqlite"/>
```

This will make tests use the local SQLite file instead of an in-memory database.

---

### Running Feature Tests

To run the feature tests, use the following command:

```sh
php artisan test --testsuite=Feature
```
To optionally refresh the database, add this step after running migrations and seeding:

---

**Optional: Refresh the database (drops all tables and re-runs all migrations and seeders):**
```sh
php artisan migrate:refresh --seed
```

Use this if you want to reset the database to a clean state.

[See additional documentation in `doc/NOTES.md`](doc/NOTES.md)
