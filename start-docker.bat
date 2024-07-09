@echo off
REM Script to start Docker Compose with build

echo -- Starting Docker Compose with build -- 
docker-compose down
docker-compose up --build


