$DockerDesktopProcess = Get-Process 'Docker Desktop'
if (-Not $DockerDesktopProcess) {
  Write-Host "Docker is not running, attempting to start."
  Start-Process "C:\Program Files\Docker\Docker\Docker Desktop.exe"
  Write-Host "Waiting 10 seconds for docker to start"
  Start-Sleep -s 10
}

Write-Host "Starting containers"
docker-compose up -d
