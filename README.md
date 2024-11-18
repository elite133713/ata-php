Here is the complete README.md file content in Markdown format:

# Movie Sales Analysis Application

This application is designed for a movie research company to analyze movie sales data. It provides functionalities to:

- Seed the database with large datasets (e.g., 1 million sales records).
- Identify the theater with the highest sales on a specific date via a console command.
- Display sales data and charts through a web UI.
- Run feature tests to ensure application correctness.

Database Schema and Data Explanation

Database Schema Overview

The application uses a MySQL database with the following main tables:
1.	`theaters` columns:
* id (Primary Key)
* name (String)
* rating (Unsigned tiny integer)
* location (String)
* Timestamps (created_at, updated_at)
2.	`movies` Columns:
* id (Primary Key)
* title (String)
* genre (String)
* rating (Unsigned tiny integer)
* release_date (Date)
* Timestamps (created_at, updated_at)
3.	`sales` Columns:
* id (Primary Key)
* theater_id (Foreign Key to theaters.id)
* movie_id (Foreign Key to movies.id)
* sale_date (Date)
* tickets_sold (Integer)
* Timestamps (created_at, updated_at)

Relationships

* Each sale belongs to a theater and a movie.
* A theater can have many sales.
* A movie can have many sales.

Indexes

Indexes are added to optimize query performance:
* sales table:
* Index on sale_date
* Index on theater_id
* Index on movie_id

Database Dump Files

Sample database dump files are provided in the `database/schema` directory:
* Structure Dump: Contains the SQL statements to create the tables and indexes.
* Sample Data Dump: Contains a small set of data to illustrate the schema (not the full dataset due to size constraints).
---

## Prerequisites

Before setting up the project, ensure you have the following installed:

- **Docker**
- **Docker Compose**

---

## Installation

**Clone the Repository**

   ```bash
   git clone https://github.com/elite133713/ata-laravel
   cd ata-laravel
   ```
## Environment Setup

The project is containerized using Docker and Docker Compose. Follow the steps below to set up the environment.

**Copy .env File**

Copy the example .env file and set your environment variables:
```bash
cp .env.example .env
```

**Build and Start the Docker Containers**

Run the following command to build and start the containers:

```bash
docker-compose up -d --build
```

This command will:
•	Build the Docker images for the application and database.
•	Start the containers in detached mode.

**Install Composer Dependencies**

Execute the following command inside the app container:
```bash
docker-compose exec app composer install
```

**Generate Application Key**

Inside the app container, run:
```bash
docker-compose exec app php artisan key:generate
```

**Run Migrations**
```bash
docker-compose exec app php artisan migrate
```

**Seed the Database**

Note: Seeding large datasets (e.g., 1 million records) can take significant time and resources. Ensure your system has sufficient memory and disk space.

***Option 1: Seed Full Dataset***

docker-compose exec app php artisan db:seed

***Option 2: Seed Smaller Dataset for Testing***
•	Adjust the $totalSales variable in `SaleSeeder.php` to a smaller number (e.g., 1000).
•	Run the seeder:
```bash
docker-compose exec app php artisan db:seed
```
**Running the Application**

**Access the Application**

The application should be accessible at http://localhost:8000.

**Using the Console Command**

The application includes an Artisan command to find the theater with the highest sales on a specific date.

***Run the Command***
```bash
docker-compose exec app php artisan highest-sales:theater
```

***Follow the Prompt***

•	You will be asked to enter a date in the format YYYY-MM-DD.

•	Example: Enter a date (YYYY-MM-DD):

•	After entering the date, the command will output the theater with the highest sales on that date.

***Sample Output***

Theater with highest sales on 2024-05-15:
Theater: Sample Theater, Tickets Sold: 1500

Using the Web UI

The application provides a web interface to find the theater with the highest sales and view monthly sales charts.

***Access the Highest Sales Page***

Navigate to:

http://localhost:8000/highest-sales


***Select a Date***

•	Use the date picker to select a date within the range of your sales data (e.g., 2024-05-15).
•	Click the Submit button.

*** View Results***

•	The page will display:
•	The theater with the highest sales on the selected date.
•	The number of tickets sold.
•	A bar chart showing the total tickets sold for each day of the month.

***Chart Interactivity***

•	Hover over the bars in the chart to view tooltips with sales data.
•	The selected date will be highlighted in the chart.

**Running Tests**

Feature tests are included to ensure the application functions as expected.

***Run Tests***

```bash
docker-compose exec app php artisan test
```

***Sample Output***

```
PASS  Tests\Feature\SalesControllerTest
✓ index page loads successfully
✓ show page displays correct data for valid date
✓ show page displays validation error for invalid date
✓ show page displays message when no sales data found
✓ show page returns highest sales among multiple theaters

Tests:  5 passed
```

**Troubleshooting**

***Containers Not Starting***

   •	Issue: Containers fail to start or exit immediately.
   •	Solution: Check the Docker Compose logs:

```bash
docker-compose logs
```

Look for error messages and resolve any issues (e.g., port conflicts, permission issues).

***Database Connection Errors***

Issue: The application cannot connect to the database.

Solution:
Ensure the database container is running:

```bash
docker-compose ps
```
Verify database credentials in the .env file match those in docker-compose.yml.
Wait a few seconds after starting the containers to allow the database to initialize.

***Seeding Process Hangs or Crashes***

Issue: Seeding large datasets causes the container to hang or crash.

Solution:
Allocate more resources to Docker (memory and CPU) via Docker settings.
Seed a smaller dataset for testing purposes.

***Artisan Commands Not Found***

Issue: php artisan commands are not recognized inside the container.

Solution:
Ensure Composer dependencies are installed:
```bash
docker-compose exec app composer install
```

### Contact

Thank you for using the Movie Sales Analysis Application!

