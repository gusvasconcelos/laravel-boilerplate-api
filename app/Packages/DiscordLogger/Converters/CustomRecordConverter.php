<?php

namespace App\Packages\DiscordLogger\Converters;

use Illuminate\Contracts\Config\Repository;
use MarvinLabs\DiscordLogger\Discord\Message;
use MarvinLabs\DiscordLogger\Contracts\DiscordWebHook;
use GusVasconcelos\MarkdownConverter\MarkdownConverter;
use MarvinLabs\DiscordLogger\Converters\AbstractRecordConverter;

class CustomRecordConverter extends AbstractRecordConverter
{
    private MarkdownConverter $markdownConverter;

    public function __construct(
        Repository $config,
    ) {
        parent::__construct($config);

        $this->markdownConverter = new MarkdownConverter();
    }

    /**
     * @throws \MarvinLabs\DiscordLogger\Discord\Exceptions\ConfigurationIssue
     */
    public function buildMessages(array $record): array
    {
        $message = Message::make();

        $emoji = $this->getRecordEmoji($record);

        $timestamp = $record['datetime']->format('d/m/Y - H:i:s');

        $channel = $record['channel'];

        $levelName = $record['level_name'];

        $content = $this->markdownConverter
            ->heading("{$emoji} - **[{$timestamp}] {$channel}.{$levelName}**", 1)
            ->paragraph($record['message'])
            ->heading('📄 Response', 2)
            ->codeBlock(json_pretty($record['context']), 'json');

        if (strlen($content->getContent()) <= DiscordWebHook::MAX_CONTENT_LENGTH) {
            $message->content($content->getContent());

            return [$message];
        }

        $this->markdownConverter->stepBack(2);

        $stackTraceMessage = Message::make()
            ->file(json_pretty($record['context']), 'json', $this->getStacktraceFilename($record));

        $this->addGenericMessageFrom($stackTraceMessage);

        $message->content($content->getContent());

        return [$message, $stackTraceMessage];
    }
}
