<?php

namespace CodeBlastrQueue\Model\Table;

use Cake\ORM\Table;
use Cake\Database\Schema\Table as Schema;
use Exception;


class QueuesTable extends Table
{
    /**
     * Limit the tries that you'll run before
     * you stop trying to send a message in the queue
     *
     * @var int
     */
    public $maxTries = 10;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->table('queues');
        $this->displayField('to');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
    }

    /**
     * Initialize Schema
     *
     * @param Schema $schema
     * @return Schema
     */
    protected function _initializeSchema(Schema $schema)
    {
        $schema->columnType('data', 'json');
        $schema->columnType('stats', 'json');
        return $schema;
    }

    /**
     * Add a new Job to the Queue.
     *
     * @param string $jobName   QueueTask name
     * @param array|null $data      any array
     * @param array|null $notBefore optional date which must not be preceded
     * @param string|null $reference An optional reference string.
     * @return \Cake\ORM\Entity Saved job entity
     * @throws \Exception
     */
    public function createJob($jobName, $data = null, $notBefore = null, $reference = null)
    {
        $data = [
            'type' => $jobName,
            'data' => $data,
            'reference' => $reference,
        ];
        if ($notBefore !== null) {
            $data['notbefore'] = new Time($notBefore);
        }
        $queue = $this->newEntity($data);
        if ($queue->errors()) {
            throw new Exception('Invalid entity data');
        }
        return $this->save($queue);
    }

    /**
     * Find a job to do.
     *
     * @return array
     */
    public function requestJob()
    {
        $findCond = [
            'conditions' => [
                'completed' => 0
            ],
            'order' => [
                'created ASC',
                'id ASC',
            ],
            'limit' => 3,
        ];

        $jobs = $this->find('all', $findCond)->all()->toArray();
        for ($i=0; $i<count($jobs); $i++) {
            if ($jobs[$i]['stats']['tries'] >= $this->maxTries) {
                unset($jobs[$i]);
            }
        }
        return $jobs;
    }

    /**
     * Mark job done and add some stats
     *
     * @param $id
     * @return bool|\Cake\Datasource\EntityInterface|mixed
     */
    public function markJobDone($id)
    {
        $entity = $this->get($id);
        $data = ['completed' => true, 'stats' => ['tries' => ($entity->stats['tries'] + 1), 'message' => $entity->stats['message'] . ($entity->stats['tries'] + 1) . '. ' . 'Success!' . PHP_EOL]];
        $this->patchEntity($entity, $data, ['validate' => false]);
        return $this->save($entity);
    }

    /**
     * Job did not complete, save a few stats so we can see what went wrong.
     *
     * @param $id
     * @param null $message
     * @return bool|\Cake\Datasource\EntityInterface|mixed
     */
    public function markJobFailed($id, $message = null)
    {
        $entity = $this->get($id);
        $data = ['completed' => false, 'stats' => ['tries' => ($entity->stats['tries'] + 1), 'message' => $entity->stats['message'] . ($entity->stats['tries'] + 1) . '. ' . $message . PHP_EOL]];
        $this->patchEntity($entity, $data, ['validate' => false]);
        return $this->save($entity);
    }

}