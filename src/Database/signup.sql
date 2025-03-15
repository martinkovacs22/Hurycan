CREATE PROCEDURE signup(IN username VARCHAR(255), IN email VARCHAR(255), IN password VARCHAR(64))
BEGIN
    INSERT INTO test_user (username, email, password) 
    VALUES (username, email, password);
END
