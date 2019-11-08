<?php

namespace Enovision\MarkdownNotices\Classes;

use October\Rain\Parse\Parsedown\Parsedown;

class Helper
{
    protected $parser;

    var $codeblocks = [];

    public function replaceNotices($text)
    {
        $base_classes = 'notices';
        $level_classes = ['yellow', 'red', 'blue', 'green'];

        $text = $this->maskContentBlocks($text);

        $regex = '/(!{1,4})[ ](.*?)([\r\n|\r|\n]{1}|$)/';

        preg_match_all($regex, $text, $matches, PREG_SET_ORDER, 0);

        $parser = new Parsedown;

        foreach ($matches as $match) {

            $times = 1;

            # ! This is a nice notice
            # ^ ^
            # 1 2

            $search = $match[0];
            $level = $match[1];
            $noticeText = $match[2];

            $levelCount = strlen($level);

            // if we have more levels than we support
            if ($levelCount > count($level_classes)) {
                continue;
            }

            $noticeParsedText = $parser->line($noticeText);

            $base_classes = (empty($base_classes)) ? '' : str_replace(',', ' ', $base_classes) . ' ';

            $replace = sprintf('<div class="%s %s">%s</div>',
                trim($base_classes),
                trim($level_classes[$levelCount - 1]),
                $noticeParsedText
            );

            $text = str_replace($search, $replace, $text, $times);

        }

        /**
         * put back the not parsed code
         */
        foreach ($this->codeblocks as $block) {
            $text = str_replace($block['id'], $block['match'], $text, $times);
        }

        return $text;

    }

    private function maskContentBlocks($text)
    {
        $regex = '/(`{3}.+?`{3})/ms';

        preg_match_all($regex, $text, $matches, PREG_SET_ORDER, 0);

        if ($matches && count($matches[0]) > 0) {
            foreach ($matches[0] as $match) {
                $uniq = '%%%' . uniqid('markdownnotices', true) . '%%%';
                $times = 1;
                $text = str_replace($match, $uniq, $text, $times);

                $this->codeblocks[] = [
                    'id' => $uniq,
                    'match' => $match
                ];
            }
        }

        //var_dump('<pre>', $this->codeblocks, '</pre>');

        return $text;
    }
}