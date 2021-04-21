## LOOOOP powered by Laravel

## Setup environment

- Copy environment config files
```
cp docker/.env.example docker/.env
cp .env.example .env
```

- Build and run the project
```
cd docker
sudo make init
```

- Make sure all containers started correctly
```
sudo docker-compose ps
```
