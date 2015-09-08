# PianobarRemoteControl

This project is a hybrid Android/Ios app to remote control for the pianobar music player.  Well, what is pianobar? Pianobar is a command line client for the music streaming service Pandora. This project solves the problem of SSHing into the server and pressing the keys directly.  Instead you can remote control the player through this app.

The project is broken down into two parts, the phone app and the server listening on the network.


## Requirements
- [Ionic](http://ionicframework.com/)
- Php
- [Android SDK](https://developer.android.com/sdk/index.html)
- [Pianobar] (https://github.com/PromyLOPh/pianobar)


## Installation
### Pianobar setup:
```
cd /$HOME/.config/pianobar
```
- Download a copy of the config file here 
```
curl https://raw.githubusercontent.com/PromyLOPh/pianobar/master/contrib/config-example > config
```
- Edit the pianobar config file 
```
nano config
```
- The important bits are to set are username, password, the station to autoplay, event_command, and the fifo file.
```
user = yourusername
password = yourpassword
autostart_station = 2554976246659970499 <--- Yours will be different.
event_command = /path/to/server/docroot/eventcmd.sh
fifo = /path/to/server/docroot/keypresses
```
- Test that pianobar is setup correctly
```
cd server/docroot
./headless_pianobar
```

### Server setup:
```
cd server/docroot
```
- Edit eventcmd.sh with the path to this directory.
- Give it execute permissions 
```
chmod 744 eventcmd.sh
```
- Give exectue permission to headless_pianobar 
```
chmod 744 headless_pianobar
```
- Make the fifo file 
```
mkfifo keypresses
```
- Start the server 
```
php -S 0.0.0.0:8080 index.php
```
- test server in your browser 
```
http://loclhost:8080/info
http://loclhost:8080/action/p  <-- play/pause
http://loclhost:8080/action/n  <-- next
http://loclhost:8080/action/+  <-- like
http://loclhost:8080/action/(  <-- volume down
http://loclhost:8080/action/)  <-- volume up
```

### Client Setup
- Either a)Download the apk from release folder and install it on your phone or b) build the project with ionic. `ionic build`
- Launch the app on your phone.
- Goto setup tab and set the IP address and port number of the server.
- Enjoy music
