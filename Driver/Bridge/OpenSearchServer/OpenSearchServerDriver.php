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

namespace IndexEngine\Driver\Bridge\OpenSearchServer;

use IndexEngine\Driver\AbstractEventDispatcherAwareDriver;
use IndexEngine\Driver\Exception\MissingLibraryException;

/**
 * Class OpenSearchServerDriver
 * @package IndexEngine\Driver\Bridge\OpenSearchServer
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class OpenSearchServerDriver extends AbstractEventDispatcherAwareDriver
{
    const DEFAULT_HOST = "http://localhost:9090";

    public function checkDependencies()
    {
        if (!class_exists("OpenSearchServer\\Handler")) {
            throw MissingLibraryException::createComposer("opensearchserver/opensearchserver:~3.0", "OpenSearchServer");
        }
    }

    /**
     * @return string
     *
     * This method returns the driver name.
     * It must be a unique string, less than 64 characters and only composed of
     * lower and upper case letters, numbers, underscores, dashes and points.
     *
     * Example: Elasticsearch, OpenSearchServer, ...
     */
    public static function getCode()
    {
        return "OpenSearchServer";
    }
}
