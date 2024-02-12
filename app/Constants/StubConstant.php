<?php

namespace App\Constants;

use App\Constants\BaseConstant;

/**
 * Class StubConstant
 *
 * Constants representing various stub types.
 *
 * @package App\Constants
 */
class StubConstant extends BaseConstant
{
    /**
     * Valid stub types.
     */
    public const MIGRATION = 'migration';
    public const DATA = 'data';
    public const MODEL = 'model';
    public const REQUEST = 'request';
    public const RESOURCE = 'resource';
    public const REPOSITORY = 'repository';
    public const SERVICE = 'service';
    public const CONTROLLER = 'controller';
    public const TEST = 'test';
}
