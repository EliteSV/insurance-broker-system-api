#!/bin/bash

# Load the Elastic Beanstalk environment variables
export $(cat /opt/elasticbeanstalk/deployment/env | xargs) 

# Change to the directory of your Laravel application
cd /var/app/current

# Run the Laravel scheduler and log the output
php artisan schedule:run >> /var/log/scheduler.log 2>&1