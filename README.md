## Local Setup


### Requirements
1. Docker/Docker Compose
2. Git client

### How to Run?

The fast way to run the service is by executing ``install_server.sh`` script from root folder of the repository:
```
./install_server.sh
```
If you get the error `Can't connect to local MySQL server through socket` just run manually the db setup from in `install_server.sh` or re-run command

Then point your browser to 

```
http://localhost:8080
```

Alternatively you can run it without Docker using e.g. php build in server.
It requires creating `.env` file (you can check `.env.dist`). Run `composer install` and
set your servers document root to `public` directory. Example:
```
 php -S localhost:8080 public/index.php
```

### Users

One admin users has already been created. Here are the credentials:

```
login: admin
password: password
```

You can add more using cli command 
```
./bin/add_user.sh janek brzechwa JanB
```
Or without Docker
```
php console.php AddUser username password displayName
```

### How to restart it?

If you have previously installed it, run ``restart_server.sh``

### How to destroy it?
```
./destroy_server.sh
```

### Test and code quality tools
To perform full code check run

```
./bin/check.sh
```

To check code coverage

```
./bin/show-coverage.sh
```

Alternatively all commands can be run via composer scripts without Docker. See: scripts section in `composer.json`.
Generating code coverage requires coverage driver, e.g. PCOV or Xdebug.