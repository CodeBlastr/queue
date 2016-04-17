<?php
namespace CodeBlastrQueue\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * QueuedFixture
 *
 */
class QueueFixture extends TestFixture
{

    public $table = 'queue';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null],
        'type' => ['type' => 'string', 'length' => 255, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'subject' => ['type' => 'string', 'length' => 255, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null, 'fixed' => null],
        'message' => ['type' => 'text', 'default' => null, 'null' => true, 'comment' => null],
        'completed' => ['type' => 'boolean', 'length' => null, 'default' => 0, 'null' => false, 'comment' => null],
        'reference' => ['type' => 'text', 'default' => null, 'null' => true, 'comment' => null],
        'data' => ['type' => 'text', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'creator_id' => ['type' => 'uuid', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'modifier_id' => ['type' => 'uuid', 'length' => null, 'default' => null, 'null' => true, 'comment' => null, 'precision' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [];
}
