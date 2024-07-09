# Directory for the dev environment
$devDir = "C:\Users\VirgileCOUDERT\Documents\CESI\Vide-grenier-triple-install\vide-grenier-dev"

# Function to update the dev environment
function Update-Dev {
    Write-Host "Updating dev environment in $devDir..."

    # Navigate to the dev environment directory
    Set-Location $devDir

    # Stop the containers
    docker-compose -f docker-compose.dev.yml stop

    # Remove node_modules folder if it exists
    $nodeModulesPath = Join-Path $devDir "node_modules"
    if (Test-Path $nodeModulesPath) {
        Write-Host "Removing node_modules directory at $nodeModulesPath"
        Remove-Item -Recurse -Force $nodeModulesPath
    } else {
        Write-Host "node_modules directory not found at $nodeModulesPath, skipping removal."
    }

    # Pull the latest changes from the Git repository
    git fetch origin
    git reset --hard origin/Feature-Double-install-1

    # Rebuild and restart the containers
    docker-compose -f docker-compose.dev.yml up --build -d

    # Navigate back to the initial directory
    Pop-Location

    Write-Host "Dev environment updated successfully."
}

# Update dev environment
Update-Dev
