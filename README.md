# CO1706-Assignment-2
By: Cameron Paul Fleming (G20860874)

All files for this project are stored in the src/ directory and tracked with Git.

- Submission Date: `18th April 2021`
- GitHub Repository: https://github.com/nevexo-uni/CO1706-Assignment-2 (access available by request)
- Vesta URL: https://vesta.uclan.ac.uk/~CPFleming/assignment-2/

## Assignment Progress
- [x] App Connects to MySQL Database
- [ ] Authentication
  - [ ] Session Tracking
  - [ ] Password Hashing
  - [ ] User Greeting on Pages
- [ ] Pages
  - [ ] Home Page
    - [ ] Special Subscription Rates
  - [ ] Tracks Page
    - [ ] List of Tracks
    - [ ] Display Album Artwork (filenames from MySQL)
    - [ ] Users can Play Tracks (HTML5 Audio)
    - [ ] Users can Browse by Genre
    - [ ] Users can Browse by Artist/Album
  - [ ] Registration
    - [ ] Name
    - [ ] Password (See `Authentication`)
    - [ ] Pricing Plan (Listed in MySQL)
    - [ ] No duplicate usernames 
  - [ ] Track Page
    - [ ] Allow user to review track
    - [ ] Show Average Rating
  - [ ] Album Description Pages
  - [ ] Text-based Search Page
- [ ] Playlist feature (+ Auto shuffle)
- [ ] Input validation
- [ ] Pass W3 Validation
- [ ] Recommendation System - Suggest tracks the user might like based on their ratings of other tracks.
- [ ] Custom 404 Page (src/pages/404.html)

## Scripts Included
This project was built using Docker containers matching the specifications of UCLan's Vesta System.

### dev.ps1
This script attempts to start Docker and brings up both NGINX-PHP and a MariaDB.

### vesta.ps1
This script pushes all files from the src directory to the Vesta FTP server, it requires secrets.csv which is not included
in the repository.

