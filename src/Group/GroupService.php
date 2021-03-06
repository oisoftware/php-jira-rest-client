<?php

namespace JiraRestApi\Group;

/**
 * Class to perform all groups related queries.
 * @package JiraRestApi\Group
 */
class GroupService extends \JiraRestApi\JiraClient
{
    private $uri = '/group';

    /**
     * Function to get group.
     *
     * @param array $paramArray Possible values for $paramArray 'username', 'key'.
     *   "Either the 'username' or the 'key' query parameters need to be provided".
     *
     * @return Group class
     */
    public function get($paramArray)
    {
        $queryParam = '?'.http_build_query($paramArray);

        $ret = $this->exec($this->uri.$queryParam, null);

        $this->log->addInfo("Result=\n".$ret);

        return $this->json_mapper->map(
                json_decode($ret), new User()
        );
    }

    /**
     * Get users from group
     *
     * @param $paramArray groupname, includeInactiveUsers, startAt, maxResults
     * @return GroupSearchResult
     * @throws \JiraRestApi\JiraException
     * @throws \JsonMapper_Exception
     */
    public function getMembers($paramArray)
    {
        $queryParam = '?' . http_build_query($paramArray);

        $ret = $this->exec($this->uri . '/member'.$queryParam, null);

        $this->log->addInfo("Result=\n".$ret);

        $userData = json_decode($ret);

        $res = $this->json_mapper->map($userData, new GroupSearchResult());

        return $res;
    }

    /**
     * Creates a group by given group parameter
     *
     * @param $group \JiraRestApi\Group\Group
     * @return array
     * @throws \JiraRestApi\JiraException
     * @throws \JsonMapper_Exception
     */
    public function createGroup($group)
    {
        $data = json_encode($group);

        $ret = $this->exec($this->uri, $data);

        $this->log->addInfo("Result=\n".$ret);

        $groupData = json_decode($ret);
        $groups = [];

        $group = $this->json_mapper->map(
            json_decode($ret), new Group()
        );

        return $group;
    }
}
