
# Laravel Teams Notification

**A Laravel package for sending notifications to Microsoft Teams.**

## Overview

The `laravel-teams-notification` package allows you to easily send notifications to Microsoft Teams channels using webhooks. This package integrates with Laravel and provides a simple API for sending adaptive card messages.

## Features

- Send messages to Microsoft Teams channels using adaptive cards.
- Easy integration with Laravel.
- Configurable webhook URL via the `.env` file.

## Installation

You can install the package using Composer. Run the following command in your Laravel project:

```bash
composer require osama/laravel-teams-notification
```

## Configuration

1. **Publish the Configuration File**

   Publish the configuration file to your Laravel application:

   ```bash
   php artisan vendor:publish --provider="Osama\LaravelTeamsNotification\LaravelTeamsNotificationServiceProvider" --tag=config
   ```

   This will create a `teams.php` configuration file in your `config` directory.

2. **Set Up the Webhook URL**

   Open your `.env` file and add your Microsoft Teams webhook URL:

   ```dotenv
   TEAMS_WEBHOOK_URL=https://your-webhook-url
   ```

## Usage

To send a notification to Microsoft Teams, use the `TeamsNotification` class. Here's an example of how to use it in a controller:

```php
use Osama\LaravelTeamsNotification\TeamsNotification;

class NotificationController extends Controller
{
    public function sendTeamsNotification()
    {
        $teamsNotification = new TeamsNotification();
        $statusCode = $teamsNotification->send('Your message here');

        return response()->json(['status' => $statusCode]);
    }
}
```

## Configuration Options

- **webhook_url**: The webhook URL for your Microsoft Teams channel. Set this in your `.env` file as `TEAMS_WEBHOOK_URL`.

## Contributing

Contributions are welcome! Please read the [contributing guidelines](CONTRIBUTING.md) for more information on how you can help improve this package.

## License

This package is licensed under the [MIT License](LICENSE).

## Contact

For questions, issues, or feature requests, please open an issue on the [GitHub repository](https://github.com/your-username/laravel-teams-notification) or contact me directly.

## Changelog

Check out the [CHANGELOG](CHANGELOG.md) for a list of changes and updates.

## Credits

- [Osama](https://github.com/your-username) - Creator and maintainer
- [Laravel](https://laravel.com) - The PHP framework used
- [Microsoft Teams](https://docs.microsoft.com/en-us/microsoftteams/platform/) - Notification platform
```

Replace placeholders like `https://your-webhook-url` and `https://github.com/your-username/laravel-teams-notification` with the actual values relevant to your package.

Feel free to adjust or add any additional sections as needed.