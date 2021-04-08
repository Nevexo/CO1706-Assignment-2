# CO1706/Tools - Single-use throw-away script for converting the existing database into
# the schema used by EcksMusic. 

# Cameron Paul Fleming - 2021

import mysql.connector

db = mysql.connector.connect(
  host="127.0.0.1",
  user="root",
  password="password",
  database="musicstream"
)

cursor = db.cursor()
artists = []
albums = []
tracks = []

cursor.execute("SELECT * FROM tracks;")
result = cursor.fetchall()

for x in result:
  if x[1] not in artists:
    artists.append(x[1])
  if [x[1], x[2]] not in albums:
    albums.append([x[1], x[2]])
  if [x[0], x[1], x[2], x[3], x[4], x[5], x[6], x[7], x[8]] not in tracks:
    tracks.append([x[0], x[1], x[2], x[3], x[4], x[5], x[6], x[7], x[8]])

# for artist in artists:
#   cursor.execute(f"INSERT INTO artists (artist_name) VALUES ('{artist}')")
#   db.commit()
#   print(f"commit: {cursor.rowcount}")

cursor.execute("SELECT * FROM artists")
artists = cursor.fetchall()

# for album in albums:
#   artist = album[0]
#   album = album[1]
#   a_id = 0

  # for a in artists:
  #   if a[1] == artist:
  #     a_id = a[0]
  
#   print(f"{album} => {a_id}")

#   cursor.execute(f"INSERT INTO albums (artist_id, album_name) VALUES ({a_id}, '{album}');")
#   db.commit()
#   print(f"commit: {cursor.rowcount}")

cursor.execute("SELECT * FROM albums")
albums = cursor.fetchall()

for track in tracks:
  id = track[0]
  artist = track[1]
  album = track[2]
  desc = track[3]
  name = track[4]
  genre = track[5]
  image = track[6]
  thumb = track[7]
  sample = track[8]
  artist_id = 0
  album_id = 0

  for a in artists:
    if a[1] == artist:
      artist_id = a[0]
  
  for a in albums:
    if a[2] == album:
      album_id = a[0]

  if album_id == 0:
    print("no album " + album)
    continue

  if artist_id == 0:
    print("no artist " + artist)
    continue

  cursor.execute(f'INSERT INTO tracks_new VALUES ({id}, {artist_id}, {album_id}, "{name}", "{desc}", "{image}", "{thumb}", "{sample}", "{genre}");')
  db.commit()
  print(f"commit: {cursor.rowcount}")