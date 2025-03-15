CREATE PROCEDURE login(IN username VARCHAR(255), IN password VARCHAR(255))
BEGIN
    SELECT * FROM test_user WHERE username = username AND password = password;
END
