# PUP

A Simple PHP server for uploading files via Curl.
![PUP](corgi.jpeg)

## Server Installation:
	
    cd /var/www/html/
	git clone https://github.com/osteth/PUP.git

Once cloned open index.php and modify line #75 with the domain were you have installed pup.

	echo " File saved to: Enter Server Domain Name Here" .substr($newFileName, 10) . "\n";
Ensure that apache has write permissions to /var/www/html/pup
    
## Client Setup:
	nano ~/.bashrc
Chage my.domain.com to the domain where you have installed your pup server and add these lines to the bottom of your nano ~/.bashrc file
    
    phpupload() {
 	curl -i -X POST -H "Content-Type: multipart/form-data" -F "uploaded-file=@$1" $2
	}
	alias pup='phpupload'

	myphpupload() {
 	curl -i -X POST -H "Content-Type: multipart/form-data" -F "uploaded-file=@$1" 					my.domain.com/pup/index.php
	}
	alias mypup='phpuploadDB'
Activate changes to .bashrc
	
    source ~/.bashrc
    
### Usage

To upload to a pup server other than your own

	pup file-to-be-uploaded address-of-pup-server
  
To upload to your own pup server

    mypup file-to-be-uploaded

Upload via Raw curl without Bashrc aliases
	 
     curl -i -X POST -H "Content-Type: multipart/form-data" -F "uploaded-file=@File-to-Upload" pup.server.address/pup/index.php
     
## Needed Improvements

* Squelch HTML header responses to clean up the response
* Add security checks and filetype limiting
* Protect uploaded files agains being executed
* Better installation process

### Special Thanks:

Big thanks to Glitch for all of his help!