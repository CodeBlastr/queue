# queue
CakePHP 3 plugin for stacking messages into a queue for sending later.


## installation
composer require codeblastr/queue

## usage (cron)
*/10  *    *    *    *  cd /full/path/to/app && bin/cake CodeBlastrQueue.Queue runworker
