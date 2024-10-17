
# TO-DO Application

This is a TO-DO project built with Symfony, applying the hexagonal architecture with a Domain-Driven Design (DDD) approach. It allows you to do basic CRUD tasks and track a history of all actions.

## Prerequisites

- **Docker**: Make sure you have the latest version of Docker and Docker Compose installed on your machine.

## Installation

1. **Clone the Repository**

   ```bash
   git clone https://github.com/mystogan187/basic-backend-crud-app.git
   cd basic-backend-crud-app
   ```

2. **Build and Start the Containers**

   Build Docker images and start the containers by running:

   ```bash
   docker-compose up --build
   ```

   This command will build the images and start the services defined in the `docker-compose.yml` file.

3. **Access the Application**

   Once the containers are running, you can access the web application by navigating to:

   ```
   http://localhost:8000
   ```

   Ensure that port `8000` is free and not being used by another application.

## Running Tests

The application uses PHPUnit for unit testing. To run the tests inside the Docker container, use the following command:

```bash
docker-compose exec app ./vendor/bin/phpunit tests/
```

This command will run the tests defined in the `tests/` directory.

## Project Structure

The project is organized following the principles of hexagonal architecture, separating responsibilities into different layers and contexts:

## Technologies Used

- **PHP**: Backend programming language.
- **Symfony**: PHP framework for web applications.
- **Doctrine ORM**: Object-relational mapping for database interactions.
- **PHPUnit**: Framework for unit testing in PHP.
- **Docker**: Container platform to deploy the application in isolated environments.
- **MySQL**: Relational database to store the data.
- **Nginx/Apache**: Web server to serve the frontend and the API.

## Features

- **Create Task**: Add a new task to the list.
- **Delete Task**: Remove an existing task.
- **Update Task**: Modify the details of a task.
- **List Tasks**: Display all tasks, with an option to filter by completed/incomplete.
- **Complete Task**: Mark a task as completed.
- **Action History**: Shows a log of all actions performed on tasks.


## Additional Notes

- **Docker Versions**: It is essential to use the latest version of Docker to ensure compatibility and take advantage of the latest features and performance improvements.
- **Port Configuration**: The default port is `8000`. If you need to change it, you can modify the `docker-compose.yml` file.
- **Data Persistence**: Data is stored in Docker volumes to maintain persistence between container restarts.
- **Database Migrations**: If you need to run migrations, you can do so with:

  ```bash
  docker-compose exec php php bin/console doctrine:migrations:migrate
  ```

## Useful Commands

- **Install Dependencies**:

  ```bash
  docker-compose exec php composer install
  ```


---

**Contact**: If you have any questions or need help, you can contact me at [aec.alexandru@gmail.com](mailto:aec.alexandru@gmail.com).

---
