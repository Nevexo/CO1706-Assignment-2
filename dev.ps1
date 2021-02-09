param (
    [switch]$Up,
    [switch]$Down,
    [switch]$KillDocker
)

if ($Up) {
  $DockerDesktopProcess = Get-Process -Name 'Docker Desktop' -ErrorAction SilentlyContinue
  if (-Not $DockerDesktopProcess) {
    Write-Host "Docker is not running, attempting to start."
    Start-Process "C:\Program Files\Docker\Docker\Docker Desktop.exe"
    Write-Host "Waiting 10 seconds for docker to start"
    Start-Sleep -s 10
  }

  Write-Host "Starting containers"
  docker-compose up -d
}


if ($Down) {
  Write-Host "Bringing down containers..."
  docker-compose down

  if ($KillDocker) {
    Write-Host "Shutting down Docker..."
    Stop-Process -Name "Docker Desktop"
  }
}
