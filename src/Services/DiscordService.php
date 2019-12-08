<?php


namespace App\Services;

use App\Entity\User;
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

    private $avatarPath;

    public function __construct($botToken, $guildId, $avatarPath)
    {
        $this->discordServer = new DiscordClient(['token' => $botToken]);
        $this->guildId = intval($guildId);
        $this->avatarPath = $avatarPath;
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

    /**
     * @param $roleId
     * @return bool|string
     */
    public function getGuildRoleByRoleId($roleId)
    {
        $guildRoles = $this->getGuildRoles();

        foreach ($guildRoles as $role){
            if($role->id == $roleId)
            {
                return 'ROLE_'.strtoupper($role->name);
            }
        }

        return false;
    }

    /**
     * @param $discordId
     * @param $hash
     * @return string
     */
    public function getAvatarPath($discordId, $hash)
    {
        if(!empty($hash)) {
            return $this->avatarPath . "/" . $discordId . "/" . $hash . ".png";
        }
        return 'img/plain_avatar.png';
    }

    /**
     * @param User $dbUser
     * @param array $discordUser
     */
    public function compareUserDataSets($dbUser, $discordUser)
    {
        if($dbUser->getUsername() != $discordUser->getUsername()) {
            return false;
        }

        if($dbUser->getEmail() != $discordUser->getEmail()) {
            return false;
        }

        if($dbUser->getAvatar() != $this->getAvatarPath($discordUser->getId(), $discordUser->getAvatarHash())){
            return false;
        }

        return true;
    }

    /**
     * @return \RestCord\Model\Guild\GuildMember[]
     */
    public function getMemberList()
    {
        return $this->discordServer->guild->listGuildMembers([
            'guild.id' => $this->guildId,
            'limit' => 200
        ]);
    }
}