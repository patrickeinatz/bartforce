<?php


namespace App\Services;

use RestCord\DiscordClient;

class DiscordService
{
    /**
     * @var DiscordClient
     */
    private $discordServer;

    /**
     * @var $guildId int
     */
    private $guildId;

    public function __construct($botToken, $guildId)
    {
        $this->discordServer = new DiscordClient(['token' => $botToken]);
        $this->guildId = intval($guildId);
    }

    /**
     * @param $userId
     * @return \RestCord\Model\Guild\GuildMember
     */
    public function getMemberData($userId)
    {
        return $this->discordServer->guild->getGuildMember([
            'guild.id' => $this->guildId,
            'user.id' => intval($userId)
        ]);
    }

    /**
     * @return \RestCord\Model\Permissions\Role[]
     */
    public function getGuildRoles()
    {
        return $this->discordServer->guild->getGuildRoles([
            'guild.id' => $this->guildId
        ]);
    }


}