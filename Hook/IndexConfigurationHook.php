<?php
/*************************************************************************************/
/* This file is part of the Thelia package.                                          */
/*                                                                                   */
/* Copyright (c) OpenStudio                                                          */
/* email : dev@thelia.net                                                            */
/* web : http://www.thelia.net                                                       */
/*                                                                                   */
/* For the full copyright and license information, please view the LICENSE.txt       */
/* file that was distributed with this source code.                                  */
/*************************************************************************************/

namespace IndexEngine\Hook;

use IndexEngine\Discovering\Collector\LoopSubscriber;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

/**
 * Class IndexConfigurationHook
 * @package IndexEngine\Hook
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class IndexConfigurationHook extends BaseHook
{
    public function onIndexEngineIndexForm(HookRenderEvent $event)
    {
        $event->add($this->render("index-configuration/index-configuration-form.html"));
    }

    public function onIndexEngineIndexFormJavascript(HookRenderEvent $event)
    {
        $event
            ->add($this->render("index-configuration/index-configuration-form-js.html"))
            ->add($this->render("index-configuration/sql-query/query-js.html"))
        ;
    }

    public function onIndexEngineIndexAfterColumns(HookRenderEvent $event)
    {
        if ($event->getArgument("entity") === LoopSubscriber::TYPE) {
            $event
                ->add($this->render("index-configuration/standard/loop-criteria.html"))
                ->add($this->render("index-configuration/standard/loop-criteria-js.html"))
            ;
        }
    }
}
