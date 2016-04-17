<?php
namespace CodeBlastrQueue\Test\TestCase\Mailer\Transport;

use CodeBlastrQueue\Mailer\Transport\QueueTransport;
use CodeBlastrQueue\Model\Table\QueueTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Mailer\Email;

/**
 *
 *
 */
class QueueTransportTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \CodeBlastrQueue\Mailer\Transport\QueueTransport
     */
    public $QueueTransport;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.CodeBlastrQueue.queues'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->QueueTransport = new QueueTransport();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->QueueTransport);
        parent::tearDown();
    }

    public function testQueue()
    {
        $queue = TableRegistry::get('CodeBlastrQueue.Queues');
        $countBefore = $queue->find('all')->count();

        $email = new Email();
        $email->template('default', 'default')
            ->to('sample@example.com')
            ->subject('About Me')
            ->send();
        if (!empty($email)) {

            $countAfter = $queue->find('all')->count();
        }
        $this->assertTrue($countBefore < $countAfter);
    }
}
