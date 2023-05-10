Administration to add / delete / edit brands
=================

Start by running `php -S localhost:8000 -t www` 

DB
--

Replace 'local.neon' with:
``` 
parameters:


database:
	dsn: 'mysql:host=127.0.0.1;dbname=sportisimo'
	user: 'root'
	password: 'root'
```

Login
-----
To login into application uset userName: "admin" password: "admin"
