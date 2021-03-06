<?php

namespace App\Jobs;

use App\Hook;
use App\HookError;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class HookJob extends Job
{
    /**
     * @var Hook $hook
     */
    private $hook;

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var integer
     */
    private $retryDelay;

    /**
     * HookJob constructor.
     * @param Hook $hook
     */
    public function __construct(Hook $hook)
    {
        $this->hook = $hook;
        $this->retryDelay = config("hook.retryDelay");
        $this->httpClient = new Client([
            'timeout' => config("hook.timeout"),
        ]);
    }

    /**
     * Handle tries to perform a hook until gets a success response.
     */
    public function handle()
    {
        for ($i = 1; $i <= $this->hook->threshold; $i++) {
            try {
                $response = $this->httpClient->get($this->hook->url);
            } catch (GuzzleException $exception) {
                if ($i == $this->hook->threshold) {
                    $this->reportHookError($exception->getCode(), $exception->getMessage());

                    return;
                }
                sleep($this->retryDelay);
                continue;
            }

            if ($response->getStatusCode() >= 200 || $response->getStatusCode() <= 299) {
                return;
            }

            if ($i == $this->hook->threshold) {
                $this->reportHookError($response->getStatusCode(), $response->getBody());

                return;
            }

            sleep($this->retryDelay);
        }
    }

    /**
     * Saves a hook error for this hook.
     *
     * @param $statusCode
     * @param $responseBody
     */
    private function reportHookError($statusCode, $responseBody)
    {
        $hookError = new HookError();
        $hookError->hook_id = $this->hook->id;
        $hookError->status_code = $statusCode;
        $hookError->response_body = $responseBody;
        $hookError->save();
    }
}
