# HomEase - Home Services Platform

HomEase is a comprehensive web application for connecting home service providers with clients. The platform facilitates the booking, management, and tracking of home services with an elegant monochromatic interface.

## Features

- **User Authentication**: Secure login and registration with Google Authentication
- **Multiple User Roles**:
  - **Admin**: System administration and oversight
  - **Manager**: Service provider management
  - **Client**: End-users seeking home services
- **Google Maps Integration**: Real-time directions and service provider tracking
- **Responsive Design**: Clean, monochromatic black and white theme
- **Service Booking**: Easy scheduling and management of service appointments
- **Provider Ratings**: Quality assurance through customer reviews

## Tech Stack

- **Backend**: PHP
- **Frontend**: HTML5, Custom CSS (no frameworks)
- **Database**: MySQL
- **Authentication**: Google OAuth 2.0
- **Maps**: Google Maps JavaScript API
- **Design**: Monochromatic black and white theme

## Installation

1. Clone the repository:
   ```
   git clone https://github.com/yourusername/HomEase.git
   ```

2. Set up your web server (Apache/XAMPP) to point to the project directory

3. Import the database schema:
   ```
   mysql -u username -p database_name < database/schema.sql
   ```

4. Configure your Google API credentials in `config/config.php`

5. Access the application through your web browser:
   ```
   http://localhost/HomEase
   ```

## Project Structure

```
HomEase/
├── config/               # Configuration files
├── database/             # Database schema and migrations
├── public/               # Publicly accessible files
│   ├── index.php         # Application entry point
│   ├── assets/           # CSS, JS, images
│   └── .htaccess         # URL rewriting rules
├── src/                  # Application source code
│   ├── controllers/      # Request handlers
│   ├── models/           # Data models
│   ├── views/            # UI templates
│   ├── services/         # Business logic
│   └── utils/            # Helper functions
└── vendor/               # Dependencies (if any)
```

## Setup for Development

1. Register a project in the Google Developer Console
2. Enable Google OAuth 2.0 and Google Maps JavaScript API
3. Create credentials and add them to your configuration
4. Configure your database connection in `config/config.php`

## Usage

- **Admin Panel**: Access via `/admin` after logging in with admin credentials
- **Service Provider Dashboard**: Available to users with manager role
- **Client Booking**: Main interface for end-users to book services

## Security Considerations

- All user passwords are securely hashed
- CSRF protection is implemented for all forms
- Input validation is performed for all user inputs
- Authentication is required for sensitive operations

## License

[MIT](LICENSE) 