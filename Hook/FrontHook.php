<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace IndexEngine\Hook;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;


/**
 * Class FrontHook
 * @package IndexEngine\Hook
 * @author Damien Foulhoux
 */
class FrontHook extends BaseHook {
    public function onMainHeadBottom(HookRenderEvent $event)
    {
        $content = $this->addCSS('assets/css/search.css');
        $event->add($content);
    }
    public function onMainNavbarSecondary(HookRenderEvent $event)
    {
        $content = $this->render("search.html");
        $event->add($content);
    }
    public function onMainBodyBottom(HookRenderEvent $event)
    {
        $content = $this->render("search-overlay.html");
        $event->add($content);
    }
    public function OnMainJavascriptInitialization(HookRenderEvent $event){
        $content = $this->render("search-js.html");      
        $event->add($content);
    }

} 