# Directory for the stage environment
$stageDir = "C:\Users\VirgileCOUDERT\Documents\CESI\Vide-grenier-triple-install\vide-grenier-stage"

# Function to update the stage environment
function Update-Stage {
    Write-Host "Updating stage environment in $stageDir..."

    # Navigate to the stage environment directory
    Set-Location $stageDir

    # Stop the containers
    docker-compose -f docker-compose.stage.yml stop

    # Remove node_modules folder if it exists
    if (Test-Path "src\node_modules") {
        Remove-Item -Recurse -Force "src\node_modules"
    } else {
        Write-Host "node_modules folder not found, skipping removal."
    }

    # Pull the latest changes from the Git repository
    git fetch origin
    git reset --hard origin/Feature-Double-install-2

    # Rebuild and restart the containers
    docker-compose -f docker-compose.stage.yml up --build -d

    # Navigate back to the initial directory
    Pop-Location

    Write-Host "Stage environment updated successfully."
}

# Update stage environment
Update-Stage
