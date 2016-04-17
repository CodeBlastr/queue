<?php
namespace CodeBlastrQueue\Mailer\Transport;

use Cake\Mailer\AbstractTransport;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;

/**
 * Class QueueTransport
 *
 * @package CodeBlastrQueue\Mailer\Transport
 * @url help from : https://github.com/dereuromark/cakephp-queue/blob/master/src/Mailer/Transport/QueueTransport.php
 */

class QueueTransport extends AbstractTransport
{
    public function send(Email $email)
    {
        if (!empty($this->_config['queue'])) {
            $this->_config = $this->_config['queue'] + $this->_config;
            $email->config((array)$this->_config['queue'] + ['queue' => []]);
            unset($this->_config['queue']);
        }
        $transport = $this->_config['transport'];
        $email->transport($transport);
        $queue = TableRegistry::get('CodeBlastrQueue.Queue');
        $result = $queue->createJob('Email', ['transport' => $transport, 'settings' => $email]);
        $result['headers'] = '';
        $result['message'] = '';
        return $result;
    }
}