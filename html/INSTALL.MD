#Install
## Downloads
- Download the latest PHP from https://windows.php.net/download, x64 Thread Safe or Not Does not matter
- Download the Visual C++ Runtime for the version you picked .. there is a good change this is already installed on your pc, so if it said VC16 64 you have to download VC16 https://support.microsoft.com/en-us/topic/the-latest-supported-visual-c-downloads-2647da03-1eea-4433-9aff-95f26a218cc0
- Download a copy of this project https://github.com/HermanRas/OrgChart_VipPreople 

## Install
- install VC++ as per normal
- Exstract php.zip to c:\php\
- confirm c:\php\php.exe is the correct path

# Configure
## App Config
- extract OrgChart_VipPeople anywhere you like
- edit the Start.bat in the extracted folder, if you want to change the web port, you can change it here
- edit Config/db.php, enter the details of your SQL server
- run start.bat