#!/bin/bash 

# make sure you've copied the default file from the nginx site-enabled and edited the root directory to 
#/home/site/wwwroot/public 
cp /home/site/default /etc/nginx/sites-enabled/default 

service nginx restart