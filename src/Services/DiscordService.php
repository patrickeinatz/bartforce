<?php


namespace App\Services;

use App\Entity\User;
use RestCord\DiscordClient;

class DiscordService
{

    private const DISCORD_VOICE_CHANNEL_TYPE = 2;

    private const DISCORD_TEXT_CHANNEL_TYPE = 0;

    private const FORUM_CAT_PARENT_ID = 669578861587202088;


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
     * @return DiscordClient
     */
    public function getDiscordServer(): DiscordClient
    {
        return $this->discordServer;
    }

    /**
     * @return int
     */
    public function getGuildId(): int
    {
        return $this->guildId;
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
     * @return bool
     */
    public function compareUserDataSets(User $dbUser, $discordUser):bool
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


    /**
     * @return \RestCord\Model\Channel\Channel[]
     */
    public function getAllChannels()
    {
        return $this->discordServer->guild->getGuildChannels([
            'guild.id' => $this->guildId
        ]);
    }

    /**
     * @param string $channelTitle
     */
    public function createNewTextChannel(string $channelTitle)
    {
        return ($this->discordServer->guild->createGuildChannel([
            'guild.id' => $this->guildId,
            'name' => $channelTitle,
            'type' => self::DISCORD_TEXT_CHANNEL_TYPE,
            'parent_id' => self::FORUM_CAT_PARENT_ID,
        ]))->id;
    }

    /**
     * @param string $channelId
     */
    public function deleteChannel(string $channelId)
    {
        $this->discordServer->channel->deleteOrcloseChannel([
            'channel.id' => intval($channelId),
        ]);
    }

    public function sendChannelMsg(string $channelId, string $content)
    {
        $this->discordServer->channel->createMessage([
            'channel.id' => intval($channelId),
            'content' => $content,
        ]);
    }

}