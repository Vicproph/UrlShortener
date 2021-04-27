# UrlShortener
  This is an app that gets a URL as an input and then shortens it to a string of 5 chatacters
  with each character being chosen from a pool of 62 randomly, with a possible set of 62 to the power of 5 (~= 916 million) different combinations.
  the shortened key of the original URL is unique, so if an identicality is found with another key in the Database, the key will be generated again randomly.
  user types in a raw-url (e.g. 'www.google.com') and then gets their shortened one. if the raw-url has been INSERT'ed into the DB before by someone,
  the key-generator piece of code would not run and we only fetch the previously INSERT'ed URL and show it to the user.
  
  If a user enters the shortened URL into the browser URL-bar:
    If the key exists in the DB, they will be redirected to the raw-URL, else they will be notified that it's not stored in the DB.

# URLShortener (REST API)
  The same app, only that it is made as an API.
