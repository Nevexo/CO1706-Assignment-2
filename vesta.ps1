# A script to publish /src/ to vesta.uclan.ac.uk

$FTPhost = "ftp://vesta.uclan.ac.uk"
$SourceDirectory = "src/"

$Client = New-Object System.Net.WebClient
$Username, $Password = (Get-Content("secrets.csv")) -split ","
$Client.Credentials = New-Object System.Net.NetworkCredential($Username, $Password)

$files = Get-ChildItem $SourceDirectory

$Confirm = Read-Host "Files on Vesta will be overwritten, confirm? [y/N]"
if ($Confirm -ne "y") {exit}

Write-Host "Uploading source to Vesta..."

foreach ($file in $files) {
  $FileName = Split-Path -Path $file -Leaf -Resolve

  Write-Host "Pushing: $FileName => $FTPHost/$FileName"
  $Client.UploadFile("$FTPHost/$FileName", $file.FullName)
}

$Client.Dispose()
Write-Host "Successfully pushed to https://vesta.uclan.ac.uk/~$Username"