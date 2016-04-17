<?php

namespace CodeBlastrQueue\Model\Table;

use Cake\ORM\Table;
use Cake\Database\Schema\Table as Schema;


class QueueTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->table('queue');
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


    public function requestJob()
    {
        $findCond = [
            'conditions' => [
                'completed' => 0,
            ],
            'order' => [
                'created ASC',
                'id ASC',
            ],
            'limit' => 3,
        ];
        return $data = $this->find('all', $findCond)->all()->toArray();
    }

}