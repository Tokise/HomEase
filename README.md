# HomEase

HomEase is a home service management system that helps connect homeowners with service providers.

## Prerequisites

- PHP 8.0 or higher
- Composer
- XAMPP (or similar local development environment)
- MySQL Database
- Google Cloud Platform account (for Google API features)

## Installation

1. Clone the repository to your XAMPP htdocs folder:
```bash
git clone [repository-url] HomEase
cd HomEase
```

2. Install PHP dependencies using Composer:
```bash
composer require google/apiclient:^2.15.0
composer require vlucas/phpdotenv:^5.5
composer require ralouphie/getallheaders:^3.0.3
composer require guzzlehttp/guzzle:^7.8
composer require firebase/php-jwt:^6.8
composer require monolog/monolog:^3.0
```

3. Create environment file:
   - Copy `.env.example` to `.env`
   - Update the `.env` file with your configuration:
     - Database credentials
     - Google API credentials
     - Application URL
     - Other required settings

4. Set up Google Cloud Platform:
   - Create a new project in Google Cloud Console
   - Enable necessary APIs (Google Calendar, Google Maps, etc.)
   - Create OAuth 2.0 credentials
   - Download your credentials JSON file
   - Update the GOOGLE_APPLICATION_CREDENTIALS path in `.env`

5. Database Setup:
   - Create a new MySQL database
   - Update the database configuration in `.env`:
     ```
     DB_HOST=localhost
     DB_NAME=homease
     DB_USER=your_username
     DB_PASS=your_password
     ```

## Configuration

### Google API Setup
1. Go to [Google Cloud Console](https://console.cloud.google.com)
2. Create a new project or select an existing one
3. Enable the required APIs:
   - Google Calendar API
   - Google Maps API
   - Google OAuth 2.0
4. Create credentials (OAuth 2.0 Client ID)
5. Add the credentials to your `.env` file:
   ```
   GOOGLE_CLIENT_ID=your_client_id
   GOOGLE_CLIENT_SECRET=your_client_secret
   GOOGLE_REDIRECT_URI=http://localhost/HomEase/public/auth/google-handler
   ```

## Running the Application

1. Start your XAMPP server (Apache and MySQL)
2. Access the application through your web browser:
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

## Features

- User Authentication (Local and Google)
- Service Provider Management
- Booking System
- Calendar Integration
- Real-time Notifications
- Payment Processing
- Review and Rating System

## Security Notes

- Never commit your `.env` file to version control
- Keep your Google API credentials secure
- Regularly update dependencies
- Use HTTPS in production

## Support

For support, please email [support email] or open an issue in the repository.

## License

[Your License Information] 