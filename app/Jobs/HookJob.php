<?php

namespace App\Jobs;

use App\HookError;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class HookJob extends Job
{
    /**
     * @var string $url
     */
    private $url;

    /**
     * @var int $hookId
     */
    private $hookId;

    /**
     * @var int threshold
     */
    private $threshold;

    /**
     * @var integer
     */
    private $timeout;

    /**
     * @var integer
     */
    private $retryDelay;

    /**
     * HookJob constructor.
     * @param string $url
     * @param int $hookId
     * @param int $threshold
     */
    public function __construct(string $url, int $hookId, int $threshold)
    {
        $this->url = $url;
        $this->hookId = $hookId;
        $this->threshold = $threshold;
        $this->timeout = config("hook.timeout");
        $this->retryDelay = config("hook.retryDelay");
    }

    /**
     * Handle tries to perform a hook until gets a success response.
     */
    public function handle()
    {
        $httpClient = new Client([
            'timeout' => $this->timeout,
        ]);

        for ($i = 1; $i <= $this->threshold; $i++) {
            try {
                $response = $httpClient->get($this->url);
            } catch (GuzzleException $exception) {
                if ($i == $this->threshold) {
                    $this->reportHookError($exception->getCode(), $exception->getMessage());

                    return;
                }
                sleep($this->retryDelay);
                continue;
            }

            if ($response->getStatusCode() >= 200 || $response->getStatusCode() <= 299) {
                return;
            }

            if ($i == $this->threshold) {
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
        $hookError->hook_id = $this->hookId;
        $hookError->status_code = $statusCode;
        $hookError->response_body = $responseBody;
        $hookError->save();
    }
}
