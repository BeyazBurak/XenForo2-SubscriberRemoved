<?php

namespace SV\SubscriberRemoved\Option;

use XF\Entity\Option;
use XF\Option\AbstractOption;

class SubscriberRemovedCreateThread extends AbstractOption
{
    public static function renderOption(Option $option, array $htmlParams)
    {
        $selectData = self::getSelectData($option, $htmlParams);

        $select = self::getTemplater()->formSelect(
            $selectData['controlOptions'], $selectData['choices']
        );

        return self::getTemplate(
            'admin:svSubscriberRemoved_option_template_thread', $option, $htmlParams, [
                'nodeSelect' => $select
            ]
        );
    }

    public static function verifyOption(array &$threadData, Option $option)
    {
        if (isset($threadData['create_thread']))
        {
            /** @var \XF\Entity\User $threadAuthor */
            $threadAuthor = \XF::finder('XF:User')->where('username', $threadData['thread_author'])->fetchOne();

            if (!$threadAuthor)
            {
                $option->error(\XF::phrase('sv_subscriberremoved_invalid_thread_author'));

                return false;
            }
        }

        return true;
    }

    protected static function getSelectData(Option $option, array $htmlParams)
    {
        /** @var \XF\Repository\Node $nodeRepo */
        $nodeRepo = \XF::repository('XF:Node');

        $choices = $nodeRepo->getNodeOptionsData(true, 'Forum', 'option');
        $choices = array_map(
            function ($v) {
                $v['label'] = \XF::escapeString($v['label']);

                return $v;
            }, $choices
        );

        return [
            'choices'        => $choices,
            'controlOptions' => [
                'name'  => $htmlParams['inputName'] . '[node_id]',
                'value' => $option->option_value['node_id']
            ]
        ];
    }
}
