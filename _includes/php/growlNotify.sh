 #!/bin/bash

HOST=$1
SUBJECT=$2
SENDER=$3
PASS=$4

# Location of growlnotify (located in Growl distribution)
GROWLNOTIFY=/usr/local/bin/growlnotify
 
$GROWLNOTIFY -H "$HOST" -P $PASS -u  -t "$SUBJECT" -m "$SENDER"


