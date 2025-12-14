# Notizen

## German
Dieses Repository enthält die vue.js / Laravel-Anwendung, wie von Kosatec gewünscht. Es wird mit einer vollständigen Docker-Konfiguration geliefert, um ein `laravel_docker` mit nginx, PHP 8.2 und MySQL bereitzustellen.

### Installation
Erstellen Sie ein separates Unterverzeichnis und klonen Sie das Repository dort hinein:

```
git clone https://github.com/steve-dix/kosatec-aufgabe.git
```

Es gibt nur einen Branch, `main`, daher müssen Sie nichts anderes auschecken.

Vorausgesetzt, Sie haben Docker installiert, können die Container mit dem folgenden Befehl erstellt werden:

```
docker compose -f docker-compose.yaml up --build -d
```

Sobald die Container erstellt wurden, müssen Sie den PHP-Container betreten mit den folgenden Befehlen:

```
cd laravel-docker
  docker-compose exec -it php bash
  php artisan key:generate
  php artisan migrate
  npm install
```

Sie sollten jetzt eine vollständige Laravel-Installation haben und können die Docker-Shell wieder verlassen.

### Verzeichnisstruktur

.
 * [laravel_docker](./laravel_docker)
   * [docker](./docker)
      * [nginx](./nginx)
	     * [default.conf](./laravel_docker/docker/nginx/default.conf)
      * [php](./php)
	     * [default.conf](./laravel_docker/docker/php/default.conf)
   * [src](./src)
 * [.env](./.env)
 * [README.md](./README.md)
 
### Nutzung

Sobald Sie die Docker-Container gestartet haben, können Sie auf die Anwendung zugreifen über:
```
http://localhost:8080
```
### TO-DO
Aufgrund von Zeitmangel (und Problemen mit Laravel Mix/Vite unter Docker) wurden eine Reihe von Funktionen nicht implementiert.  

* Die Update-Funktion funktioniert noch nicht.
* Paging funktioniert noch nicht.
* Die Löschfunktion funktioniert noch nicht.
* Das CSS (und damit das responsive Design) funktioniert noch nicht.

## English

This repository contains the vue.js / Laravel application as requested by Kosatec.  This comes with a complete Docker configuration to produce a laravel_docker containing nginx, php 8.2 and mysql.


### Installation
Create a separate subdirectory, and clone the repository into there.  

```
git clone https://github.com/steve-dix/kosatec-aufgabe.git
```

There is only one branch, main, so you do not need to checkout anything.

Assuming you have installed Docker, the containers can be built with the following command.

```
docker compose -f docker-compose.yaml up --build -d
```

Once you have built the containers, you must enter the php container with the following commands :

```
cd laravel-docker
docker-compose exec -it php bash
   php artisan key:generate
   php artisan migrate
   npm install
```

You should now have a complete laravel installation, and can exit the docker shell.

### Directory Structure

.
 * [laravel_docker](./laravel_docker)
   * [docker](./docker)
      * [nginx](./nginx)
	     * [default.conf](./laravel_docker/docker/nginx/default.conf)
      * [php](./php)
	     * [default.conf](./laravel_docker/docker/php/default.conf)
   * [src](./src)
 * [.env](./.env)
 * [README.md](./README.md)
 
 ### Use
 
 Once you have started the Docker containers, you will be able to access the application through
 
```
http://localhost:8080
```

### TO-DO
Due to time limitations (and problems with Laravel mix/Vite under docker), A number of features were not implemented.  

* The Update function does not work.
* Paging does not yet work.
* Deletion does not yet work.
* The css (and therefore responsive design) is not working
 
