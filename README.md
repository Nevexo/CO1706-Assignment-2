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

## Testing Plan
This plan can be followed to test all features of the platform.

- Open the platform,
  - https://vesta.uclan.ac.uk/~CPFleming/assignment-2/
  - http://ecksmusic.ldn1.cpfleming.co.uk
- Attempt to navigate to the Tracks information page
- Create a new account,
  - Use nav-bar register button or
  - Select one of the offers from the carousel or offers card.
- Test password match validation by entering two different passwords
- Create the account, selecting the gold pricing plan
- Scroll to the bottom of the landing page and select "Start using EcksMusic"
- Navigate to the tracks page from the navigation bar
- Filter the tracks by the `Indie` genre
- Scroll to the bottom and navigate to page 2
- Select `More Info` on the `Boppin Boots` track
- Press play on the audio element to test to hear a sample of the track
- Give the track a rating > 5, test the review box requires you to enter 10 characters to review a track.
- Submit your review
- Click the artist name, view the aritst information and navigate back to the track
- Click the `More Info` button on the album information and navigate back to the track.
- Select the `Add to Playlist` button 
  - Leave `New Playlist...` as the selected option
  - Press 'Add Track'
- Give the playlist a name and tick the `Make this Playlist Public` checkbox
- Select `Open Playlist` on the newly created playlist 
  - Ensure the `Boppin Boots` track has appeared
  - Remove the track
  - Select `Add 10 Random Tracks` to repopulate the playlist with random tracks
- Edit the playlist and uncheck the `Make this Playlist Public` button
  - Oberserve the playlist being removed from the `Public Playlists` box
- Delete the playlist (under the edit menu)
- Navigate to the search page from the navbar
- Enter `what` in the search-bar and leave the filter set to everything
- Visit the Artist & album pages, then return back to the search page.
- Select `More Info` on the track `Upon What Are You Pi`
- Leave this track a negative (< 5) rating.
- Select your username on the navigation bar and open the 'Recommended Tracks' page
  - Tracks displayed here should favour the higher rated genres, albums and artists.
  - Press refresh to get new recommendations.
- Navigate back to the Tracks page and navigate through some of the pages
  - Some tracks will be tagged as `Recommended for You`, and the tracks that were reviewed
    will show a `Average Rating: N/10` tag.
- Visit the `Upon What Are You Pi` page again, and delete the review
- Log out or delete your account
- Set the URL to an invalid page (see 404)
- Resize the browser to demonstrate responsive design

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