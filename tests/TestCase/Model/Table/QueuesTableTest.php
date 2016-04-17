<?php
namespace CodeBlastrQueue\Test\TestCase\Model\Table;

use CodeBlastrQueue\Model\Table\QueuesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Mailer\Email;

/**
 * CodeBlastrQueue\Model\Table\QueuesTable Test Case
 */
class QueuesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \CodeBlastrQueue\Model\Table\QueuesTable
     */
    public $Queues;

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
        $config = TableRegistry::exists('Queues') ? [] : ['className' => 'CodeBlastrQueue\Model\Table\QueuesTable'];
        $this->Queues = TableRegistry::get('Queues', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Queues);
        parent::tearDown();
    }

    public function testCreateJob() {
        // this creates a job
        $email = new Email();
        $email->template('default', 'default')
            ->to('sample@example.com')
            ->subject('About Me')
            ->send();
        // now we'll request it
        $this->assertTrue(!empty($this->Queues->find('all')->toArray()));
    }

    public function testRequestJob() {
        $email = new Email();
        $email->template('default', 'default')
            ->to('sample@example.com')
            ->subject('About Me')
            ->send();
        $this->assertTrue(!empty($this->Queues->requestJob()));
    }
}
