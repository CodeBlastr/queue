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
        'plugin.CodeBlastrQueue.Queue'
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

//        $config = TableRegistry::exists('Queue') ? [] : ['className' => 'CodeBlastrQueue\Model\Table\QueueTable'];
//        $this->Queue = TableRegistry::get('Queue', $config);
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
        $queue = TableRegistry::get('CodeBlastrQueue.Queue');
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
