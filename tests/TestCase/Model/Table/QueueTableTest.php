<?php
namespace CodeBlastrQueue\Test\TestCase\Model\Table;

use CodeBlastrQueue\Model\Table\QueueTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Mailer\Email;

/**
 * CodeBlastrQueue\Model\Table\QueueTable Test Case
 */
class QueueTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \CodeBlastrQueue\Model\Table\QueueTable
     */
    public $Queue;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.CodeBlastrQueue.queue'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Queue') ? [] : ['className' => 'CodeBlastrQueue\Model\Table\QueueTable'];
        $this->Queue = TableRegistry::get('Queue', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Queue);
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
        $this->assertTrue(!empty($this->Queue->find('all')->toArray()));
    }

    public function testRequestJob() {
        $email = new Email();
        $email->template('default', 'default')
            ->to('sample@example.com')
            ->subject('About Me')
            ->send();
        $this->assertTrue(!empty($this->Queue->requestJob()));
    }
}
