# cheat_api informations

## database models:

users:
-----
userid
username
useremail
userpassword
userrank
userdatetime

tokens:
-------
tokenid
userid
tokencode
tokendatetimestart
tokendatetimeend

rooms:
-----
roomid
roomname | rootroom
roomadminid
roomdatetime

roomusers
----------
userid
roomid
activeepoch

messages
--------
messageid
userid
roomid
message
messagedatetime

## REST API endpoints:

USERS
-----
Query:                 Auth.      method      Expected Inputs:
?users=login            no         POST       { "usernameoremail", "userpassword" }
?users=insert           no         POST       { "username", "useremail", "userpassword", "userrank" }
?users=userdataid       yes        POST       { "userid" }
---
?users=userdata         yes        GET


CHEAT
-----
Query:                 Auth.      method      Expected Inputs:
?cheat=refresh          yes        POST       { "userid", "roomid" }
# refresh messages.
?cheat=insertmessage    yes        POST       { "userid", "roomid", "messagetext" }
# inserted message.
?cheat=useriddelrooms   no         POST       { "userid" }
# When user quit delete about roomusers userid.
