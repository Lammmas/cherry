/* Add a test user (testuser/testpass) */
INSERT INTO oauth_users
  (username, password, user_id)
VALUES
  ('testuser', '$2y$10$0PLA0gpbGw5PTnll/UVr4.QqYFJ6rfoDak4XkJDWhOuQlR1QB4pDe', 1);

/* Add a test client (testclient/testpass) */
INSERT INTO oauth_clients
  (client_id, client_secret, redirect_uri)
VALUES
  ('testclient', '$2y$10$cwjpmicvjN3Lph8DIiQnWesRTg261lFBM3sThxl9UDqlvSjG2Ii46', '/oauth/receivecode'),
  ('testpublic', '', '/oauth/receivecode');