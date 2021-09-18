# CO1706-Assignment-2
By: Cameron Fleming (Nevexo)

This was a university assignment and is now archived, this webapp is a 
prototype/mock-up music streaming platform, built in PHP with a Bootstrap front-end.

This project is no-longer maintained, the project can be started with `docker compose up` - the
provided SQL file in sql-init will create demo users and populate the database
with the provided data automatically.

The following is documentation provided with the final assignment.

## Features Implemented
- Third (40%+)
  - [X] Connects to database with PDO (php/database.php)
  - [X] Logon page (pages/login.php)
  - [X] Homepage displays currently available subscriptions
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
  - [X] All HTML/CSS passes validation
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

## References
All references are cited where they are used.

### Bootstrap (CSS Framework)
Used in: `All Frontend Pages`

Link: https://getbootstrap.com/ 

### Object Sorting with `usort` - Stackoverflow Answer
Used in: `src/php/recommend.php` (line 135)

Author: Scott Quinlan

Link: https://stackoverflow.com/questions/4282413/sort-array-of-objects-by-object-fields
