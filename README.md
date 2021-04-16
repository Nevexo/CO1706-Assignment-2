# CO1706-Assignment-2
By: Cameron Paul Fleming (G20860874)

All files for this project are stored in the `src/` directory and tracked with Git.

A working copy of the MySQL database can be found in the `sql-init/` directory, this is automatically installed to the local
docker based MySQL server during the first start.

The `tools/` directory stores smoketest scripts for the PHP and the single-use Python script used to convert the provided database into
the schema used by this application.

The `src/samples/` and `src/images/` directories include the provided files, `src/svgs/` contains the modified subscription header images.

Two .htaccess files are tracked by Git, `src/.htaccess` is used by the Docker Apache server for testing locally, `src/.htaccess_vesta`
is the modified version of this file specifically for use on Vesta. This is rename to `.htaccess` when the source is synced to Vesta.

The `src/php/vars.php` file contains settings for the platform, including database connection details, controls for the registration system, etc.

- Due Date: `18th April 2021`
- GitHub Repository: https://github.com/nevexo-uni/CO1706-Assignment-2 (access available by request)
- Access - Both servers have the same database.
  - UCLan Vesta: https://vesta.uclan.ac.uk/~CPFleming/assignment-2/
  - As-developed build: http://ecksmusic.ldn1.cpfleming.co.uk/

## Features Implemented
- Third (40%+)
  - [X] Connects to database with PDO (php/database.php)
  - [X] Logon page (pages/login.php)
  - [X] Homepage displays current subscriptions in the carousel
  - [X] Tracks page (pages/tracks.php)
- 2:2 (50%+)
  - [X] Session tracking
  - [X] Tracks page displays artwork (pages/tracks.php)
  - [X] HTML5 Player (pages/track.php)
  - [X] Users can browse tracks by genre
  - [X] User greeted on all pages with their username (top right)
  - [X] Validation on all forms
  - [X] 404 Page (.htaccess, pages/404.html)
- 2:1 (60%+)
  - [X] Registration Page (pages/register.php)
    - Username
      - Duplicates not allowed
    - Password
    - Pricing plan
  - [X] Track information page (pages/track.php)
  - [X] Users can review tracks (pages/track.php + php/reviews.php)
  - [X] HTML/JavaScript form validation on all forms
  - [X] Passwords stored with hashes (php/auth.php)
- First (70%+)
  - [X] Playlist Function
    - Random selection of tracks
  - [X] Users can browse for music by album and artist
  - [X] Album description page
  - [X] Search page
  - [X] Average review ratings displayed on
    - Tracks page
    - Track information page
    - Playlist page
  - [ ] All HTML/CSS passes validation
- 90%+
  - [X] Recommendation System

## Users
Registration is enabled on the Vesta server, so accounts can be created when needed,
the following accounts were created for demo purposes and can be logged into.

| Username | Password         | Use Case                                                  |
|----------|------------------|-----------------------------------------------------------|
| DemoUser | Passw0rd!        | Testing the platform without creating another user        |
| Bobby    | EcksMusicBobby   | A default user account holding a review on track 1        |
| Brendan  | EcksMusicBrendan | A default user account holding reviews on tracks 1 and 61 |

## Running with Docker
This assignment was tested with Docker, using the Dockerfile found in php-image and MariaDB. Run `docker-compose up -d`
from the root directory and navigate to http://localhost

If testing through some other means, it may be necessary to alter the database connection details, these can be found in 
`src/php/vars.php` file. 