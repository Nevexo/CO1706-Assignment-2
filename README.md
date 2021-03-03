# CO1706-Assignment-2
By: Cameron Paul Fleming (G20860874)

All files for this project are stored in the src/ directory and tracked with Git.

- Submission Date: `18th April 2021`
- GitHub Repository: https://github.com/nevexo-uni/CO1706-Assignment-2 (access available by request)
- Vesta URL: https://vesta.uclan.ac.uk/~CPFleming/assignment-2/

## Assignment Progress
- [x] App Connects to MySQL Database
- [x] Authentication
  - [x] Session Tracking
  - [x] Password Hashing
  - [x] User Greeting on Pages
- [x] Pages
  - [x] Home Page
    - [x] Special Subscription Rates
  - [X] Tracks Page
    - [X] List of Tracks
    - [X] Display Album Artwork (filenames from MySQL)
    - [X] Users can Play Tracks (HTML5 Audio)
    - [ ] Users can Browse by Genre
    - [ ] Users can Browse by Artist/Album
  - [x] Registration
    - [x] Name
    - [x] Password (See `Authentication`)
    - [x] Pricing Plan (Listed in MySQL)
    - [x] No duplicate usernames 
  - [X] Track Page
    - [X] Allow user to review track
    - [X] Show Average Rating
  - [ ] Album Description Pages
  - [ ] Text-based Search Page
- [ ] Playlist feature (+ Auto shuffle)
- [ ] Input validation
- [ ] Pass W3 Validation
- [ ] Recommendation System
  - [ ] Check all reviews by a user
  - [ ] Order reviews by rating
  - [ ] Get genre/album/artist for all tracks and randomly select tracks from those
  - [ ] Display recommendations on a seperate page and the homepage's jumbotron
- [x] Custom 404 Page (src/pages/404.html)

## Users
Registration is enabled on the Vesta server, so accounts can be created when needed,
the following accounts were created for demo purposes and can be logged into.

| Username | Password         | Use Case                                                  |
|----------|------------------|-----------------------------------------------------------|
| DemoUser | Passw0rd!        | Testing the platform without creating another user        |
| Bobby    | EcksMusicBobby   | A default user account holding a review on track 1        |
| Brendan  | EcksMusicBrendan | A default user account holding reviews on tracks 1 and 61 |

## Scripts Included
This project was built using Docker containers matching the specifications of UCLan's Vesta System.

### dev.ps1
This script attempts to start Docker and brings up both NGINX-PHP and a MariaDB.

### vesta.ps1
This script pushes all files from the src directory to the Vesta FTP server, it requires secrets.csv which is not included
in the repository.

