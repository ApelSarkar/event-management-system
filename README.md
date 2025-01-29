# Event Management System

## **Overview**
The Event Management System is a web application built using raw PHP and MySQL for the backend and Bootstrap, HTML, and CSS for the frontend. The system allows users, clients, and admins to manage events and user data effectively. The platform includes essential features such as user registration, login, data validation, event management, searching, CSV downloads, and a JSON API for programmatic access to event details.

---

## **Features**

### **User Roles and Functionalities:**

#### **1. Attendie:**
- Register for multiple events simultaneously.

#### **2. User:**
- Create, update, and delete multiple events.
- View user information for their respective events.

#### **3. Admin:**
- Manage events by updating or deleting them.
- Download user information related to specific events.
- Download CSV files containing user information for events.
- Paginate and filter user information for easier data management.

### **General Features:**
- **Login and Registration:** Secure user authentication.
- **Validation:** Server-side validation for data integrity.
- **Searching:** Search functionality to easily find user or event information.
- **CSV Download:** Export event-related user information in CSV format.
- **API:** JSON-based API endpoint to programmatically fetch event details.

---

## **Technology Stack**
- **Backend:** Raw PHP, MySQL
- **Frontend:** Bootstrap, HTML, CSS
- **Hosting:** Free hosting server

I have added the database file , plese check it
---

## **API Documentation**
### **Endpoint:** `/api_events.php`
**Method:** `GET`
**Response Format:** JSON

**Example Response:**
```json
[
  {
    "id": 1,
    "name": "scrum bbb",
    "description": "Learn about scrum master in the event details event",
    "date": "2025-01-28",
    "max_capacity": 5,
    "created_at": "2025-01-25 09:28:58"
  },
  {
    "id": 3,
    "name": "programming",
    "description": "problem solving in the event learn new things",
    "date": "2025-01-26",
    "max_capacity": 120,
    "created_at": "2025-01-25 09:30:44"
  }
]
```

---

## **Database Structure**
### **Tables:**
1. **Users:** Stores user information and role information.
2. **Events:** Stores event details.
3. **Attendees:** Manages attendie registrations for multiple events.
4. **event-attendees:** This is a pivote table where i stored event and attendie id to manage multiple registration of an respective event.



## **Setup Instructions**
### **1. Clone the Repository:**
```bash
git clone https://github.com/ApelSarkar/event-management-system
```
### **2. Configure the Database:**
- Import the provided SQL file into your MySQL database.
- Update the database configuration in `db.php`:
  ```php
  $servername = "your_server_name";
  $username = "your_username";
  $password = "your_password";
  $dbname = "your_database_name";
  ```

### **3. Run the Application in local:**
- Start your PHP server using:
  ```bash
  php -S localhost:8000
  ```
- Access the application at `http://localhost:8000`.

---

## **Usage Instructions**
1. **Attendie Registration:**
   - http://apel.ct.ws/views/register_event.php
   - Navigate to the registration page and fill out the required details.

2. **Login:**
   - http://apel.ct.ws/views/login.php
   - Use below credentials to log in as a user or admin.
   - For admin login use email: admin@gmail.com & password: admin
   - For user login user email: apel@gmail.com & password: 123456  

3. ** User Actions:**
   - Create, update, and delete events.
   - View attendie information for specific events.

4. **Admin Actions:**
   - Manage event activities.
   - Download user information as CSV.
   - Paginate and filter user data.

---

## **Security Features**
- Password hashing for secure authentication.
- Server-side input validation & prevent sql injection.

---

## **Future Enhancements**
- Add role-based access control for enhanced security.
- Implement email notifications for event registrations.
- Introduce graphical data representations for event analytics.
- Deploy the system on a scalable cloud platform.
- Add payment gateway
- SMS api integration
---

Thanks for reading

