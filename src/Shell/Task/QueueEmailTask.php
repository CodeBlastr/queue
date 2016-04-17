<?php
namespace CodeBlastrQueue\Shell\Task;

use Cake\Console\Shell;
use Cake\Log\Log;
use Cake\Mailer\Email;

/**
 * Class QueueEmailTask
 * @package CodeBlastrQueue\Shell\Task
 */
class QueueEmailTask extends Shell
{
    public $modelClass = 'CodeBlastrQueue.QueuedTasks';

    /**
     * List of default variables for EmailComponent
     *
     * @var array
     */
    public $defaults = [
        'to' => null,
        'from' => null,
    ];

    public function settings($settings)
    {
        foreach ($settings as $method => $setting) {

            if (strpos($method, '_') === 0 && $cleaned = str_replace('_', '', $method)) {
                unset($settings[$method]);
                $settings[$cleaned] = $setting;
            }
            if (@is_array($settings[$method])) {
                $settings[$method] = $this->settings($settings[$method]);
            }
        }
        // handle special cases where there is no function that matches up to the config setting in Cake/Mailer/Email
        if (!empty($settings['viewConfig'])) {
            $settings['template'] = [$settings['viewConfig']['template'] => $settings['viewConfig']['layout']];
        }
        return array_merge($this->defaults, $settings);
    }

    /**
     * @param $data
     * @param null $id
     */
    public function run($data, $id = null)
    {
        $email = new Email($data['transport']);
        $settings = $this->settings($data['settings']);
        foreach ($settings as $method => $setting) {
            if (method_exists($email, $method)) {
                call_user_func_array([$email, $method], (array)$setting);
            }
        }
        $message = null;
        if (!empty($data['vars'])) {
            if (isset($data['vars']['content'])) {
                $message = $data['vars']['content'];
            }
            $email->viewVars($data['vars']);
        }
        //some debuggging info
        //debug($email->template());
        //debug($email->to());
        return $email->send();
    }
}


