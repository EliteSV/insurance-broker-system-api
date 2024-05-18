#!/bin/bash

CURRENT_PATH=$(pwd)

CRON_JOB="* * * * * cd $CURRENT_PATH && php artisan schedule:run >> /dev/null 2>&1"

(crontab -l | grep -F "$CRON_JOB") || (crontab -l ; echo "$CRON_JOB") | crontab -

echo "Cron job added to run every minute: $CRON_JOB"
