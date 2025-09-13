<?php

namespace App\Packages\DiscordLogger\Converters;

use Illuminate\Support\Arr;
use MarvinLabs\DiscordLogger\Converters\AbstractRecordConverter;
use MarvinLabs\DiscordLogger\Contracts\DiscordWebHook;
use MarvinLabs\DiscordLogger\Discord\Message;

class CustomRecordConverter extends AbstractRecordConverter
{
    /**
     * @throws \MarvinLabs\DiscordLogger\Discord\Exceptions\ConfigurationIssue
     */
    public function buildMessages(array $record): array
    {
        $message = Message::make();

        $this->addGenericMessageFrom($message);
        $this->addMainContent($message, $record);
        $this->addContextBlock($message, $record);

        return [$message];
    }

    protected function addMainContent(Message $message, array $record): void
    {
        $timestamp = $record['datetime']->format('d/m/Y - H:i:s');

        $emoji = $this->getRecordEmoji($record);

        $levelName = $record['level_name'];

        $channel = $record['channel'];

        $mainMessage = $record['message'];

        $content = $emoji ? "$emoji " : "";

        $content .= "**[$timestamp] $channel.$levelName**\n";

        $content .= "```\n$mainMessage\n```";

        $message->content($content);
    }

    protected function addContextBlock(Message $message, array $record): void
    {
        $context = Arr::except($record['context'] ?? [], ['exception']);

        if (empty($context)) {
            return;
        }

        $jsonContext = json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $contextContent = "\n**Response:**\n```json\n$jsonContext\n```";

        $currentContent = $message->content ?? '';

        if (strlen($currentContent . $contextContent) > DiscordWebHook::MAX_CONTENT_LENGTH) {
            // Se exceder o limite, criar uma mensagem separada
            $contextMessage = Message::make();
            $this->addGenericMessageFrom($contextMessage);
            $contextMessage->content("**Response:**\n```json\n$jsonContext\n```");
            return;
        }

        $message->content($currentContent . $contextContent);
    }
}
