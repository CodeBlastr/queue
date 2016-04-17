<?php
namespace CodeBlastrQueue\Shell;

use Cake\Console\Shell;
use Cake\Core\App;
//use Cake\Core\App;
use Cake\Core\Configure;
//use Cake\Core\Plugin;
use Cake\Filesystem\Folder;
//use Cake\I18n\Number;
//use Cake\Utility\Inflector;
use Cake\Mailer\Email;
use Exception;


/**
 * Main shell to init and run queue workers.
 *
 * @author MGriesbach@gmail.com
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link http://github.com/MSeven/cakephp_queue
 */
class QueueShell extends Shell
{
    /**
     * @var string
     */
    public $modelClass = 'CodeBlastrQueue.Queues';

    /**
     * Initialize method
     */
    public function initialize()
    {
        $paths = App::path('Shell/Task', 'CodeBlastrQueue');
        foreach ($paths as $path) {
            $Folder = new Folder($path);
            $res = array_merge($this->tasks, $Folder->find('Queue.+\.php'));
            foreach ($res as &$r) {
                $r = 'CodeBlastrQueue.' . basename($r, 'Task.php');
            }
            $this->tasks = $res;
        }
        parent::initialize();
    }

    /**
     * Output some basic usage Info.
     *
     * @return void
     */
    public function main()
    {
        $this->out('CodeBlastr Queue Plugin:');
        $this->hr();
        $this->out('Usage:');
        $this->out('	cake CodeBlastr.Queue help');
        $this->out('		-> Display this Help message');
        $this->out('	cake Queue.Queue add <taskname>');
        $this->out('		-> Try to call the cli add() function on a task');
        $this->out('		-> tasks may or may not provide this functionality.');
        $this->out('	cake Queue.Queue runworker');
        $this->out('		-> run a queue worker, which will look for a pending task it can execute.');
        $this->out('		-> the worker will always try to find jobs matching its installed Tasks');
        $this->out('		-> see "Available Tasks" below.');
        $this->out('	cake Queue.Queue stats');
        $this->out('		-> Display some general Statistics.');
        $this->out('	cake Queue.Queue clean');
        $this->out('		-> Manually call cleanup function to delete task data of completed tasks.');
    }

    public function runworker()
    {
        $starttime = time();

        debug($this->Queues->requestJob());

        $this->out('[' . date('Y-m-d H:i:s') . '] Looking for Job ...');
        exit;
    }

    public function testrun()
    {
        $starttime = time();
        $this->out('[' . date('Y-m-d H:i:s') . '] Creating Job ...');
        // this creates a job (try different settings here for testing)
        $email = new Email();
        $email->template('CodeBlastrQueue.testeree', 'CodeBlastrQueue.testeroo')
            ->to('email@example.com')
            ->subject('About Me')
            ->emailFormat('both')
            ->send();

        $queus = $this->Queues->requestJob();
        foreach ($queus as $queue) {
            try {
                $this->out('Running Job of type "' . $queue['type'] . '"');
                $taskname = 'Queue' . $queue['type'];
                $return = $this->{$taskname}->run($queue['data']);
                $this->Queues->markJobDone($queue['id']);
                $this->out('Job Finished.');
            } catch (Exception $e) {
                $failureMessage = $e->getMessage();
                if (!empty($this->{$taskname}->failureMessage)) {
                    $failureMessage = $this->{$taskname}->failureMessage;
                }
                $this->Queues->markJobFailed($queue['id'], $failureMessage);
                $this->out('Job did not finish, requeued.');
            }
        }
        $endtime = time();
        $this->out('Test run finished.');
    }
}