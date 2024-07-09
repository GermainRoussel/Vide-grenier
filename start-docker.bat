@echo off
REM Script to start Docker Compose with build

echo -- Suppression des anciens containers -- 
docker-compose down

echo -- Suppression du dossier node_modules --
rmdir /s /q node_modules

echo -- Lancement du build docker -- 
docker-compose up --build

