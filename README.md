#Notizen

##English

This repository contains the vue.js / Laravel application as requested
by Kosatec.  This comes with a complete Docker configuration to produce
a laravel_docker containing nginx, php 8.2 and mysql.


###Installation
Create a separate subdirectory, and clone the repository into there.  

```
git clone https://github.com/steve-dix/kosatec-aufgabe.git
```

There is only one branch, main, so you do not need to checkout anything.

Assuming you have installed Docker, the containers can be built with
the following command.

```
docker compose -f docker-compose.yaml up --build -d

```

###Directory Structure

.
 * [dir1](./laravel_docker)
   * [dir2](./docker)
      * [dir4](./nginx)
	     * [default.conf](./dir1/dir2/dir4/default.conf)
      * [dir5](./php)
	     * [default.conf](./dir1/dir2/dir5/default.conf)
   * [dir3](./src)
 * [.env](./.env)
 * [README.md](./README.md)
