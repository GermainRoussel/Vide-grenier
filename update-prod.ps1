# Directory for the prod environment
$prodDir = "C:\Users\VirgileCOUDERT\Documents\CESI\Vide-grenier-triple-install\vide-grenier-prod"

# Function to update the prod environment
function Update-Prod {
    Write-Host "Updating prod environment in $prodDir..."

    # Navigate to the prod environment directory
    Set-Location $prodDir

    # Stop the containers
    docker-compose -f docker-compose.prod.yml stop

    # Remove node_modules folder if it exists
    if (Test-Path "src\node_modules") {
        Remove-Item -Recurse -Force "src\node_modules"
    } else {
        Write-Host "node_modules folder not found, skipping removal."
    }

    # Pull the latest changes from the Git repository
    git fetch origin
    git reset --hard origin/main

    # Rebuild and restart the containers
    docker-compose -f docker-compose.prod.yml up --build -d

    # Navigate back to the initial directory
    Pop-Location

    Write-Host "Prod environment updated successfully."
}

# Update prod environment
Update-Prod
