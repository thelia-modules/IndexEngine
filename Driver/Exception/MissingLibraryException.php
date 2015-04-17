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

namespace IndexEngine\Driver\Exception;


/**
 * Class MissingLibraryException
 * @package IndexEngine\Driver\Exception
 * @author Benjamin Perche <benjamin@thelia.net>
 */
class MissingLibraryException extends \RuntimeException
{
    public static function createComposer($package, $libraryName)
    {
        return new static(sprintf(
            "You need to install the a package to use %s. Please run: composer require %s",
            $libraryName,
            $package
        ));
    }
}
