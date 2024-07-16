# Org Chart API by Abdul Muhaimin bin Mohamad

This project provides RESTful APIs to upload and update the organizational chart.

## Setup

1. Clone the repository
2. Run `composer install`
3. Ensure the `data/` and `logs/` directories are writable.

## Endpoints

### Upload Org Chart

- **URL**: `/upload`
- **Method**: `POST`
- **Params**: 
  - `file`: CSV file containing the org chart
- **Response**: 
  - `200`: File uploaded successfully
  - `400`: No file uploaded
  - `500`: Failed to upload file

### Update Employee

- **URL**: `/update`
- **Method**: `POST`
- **Params**: 
  - `employee_id`: ID of the employee (required)
  - `employee_name`: Name of the employee (optional)
  - `reporting_line`: Reporting line of the employee (optional)
- **Response**: 
  - `200`: Employee updated successfully
  - `400`: Employee ID is required
  - `404`: Employee not found
  - `500`: Failed to read or write CSV file

### View Org Chart

- **URL**: `/view`
- **Method**: `GET`
- **Response**: 
  - `200`: JSON representation of the org chart
  - `500`: Failed to read CSV file

## Installation

1. **Clone the Repository**:
    ```bash
    git clone <repository-url>
    cd org-chart-api
    ```

2. **Install Dependencies**:
    ```bash
    composer install
    ```

3. **Ensure Directories are Writable**:
    Ensure that the `data/` and `logs/` directories are writable by the web server user.

4. **Start the PHP Built-in Server** (or configure your web server):
    ```bash
    php -S localhost:8000 -t public
    ```

## Testing

A Postman collection is included for testing the APIs. Follow the instructions below to load and use the collection.

### Loading Postman Collection

1. Open Postman.
2. Click on the `Import` button.
3. Select the `File` tab.
4. Choose the Postman collection JSON file from the repository.
5. Click `Import`.

### Using the Postman Collection

1. **Upload Org Chart**:
    - Method: `POST`
    - URL: `http://localhost:8000/upload`
    - Headers: None
    - Body: `form-data` with a key `file` and type `File`. Select a CSV file to upload.

2. **Update Employee**:
    - Method: `POST`
    - URL: `http://localhost:8000/update`
    - Headers: `Content-Type: application/json`
    - Body: `raw` JSON, e.g.:
      ```json
      {
        "employee_id": "1001",
        "employee_name": "Alice Updated",
        "reporting_line": "Bob"
      }
      ```

3. **View Org Chart**:
    - Method: `GET`
    - URL: `http://localhost:8000/view`
    - Headers: None
    - Body: None

## Logs

Logs are stored in the `logs/` directory. Ensure this directory is writable by the web server user.
