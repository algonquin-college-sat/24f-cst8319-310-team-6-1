<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Taskrouter
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */


namespace Twilio\Rest\Taskrouter\V1\Workspace;

use Twilio\Exceptions\TwilioException;
use Twilio\Options;
use Twilio\Values;
use Twilio\Version;
use Twilio\InstanceContext;
use Twilio\Serialize;


class WorkspaceStatisticsContext extends InstanceContext
    {
    /**
     * Initialize the WorkspaceStatisticsContext
     *
     * @param Version $version Version that contains the resource
     * @param string $workspaceSid The SID of the Workspace to fetch.
     */
    public function __construct(
        Version $version,
        $workspaceSid
    ) {
        parent::__construct($version);

        // Path Solution
        $this->solution = [
        'workspaceSid' =>
            $workspaceSid,
        ];

        $this->uri = '/Workspaces/' . \rawurlencode($workspaceSid)
        .'/Statistics';
    }

    /**
     * Fetch the WorkspaceStatisticsInstance
     *
     * @param array|Options $options Optional Arguments
     * @return WorkspaceStatisticsInstance Fetched WorkspaceStatisticsInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(array $options = []): WorkspaceStatisticsInstance
    {

        $options = new Values($options);

        $params = Values::of([
            'Minutes' =>
                $options['minutes'],
            'StartDate' =>
                Serialize::iso8601DateTime($options['startDate']),
            'EndDate' =>
                Serialize::iso8601DateTime($options['endDate']),
            'TaskChannel' =>
                $options['taskChannel'],
            'SplitByWaitTime' =>
                $options['splitByWaitTime'],
        ]);

        $payload = $this->version->fetch('GET', $this->uri, $params);

        return new WorkspaceStatisticsInstance(
            $this->version,
            $payload,
            $this->solution['workspaceSid']
        );
    }


    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Taskrouter.V1.WorkspaceStatisticsContext ' . \implode(' ', $context) . ']';
    }
}
